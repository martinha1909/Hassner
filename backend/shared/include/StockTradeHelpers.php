<?php
/**
* Updates any buy orders or sell orders that have no limit or stop to the current market price 
*
* @param  	new_pps                 current stock price of a specific artist
*
* @param  	artist_username         given artist to query orders
*
*/
function updateMarketPriceOrderToPPS($new_pps, $artist_username)
{
    $conn = connect();
    $update_err_code = StatusCodes::NONE;
    
    $res_sell_order = searchAllSellOrdersNoLimitStop($conn, $artist_username);
    while($row = $res_sell_order->fetch_assoc())
    {
        $update_err_code = updateSellOrderPPS($new_pps, $row['id']);
        if($update_err_code != StatusCodes::Success)
        {
            hx_error(HX::SELL_ORDER, "Could not update selling price for sell order ".$row['id']);
        }
    }

    $res_buy_order = searchAllBuyOrdersNoLimitStop($conn, $artist_username);
    while($row = $res_buy_order->fetch_assoc())
    {
        $update_err_code = updateBuyOrderPPS($new_pps, $row['id']);
        if($update_err_code != StatusCodes::Success)
        {
            hx_error(HX::SELL_ORDER, "Could not update requesting price for buy order ".$row['id']);
        }
    }
}

/**
* Automatically purchases buy orders that have no limits and stops, which means purchasing at market price. 
* Matching candidates will be:
* - Sell orders that have no limits and stops (since the price will always be at market price)
* - Sell orders that are share injections or IPOs
* - Sell orders that have stop >= market price
* - Sell orders that have limit <= market price
* - Sell orders that have limit and stop equal to each other (These orders are matching for every buy order)
*
* @param  	conn	                    a connection to the db
*
* @param  	user_username	            username of the buyer who is posting the buy order
*
* @param  	artist_username	            artist username whose shares are being requested from
*
* @param  	request_quantity            amount of shares the buyer is requesting
*
* @param  	request_price               requesting price specified by the buyer, this is used to find matching sell orders
*
* @param  	current_market_price	    current stock price of the artist's stock
*
* @param  	user_share_amount	        amount of shares the user have towards the artist
*
*
* @return 	request_quantity	       the remaining quantity of the buy order after automatically executed, 
*                                      remains the same if no matching sell orders found, 0 if the quantity is less than the quantity in matching sell orders
*/
function autoPurchase($conn, $user_username, $artist_username, $request_quantity, $request_price, $current_market_price, $user_share_amount)
{
    $connPDO = connectPDO();
    $current_date = date('Y-m-d H:i:s');
    $res = searchMatchingSellOrderNoLimitStop($conn, $user_username, $artist_username, $current_market_price);
    hx_debug(HX::QUERY, "searchMatchingSellOrderNoLimitStop returned ".$res->num_rows." entries");

    while($row = $res->fetch_assoc())
    {
        //fail safe
        $will_execute = false;

        $result = searchAccount($conn, $row['user_username']);
        $seller_account_info = $result->fetch_assoc();

        $res_1 = searchAccount($conn, $user_username);
        $buyer_account_info = $res_1->fetch_assoc();

        $buyer_account_type = getAccountType($user_username);
        $seller_account_type = getAccountType($row['user_username']);


        //Initialize these variables to be the original values in db to be safe
        $buyer_new_balance = $buyer_account_info['balance'];
        $seller_new_balance = $seller_account_info['balance'];

        $seller_new_share_amount = $seller_account_info['Shares'];
        $buyer_new_share_amount = $buyer_account_info['Shares'];

        //Assume stock price unchanged
        $new_pps = $current_market_price;

        //Assuming p2p trading
        $buy_mode = ShareInteraction::BUY;
        if($request_quantity <= 0)
        {
            break;
        }
        if($row['is_from_injection'])
        {
            $buy_mode = ShareInteraction::BUY_FROM_INJECTION;
        }

        
        if($request_quantity >= $row['no_of_share'])
        {
            hx_debug(HX::BUY_SHARES, "request quantity >= sell order number of share case");
            //Case when a sell order has no limit or stop
            if($row['sell_limit'] == -1 && $row['sell_stop'] == -1)
            {
                hx_debug(HX::SELL_SHARES, "Matching sell order id: ".$row['id'].", injection sell order with no limit or stop, order details: ".json_encode($row));
                
                //in this case we will use the standard market price
                $seller_new_balance = $seller_account_info['balance'] + ($row['no_of_share'] * $row['selling_price']);
                $buyer_new_balance = $buyer_account_info['balance'] - ($row['no_of_share'] * $row['selling_price']);

                $seller_new_share_amount = $seller_account_info['Shares'] - $row['no_of_share'];
                $buyer_new_share_amount = $buyer_account_info['Shares'] + $row['no_of_share'];

                $new_pps = $row['selling_price'];

                $will_execute = true;
            }
            //['selling_price'] == -1 indicates that this sell order has a limit or a stop
            else if($row['selling_price'] == -1)
            {
                //case when both limit and stop are set to equal to each other
                if($row['sell_limit'] == $row['sell_stop'])
                {
                    hx_debug(HX::SELL_SHARES, "Matching sell order id: ".$row['id'].", sell order with both limit and stop set to market price, order details: ".json_encode($row));

                    //in this case we will also use standard market price
                    $seller_new_balance = $seller_account_info['balance'] + ($row['no_of_share'] * $request_price);
                    $buyer_new_balance = $buyer_account_info['balance'] - ($row['no_of_share'] * $request_price);
    
                    $seller_new_share_amount = $seller_account_info['Shares'] - $row['no_of_share'];
                    $buyer_new_share_amount = $buyer_account_info['Shares'] + $row['no_of_share'];
    
                    $new_pps = $request_price;

                    $will_execute = true;
                }
                //matching case of a limit set
                else if($row['sell_limit'] <= $request_price)
                {
                    hx_debug(HX::SELL_SHARES, "Matching sell order id: ".$row['id'].", sell order with matching limit, order details: ".json_encode($row));

                    //in this case we will also use standard market price
                    $seller_new_balance = $seller_account_info['balance'] + ($row['no_of_share'] * $request_price);
                    $buyer_new_balance = $buyer_account_info['balance'] - ($row['no_of_share'] * $request_price);
    
                    $seller_new_share_amount = $seller_account_info['Shares'] - $row['no_of_share'];
                    $buyer_new_share_amount = $buyer_account_info['Shares'] + $row['no_of_share'];
    
                    $new_pps = $request_price;

                    $will_execute = true;
                }
                //matching case of a stop set
                else if($row['sell_stop'] >= $request_price)
                {
                    hx_debug(HX::SELL_SHARES, "Matching sell order id: ".$row['id'].", sell order with matching stop, order details: ".json_encode($row));

                    //in this case we will also use standard market price
                    $seller_new_balance = $seller_account_info['balance'] + ($row['no_of_share'] * $request_price);
                    $buyer_new_balance = $buyer_account_info['balance'] - ($row['no_of_share'] * $request_price);
    
                    $seller_new_share_amount = $seller_account_info['Shares'] - $row['no_of_share'];
                    $buyer_new_share_amount = $buyer_account_info['Shares'] + $row['no_of_share'];
    
                    $new_pps = $request_price;

                    $will_execute = true;
                }
            }

            if($will_execute)
            {
                hx_debug(HX::SELL_SHARES, "purchaseAskedPriceShare param: ".json_encode(array(
                    "buyer" => $user_username, 
                    "seller" => $row['user_username'], 
                    "buyer_account_type" => $buyer_account_type, 
                    "seller_account_type" => $seller_account_type, 
                    "artist" => $artist_username, 
                    "buyer_new_balance" => $buyer_new_balance, 
                    "seller_new_balance" => $seller_new_balance, 
                    "initial_pps" => $current_market_price, 
                    "new_pps" => $new_pps, 
                    "buyer_new_share_amount" => $buyer_new_share_amount, 
                    "seller_new_share_amount" => $seller_new_share_amount, 
                    "shares_owned" => $user_share_amount, 
                    "amount" => $row['no_of_share'], 
                    "price" => $row['selling_price'], 
                    "order_id" => $row['id'], 
                    "date_purchased" => $current_date, 
                    "indicator" => "AUTO_PURCHASE", 
                    "buy_mode"  => $buy_mode
                )));
    
                purchaseAskedPriceShare($connPDO, 
                                        $user_username, 
                                        $row['user_username'], 
                                        $buyer_account_type,
                                        $seller_account_type,
                                        $artist_username,
                                        $buyer_new_balance, 
                                        $seller_new_balance, 
                                        $current_market_price, 
                                        $new_pps, 
                                        $buyer_new_share_amount, 
                                        $seller_new_share_amount,
                                        $user_share_amount, 
                                        $row['no_of_share'],
                                        $row['selling_price'],
                                        $row['id'],
                                        $current_date,
                                        "AUTO_PURCHASE",
                                        $buy_mode);
    
                hx_info(HX::SELL_SHARES, "Auto purchasing sell order id ".$row['id'].", amount $".($row['no_of_share'] * $request_price)." was transfered between buyer ".$_SESSION['username']." and seller ".$row['user_username']);
                
    
                //The return value should be the amount of share requested subtracted by the amount that 
                //is automatically bought
                $request_quantity = $request_quantity - $row['no_of_share'];
                hx_debug(HX::SELL_SHARES, "quantity has been reduced to ".$request_quantity." after auto selling to buy order ".$row['id']);
            }
        }
        else if($request_quantity < $row['no_of_share'])
        {
            hx_debug(HX::BUY_SHARES, "request quantity < sell order number of share case");
            //Case when a sell order has no limit or stop
            if($row['sell_limit'] == -1 && $row['sell_stop'] == -1)
            {
                hx_debug(HX::SELL_SHARES, "Matching sell order id: ".$row['id'].", injection sell order with no limit or stop, order details: ".json_encode($row));
                
                //in this case we will use the standard market price
                $seller_new_balance = $seller_account_info['balance'] + ($request_quantity * $row['selling_price']); 
                $buyer_new_balance = $buyer_account_info['balance'] - ($request_quantity * $row['selling_price']);

                $seller_new_share_amount = $seller_account_info['Shares'] - $request_quantity;
                $buyer_new_share_amount = $buyer_account_info['Shares'] + $request_quantity;

                $new_pps = $row['selling_price'];

                $will_execute = true;
            }
            //['selling_price'] == -1 indicates that this sell order has a limit or a stop
            else if($row['selling_price'] == -1)
            {
                //case when both limit and stop are set to market price
                if($row['sell_limit'] == $row['sell_stop'])
                {
                    hx_debug(HX::SELL_SHARES, "Matching sell order id: ".$row['id'].", sell order with both limit and stop set to market price, order details: ".json_encode($row));

                    //in this case we will also use standard market price
                    $seller_new_balance = $seller_account_info['balance'] + ($request_quantity * $request_price); 
                    $buyer_new_balance = $buyer_account_info['balance'] - ($request_quantity * $request_price);

                    $seller_new_share_amount = $seller_account_info['Shares'] - $request_quantity;
                    $buyer_new_share_amount = $buyer_account_info['Shares'] + $request_quantity;

                    $new_pps = $request_price;

                    $will_execute = true;
                }
                //matching case of a limit set
                else if($row['sell_limit'] <= $request_price)
                {
                    hx_debug(HX::SELL_SHARES, "Matching sell order id: ".$row['id'].", sell order with matching limit, order details: ".json_encode($row));

                    //in this case we will also use standard market price
                    $seller_new_balance = $seller_account_info['balance'] + ($request_quantity * $request_price); 
                    $buyer_new_balance = $buyer_account_info['balance'] - ($request_quantity * $request_price);

                    $seller_new_share_amount = $seller_account_info['Shares'] - $request_quantity;
                    $buyer_new_share_amount = $buyer_account_info['Shares'] + $request_quantity;

                    $new_pps = $request_price;

                    $will_execute = true;
                }
                //matching case of a stop set
                else if($row['sell_stop'] >= $request_price)
                {
                    hx_debug(HX::SELL_SHARES, "Matching sell order id: ".$row['id'].", sell order with matching stop, order details: ".json_encode($row));

                    //in this case we will also use standard market price
                    $seller_new_balance = $seller_account_info['balance'] + ($request_quantity * $request_price); 
                    $buyer_new_balance = $buyer_account_info['balance'] - ($request_quantity * $request_price);

                    $seller_new_share_amount = $seller_account_info['Shares'] - $request_quantity;
                    $buyer_new_share_amount = $buyer_account_info['Shares'] + $request_quantity;

                    $new_pps = $request_price;

                    $will_execute = true;
                }
            }

            if($will_execute)
            {
                hx_debug(HX::SELL_SHARES, "purchaseAskedPriceShare param: ".json_encode(array(
                    "buyer" => $user_username, 
                    "seller" => $row['user_username'], 
                    "buyer_account_type" => $buyer_account_type, 
                    "seller_account_type" => $seller_account_type, 
                    "artist" => $artist_username, 
                    "buyer_new_balance" => $buyer_new_balance, 
                    "seller_new_balance" => $seller_new_balance, 
                    "initial_pps" => $current_market_price, 
                    "new_pps" => $new_pps, 
                    "buyer_new_share_amount" => $buyer_new_share_amount, 
                    "seller_new_share_amount" => $seller_new_share_amount, 
                    "shares_owned" => $user_share_amount, 
                    "amount" => $request_quantity, 
                    "price" => $row['selling_price'], 
                    "order_id" => $row['id'], 
                    "date_purchased" => $current_date, 
                    "indicator" => "AUTO_PURCHASE", 
                    "buy_mode"  => $buy_mode
                )));

                purchaseAskedPriceShare($connPDO, 
                                        $user_username, 
                                        $row['user_username'], 
                                        $buyer_account_type,
                                        $seller_account_type,
                                        $artist_username,
                                        $buyer_new_balance, 
                                        $seller_new_balance, 
                                        $current_market_price, 
                                        $new_pps, 
                                        $buyer_new_share_amount, 
                                        $seller_new_share_amount,
                                        $user_share_amount, 
                                        $request_quantity,
                                        $row['selling_price'],
                                        $row['id'],
                                        $current_date,
                                        "AUTO_PURCHASE",
                                        $buy_mode);

                hx_info(HX::SELL_SHARES, "Auto purchasing sell order id ".$row['id'].", amount $".($row['no_of_share'] * $request_price)." was transfered between buyer ".$_SESSION['username']." and seller ".$row['user_username']);
                //The return value should be the amount of share requested subtracted by the amount that 
                //is automatically bought
                $request_quantity = $request_quantity - $row['no_of_share'];
                hx_debug(HX::SELL_SHARES, "quantity has been reduced to ".$request_quantity." after auto selling to buy order ".$row['id']);
            }
        }
    }

    return $request_quantity;
}

/**
* Automatically sells the intended sell order (before posting), if there is any matching buy orders (sell price = requested price).
* If a sell order has a higher amount of shares selling than the matching buy order, the buy order will get deleted and the seller 
* will perform a transaction equivalent to the amount in that buy order. The remaining amount of the sell order will get posted
* If a sell order has a lower amount of shares selling than the matching buy order, the purchasing quantity of the buy order will be reduced
* and the buyer will automatically purchases all the quantity in the sell order, Hence the sell order won't be posted
*
* @param  	user_username	   username of the seller who is posting the sell order
*
* @param  	artist_username	   artist username whose shares are being sold
*
* @param  	asked_price	       selling price specified by the seller, this is used to find matching buy orders
*
* @param  	quantity	       amount of shares the seller is selling
*
* @param  	current_date	   date and time at the time the sell order is being created
*
* @param  	buy_mode	       share interaction mode
*
*
* @return 	quantity	       the remaining quantity of the sell order after automatically executed, 
*                              remains the same if no matching buy orders found, 0 if the quantity is less than the quantity in matching buy orders
*/
function autoSell($user_username, $artist_username, $asked_price, $quantity, $current_date, $is_from_injection)
{
    $conn = connect();

    $res = searchBuyOrdersByArtist($conn, $artist_username);
    hx_debug(HX::QUERY, "searchBuyOrdersByArtist returned ".$res->num_rows." entries");

    while ($row = $res->fetch_assoc()) 
    {
        $buy_mode = ShareInteraction::BUY;
        if ($quantity <= 0) {
            break;
        }

        if ($row['user_username'] == $user_username) {
            continue;
        }

        if ($row['siliqas_requested'] == $asked_price) 
        {
            hx_debug(HX::SELL_SHARES, "Matching buy order id: ".$row['id']." for price $".$asked_price);

            if($is_from_injection)
            {
                $buy_mode = ShareInteraction::BUY_FROM_INJECTION;
            }

            hx_debug(HX::SELL_SHARES, "proceeding with buy_mode: ".$buy_mode);

            //If the sell order is selling more shares than the posted buy order
            if ($quantity >= $row['quantity']) {
                $current_date_time = getCurrentDate("America/Edmonton");
                $date_parser = dayAndTimeSplitter($current_date_time);

                $result = searchAccount($conn, $user_username);
                $account_info = $result->fetch_assoc();

                //if the user buys from the bid price, the siliqas will go to the other user since they are the seller
                $seller_new_balance = $account_info['balance'] + ($row['quantity'] * $asked_price);

                $seller_new_share_amount = $account_info['Shares'] - $row['quantity'];

                $res_1 = searchAccount($conn, $row['user_username']);
                $buyer_account_info = $res_1->fetch_assoc();
                $buyer_new_share_amount = $buyer_account_info['Shares'] + $row['quantity'];

                //subtracts siliqas from the user
                $buyer_new_balance = $buyer_account_info['balance'] - (($row['quantity'] * $asked_price));

                //In the case of buying in asked price, the new market price will become the last purchased price
                $new_pps = $asked_price;

                $buyer_account_type = getAccountType($row['user_username']);
                $seller_account_type = getAccountType($user_username);

                $connPDO = connectPDO();

                hx_debug(HX::SELL_SHARES, "purchaseAskedPriceShare param: ".json_encode(array(
                    "buyer" => $row['user_username'], 
                    "seller" => $user_username, 
                    "buyer_account_type" => $buyer_account_type, 
                    "seller_account_type" => $seller_account_type, 
                    "artist" => $artist_username, 
                    "buyer_new_balance" => $buyer_new_balance, 
                    "seller_new_balance" => $seller_new_balance, 
                    "initial_pps" => $_SESSION['current_pps']['price_per_share'], 
                    "new_pps" => $new_pps, 
                    "buyer_new_share_amount" => $buyer_new_share_amount, 
                    "seller_new_share_amount" => $seller_new_share_amount, 
                    "shares_owned" => $_SESSION['shares_owned'], 
                    "amount" => $row['quantity'], 
                    "price" => $row['siliqas_requested'], 
                    "order_id" => $row['id'], 
                    "date_purchased" => $current_date, 
                    "indicator" => "AUTO_SELL", 
                    "buy_mode"  => $buy_mode
                )));

                purchaseAskedPriceShare($connPDO,
                                        $row['user_username'],
                                        $user_username,
                                        $buyer_account_type,
                                        $seller_account_type,
                                        $artist_username,
                                        $buyer_new_balance,
                                        $seller_new_balance,
                                        $_SESSION['current_pps']['price_per_share'],
                                        $new_pps,
                                        $buyer_new_share_amount,
                                        $seller_new_share_amount,
                                        $_SESSION['shares_owned'],
                                        $row['quantity'],
                                        $row['siliqas_requested'],
                                        $row['id'],
                                        $current_date,
                                        "AUTO_SELL",
                                        $buy_mode);

                hx_info(HX::BUY_SHARES, "Auto selling buy order id ".$row['id'].", amount $".($row['quantity'] * $asked_price)." was transfered between buyer ".$row['user_username']." and seller ".$user_username);
                updateBuyOrderQuantity($conn, $row['id'], 0);

                //The return value should be the amount of share requested subtracted by the amount that 
                //is automatically bought
                $quantity = $quantity - $row['quantity'];
                hx_debug(HX::SELL_SHARES, "quantity has been reduced to ".$quantity." after auto selling to buy order ".$row['id']);
            } else if ($quantity < $row['quantity']) {
                $current_date_time = getCurrentDate("America/Edmonton");
                $date_parser = dayAndTimeSplitter($current_date_time);

                $result = searchAccount($conn, $user_username);
                $account_info = $result->fetch_assoc();

                //if the user buys from the bid price, the siliqas will go to the other user since they are the seller
                $seller_new_balance = $account_info['balance'] + ($quantity * $asked_price);

                $seller_new_share_amount = $account_info['Shares'] - $quantity;

                $res_1 = searchAccount($conn, $row['user_username']);
                $buyer_account_info = $res_1->fetch_assoc();
                $buyer_new_share_amount = $buyer_account_info['Shares'] + $quantity;

                //subtracts siliqas from the user
                $buyer_new_balance = $buyer_account_info['balance'] - (($quantity * $asked_price));

                //In the case of buying in asked price, the new market price will become the last purchased price
                $new_pps = $asked_price;

                $buyer_account_type = getAccountType($row['user_username']);
                $seller_account_type = getAccountType($user_username);

                $connPDO = connectPDO();

                hx_debug(HX::SELL_SHARES, "purchaseAskedPriceShare param: ".json_encode(array(
                    "buyer" => $row['user_username'], 
                    "seller" => $user_username, 
                    "buyer_account_type" => $buyer_account_type, 
                    "seller_account_type" => $seller_account_type, 
                    "artist" => $artist_username, 
                    "buyer_new_balance" => $buyer_new_balance, 
                    "seller_new_balance" => $seller_new_balance, 
                    "initial_pps" => $_SESSION['current_pps']['price_per_share'], 
                    "new_pps" => $new_pps, 
                    "buyer_new_share_amount" => $buyer_new_share_amount, 
                    "seller_new_share_amount" => $seller_new_share_amount, 
                    "shares_owned" => $_SESSION['shares_owned'], 
                    "amount" => $quantity, 
                    "price" => $row['siliqas_requested'], 
                    "order_id" => $row['id'], 
                    "date_purchased" => $current_date, 
                    "indicator" => "AUTO_SELL", 
                    "buy_mode"  => $buy_mode
                )));

                purchaseAskedPriceShare($connPDO,
                                        $row['user_username'],
                                        $user_username,
                                        $buyer_account_type,
                                        $seller_account_type,
                                        $artist_username,
                                        $buyer_new_balance,
                                        $seller_new_balance,
                                        $_SESSION['current_pps']['price_per_share'],
                                        $new_pps,
                                        $buyer_new_share_amount,
                                        $seller_new_share_amount,
                                        $_SESSION['shares_owned'],
                                        $quantity,
                                        $row['siliqas_requested'],
                                        $row['id'],
                                        $current_date,
                                        "AUTO_SELL",
                                        $buy_mode);

                $new_buy_order_quantity = $row['quantity'] - $quantity;
                hx_info(HX::SELL_SHARES, "Auto selling buy order id ".$row['id'].", amount $".($row['quantity'] * $asked_price)." was transfered between buyer ".$row['user_username']." and seller ".$user_username);
                updateBuyOrderQuantity($conn, $row['id'], $new_buy_order_quantity);
                //The return value should be the amount of share requested subtracted by the amount that 
                //is automatically bought
                $quantity = $quantity - $row['quantity'];
                hx_debug(HX::SELL_SHARES, "quantity has been reduced to ".$quantity." after auto selling to buy order ".$row['id']);
            }
        }
    }
    return $quantity;
}

function autoPurchaseWithLimitSet($user_username, $artist_username, $request_quantity, $limit_price, $current_market_price, $user_share_amount)
{
    $conn = connect();
    
    closeCon($conn);
}


?>