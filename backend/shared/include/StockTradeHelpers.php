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
* Automatically executes buy orders that have no limits and stops, which means purchasing at market price. 
* Matching candidates will be:
* - Sell orders that have no limits and stops (since the price will always be at market price)
* - Sell orders that are share injections or IPOs
* - Sell orders that have stop >= market price
* - Sell orders that have limit <= market price
* - Sell orders that have limit and stop equal to each other (These orders will match for all buy orders)
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
function autoPurchaseNoLimitStop($user_username, $artist_username, $request_quantity, $request_price, $current_market_price)
{
    hx_debug(HX::BUY_SHARES, "request quantity: ".$request_quantity.", request price: ".$request_price."\n\n");
    $conn = connect();
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
            hx_debug(HX::BUY_SHARES, "request quantity >= sell order's number of share case");
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
                if($row['sell_limit'] == $row['sell_stop'] || $row['sell_limit'] <= $request_price || $row['sell_stop'] >= $request_price)
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
            }

            if($will_execute)
            {
                hx_debug(HX::SELL_SHARES, "Case >=:\n".
                                         "Executing on sell order id: ".$row['id']."\n".
                                         "Order quantity: ".$row['no_of_share']."\n".
                                         "buyer username: ".$row['user_username']."\n".
                                         "seller username: ".$user_username."\n".
                                         "seller old balance: ".$seller_account_info['balance']."\n".
                                         "seller new balance: ".$seller_new_balance."\n".
                                         "buyer old balance: ".$buyer_account_info['balance']."\n".
                                         "buyer new balance: ".$buyer_new_balance."\n".
                                         "seller old share amount: ".$seller_account_info['Shares']."\n".
                                         "seller new share amount: ".$seller_new_share_amount."\n".
                                         "buyer old share amount: ".$buyer_account_info['Shares']."\n".
                                         "buyer new share amount: ".$buyer_new_share_amount."\n".
                                         "new price per share: ".$new_pps."\n".
                                         "\n"); 

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
                    "amount" => $row['no_of_share'], 
                    "price" => $request_price, 
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
                                        $row['no_of_share'],
                                        $request_price,
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
                hx_debug(HX::SELL_SHARES, "Case >=:\n".
                                         "Executing on sell order id: ".$row['id']."\n".
                                         "Order quantity: ".$row['no_of_share']."\n".
                                         "buyer username: ".$row['user_username']."\n".
                                         "seller username: ".$user_username."\n".
                                         "seller old balance: ".$seller_account_info['balance']."\n".
                                         "seller new balance: ".$seller_new_balance."\n".
                                         "buyer old balance: ".$buyer_account_info['balance']."\n".
                                         "buyer new balance: ".$buyer_new_balance."\n".
                                         "seller old share amount: ".$seller_account_info['Shares']."\n".
                                         "seller new share amount: ".$seller_new_share_amount."\n".
                                         "buyer old share amount: ".$buyer_account_info['Shares']."\n".
                                         "buyer new share amount: ".$buyer_new_share_amount."\n".
                                         "new price per share: ".$new_pps."\n".
                                         "\n"); 

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
                    "amount" => $request_quantity, 
                    "price" => $request_price, 
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
                                        $request_quantity,
                                        $request_price,
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

    hx_debug(HX::BUY_SHARES, "Request quantity before returning: ".$request_quantity."\n");

    return $request_quantity;
}

/**
* Automatically executes sell orders that have no limits and stops, which means selling at market price. 
* Matching candidates will be:
* - Buy orders that have no limits and stops (since the price will always be at market price)
* - Buy orders that have stop <= market price
* - Buy orders that have limit >= market price
* - Buy orders that have limit and stop equal to each other (These orders will match for all sell orders)
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
* @return 	request_quantity	       the remaining quantity of the buy order after automatically executed, 
*                                      remains the same if no matching sell orders found, 0 if the quantity is less than the quantity in matching sell orders
*/
function autoSellNoLimitStop($user_username, $artist_username, $request_quantity, $request_price, $current_market_price, $is_from_injection)
{
    hx_debug(HX::SELL_SHARES, "request quantity: ".$request_quantity.", request price: ".$request_price."\n\n");
    $conn = connect();
    $connPDO = connectPDO();
    $current_date = date('Y-m-d H:i:s');

    $res = searchMatchingBuyOrderNoLimitStop($conn, $user_username, $artist_username, $current_market_price);
    hx_debug(HX::QUERY, "searchMatchingBuyOrderNoLimitStop returned ".$res->num_rows." entries");

    while($row = $res->fetch_assoc())
    {
        //fail safe
        $will_execute = false;

        $result = searchAccount($conn, $user_username);
        $seller_account_info = $result->fetch_assoc();

        $res_1 = searchAccount($conn, $row['user_username']);
        $buyer_account_info = $res_1->fetch_assoc();

        $buyer_account_type = getAccountType($row['user_username']);
        $seller_account_type = getAccountType($user_username);

        //Assume stock price unchanged
        $new_pps = $current_market_price;

        $buy_mode = ShareInteraction::BUY;
        if ($request_quantity <= 0) 
        {
            break;
        }

        if($is_from_injection)
        {
            $buy_mode = ShareInteraction::BUY_FROM_INJECTION;
        }

        hx_debug(HX::BUY_SHARES, "proceeding with buy_mode: ".$buy_mode);

        //If the sell order is selling more shares than the posted buy order
        if ($request_quantity >= $row['quantity']) 
        {
            hx_debug(HX::BUY_SHARES, "request quantity >= buy order's number of share case");

            //Case when a buy order has no limit and stop
            //This is equivalent to if($row['siliqas_requested'] != -1), but a little bit more safe and a more specific check
            if($row['buy_limit'] == -1 && $row['buy_stop'] == -1)
            {
                $seller_new_balance = $seller_account_info['balance'] + ($row['quantity'] * $row['siliqas_requested']);
                $buyer_new_balance = $buyer_account_info['balance'] - (($row['quantity'] * $row['siliqas_requested']));

                $seller_new_share_amount = $seller_account_info['Shares'] - $row['quantity'];
                $buyer_new_share_amount = $buyer_account_info['Shares'] + $row['quantity'];
                
                $new_pps = $row['siliqas_requested'];

                $will_execute = true;
            }
            //['siliqas_requested'] == -1 indicates that this buy order has a limit or a stop
            else if($row['siliqas_requested'] == -1)
            {
                if($row['buy_limit'] == $row['buy_stop'] || $row['buy_limit'] >= $request_price || ($row['buy_stop'] <= $request_price && $row['buy_stop'] != -1))
                {
                    $seller_new_balance = $seller_account_info['balance'] + ($row['quantity'] * $request_price);
                    $buyer_new_balance = $buyer_account_info['balance'] - (($row['quantity'] * $request_price));
    
                    $seller_new_share_amount = $seller_account_info['Shares'] - $row['quantity'];
                    $buyer_new_share_amount = $buyer_account_info['Shares'] + $row['quantity'];
                    
                    $new_pps = $request_price;
    
                    $will_execute = true;
                }
            }

            if($will_execute)
            {
                hx_debug(HX::BUY_SHARES, "Case >=:\n".
                                         "Executing on buy order id: ".$row['id']."\n".
                                         "Order quantity: ".$row['quantity']."\n".
                                         "buyer username: ".$row['user_username']."\n".
                                         "seller username: ".$user_username."\n".
                                         "seller old balance: ".$seller_account_info['balance']."\n".
                                         "seller new balance: ".$seller_new_balance."\n".
                                         "buyer old balance: ".$buyer_account_info['balance']."\n".
                                         "buyer new balance: ".$buyer_new_balance."\n".
                                         "seller old share amount: ".$seller_account_info['Shares']."\n".
                                         "seller new share amount: ".$seller_new_share_amount."\n".
                                         "buyer old share amount: ".$buyer_account_info['Shares']."\n".
                                         "buyer new share amount: ".$buyer_new_share_amount."\n".
                                         "new price per share: ".$new_pps."\n".
                                         "\n"); 

                hx_debug(HX::BUY_SHARES, "purchaseAskedPriceShare param: ".json_encode(array(
                    "buyer" => $row['user_username'], 
                    "seller" => $user_username, 
                    "buyer_account_type" => $buyer_account_type, 
                    "seller_account_type" => $seller_account_type, 
                    "artist" => $artist_username, 
                    "buyer_new_balance" => $buyer_new_balance, 
                    "seller_new_balance" => $seller_new_balance, 
                    "initial_pps" => $current_market_price, 
                    "new_pps" => $new_pps, 
                    "buyer_new_share_amount" => $buyer_new_share_amount, 
                    "seller_new_share_amount" => $seller_new_share_amount,
                    "amount" => $row['quantity'], 
                    "price" => $request_price, 
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
                                        $current_market_price,
                                        $new_pps,
                                        $buyer_new_share_amount,
                                        $seller_new_share_amount,
                                        $row['quantity'],
                                        $request_price,
                                        $row['id'],
                                        $current_date,
                                        "AUTO_SELL",
                                        $buy_mode);

                hx_info(HX::BUY_SHARES, "Auto selling buy order id ".$row['id'].", amount $".($row['quantity'] * $request_price)." was transfered between buyer ".$row['user_username']." and seller ".$user_username);
                updateBuyOrderQuantity($conn, $row['id'], 0);

                //The return value should be the amount of share requested subtracted by the amount that 
                //is automatically bought
                $request_quantity = $request_quantity - $row['quantity'];
                hx_debug(HX::BUY_SHARES, "quantity has been reduced to ".$request_quantity." after auto selling to buy order ".$row['id']);
            }
        } 
        else if ($request_quantity < $row['quantity']) 
        {
            hx_debug(HX::BUY_SHARES, "request quantity < buy order's number of share case");

            //Case when a buy order has no limit and stop
            //This is equivalent to if($row['siliqas_requested'] != -1), but a little bit more safe and a more specific check
            if($row['buy_limit'] == -1 && $row['buy_stop'] == -1)
            {
                $seller_new_balance = $seller_account_info['balance'] + ($request_quantity * $row['siliqas_requested']);
                $buyer_new_balance = $buyer_account_info['balance'] - (($request_quantity * $row['siliqas_requested']));

                $seller_new_share_amount = $seller_account_info['Shares'] - $request_quantity;
                $buyer_new_share_amount = $buyer_account_info['Shares'] + $request_quantity;

                $new_pps = $row['siliqas_requested'];

                $will_execute = true;
            }
            else if($row['siliqas_requested'] == -1)
            {
                //case when both limit and stop are set to market price
                if($row['buy_limit'] == $row['buy_stop'] || $row['buy_limit'] >= $request_price || ($row['buy_stop'] <= $request_price && $row['buy_stop'] != -1))
                {
                    $seller_new_balance = $seller_account_info['balance'] + ($request_quantity * $request_price);
                    $buyer_new_balance = $buyer_account_info['balance'] - (($request_quantity * $request_price));

                    $seller_new_share_amount = $seller_account_info['Shares'] - $request_quantity;
                    $buyer_new_share_amount = $buyer_account_info['Shares'] + $request_quantity;

                    $new_pps = $request_price;

                    $will_execute = true;
                }
            }

            if($will_execute)
            {
                hx_debug(HX::BUY_SHARES, "Case >=:\n".
                                         "Executing on buy order id: ".$row['id']."\n".
                                         "Order quantity: ".$row['quantity']."\n".
                                         "buyer username: ".$row['user_username']."\n".
                                         "seller username: ".$user_username."\n".
                                         "seller old balance: ".$seller_account_info['balance']."\n".
                                         "seller new balance: ".$seller_new_balance."\n".
                                         "buyer old balance: ".$buyer_account_info['balance']."\n".
                                         "buyer new balance: ".$buyer_new_balance."\n".
                                         "seller old share amount: ".$seller_account_info['Shares']."\n".
                                         "seller new share amount: ".$seller_new_share_amount."\n".
                                         "buyer old share amount: ".$buyer_account_info['Shares']."\n".
                                         "buyer new share amount: ".$buyer_new_share_amount."\n".
                                         "new price per share: ".$new_pps."\n".
                                         "\n"); 

                hx_debug(HX::BUY_SHARES, "purchaseAskedPriceShare param: ".json_encode(array(
                    "buyer" => $row['user_username'], 
                    "seller" => $user_username, 
                    "buyer_account_type" => $buyer_account_type, 
                    "seller_account_type" => $seller_account_type, 
                    "artist" => $artist_username, 
                    "buyer_new_balance" => $buyer_new_balance, 
                    "seller_new_balance" => $seller_new_balance, 
                    "initial_pps" => $current_market_price, 
                    "new_pps" => $new_pps, 
                    "buyer_new_share_amount" => $buyer_new_share_amount, 
                    "seller_new_share_amount" => $seller_new_share_amount,
                    "amount" => $request_quantity, 
                    "price" => $request_price, 
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
                                        $current_market_price,
                                        $new_pps,
                                        $buyer_new_share_amount,
                                        $seller_new_share_amount,
                                        $request_quantity,
                                        $request_price,
                                        $row['id'],
                                        $current_date,
                                        "AUTO_SELL",
                                        $buy_mode);

                $new_buy_order_quantity = $row['quantity'] - $request_quantity;
                hx_info(HX::SELL_SHARES, "Auto selling buy order id ".$row['id'].", amount $".($row['quantity'] * $request_price)." was transfered between buyer ".$row['user_username']." and seller ".$user_username);
                updateBuyOrderQuantity($conn, $row['id'], $new_buy_order_quantity);
                //The return value should be the amount of share requested subtracted by the amount that 
                //is automatically bought
                $request_quantity = $request_quantity - $row['quantity'];
                hx_debug(HX::SELL_SHARES, "quantity has been reduced to ".$request_quantity." after auto selling to buy order ".$row['id']);
            }
        }
    }

    closeCon($conn);

    hx_debug(HX::SELL_SHARES, "Request quantity before returning: ".$request_quantity."\n");
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

/**
* Automatically executes buy orders that have limit set  
* Matching candidates will be:
* - Sell orders that are selling at market price and the current market price is lower than the limit
* - Sell orders that have sell limit set and sell limit <= buy limit 
*
* @param  	user_username	            username of the buyer who is posting the buy order
*
* @param  	artist_username	            artist username whose shares are being requested from
*
* @param  	request_quantity            amount of shares the buyer is requesting
*
* @param  	buy_limit                   limit of the buy order
*
* @param  	current_market_price	    current stock price of the artist's stock
*
* @return 	request_quantity	       the remaining quantity of the buy order after automatically executed, 
*                                      remains the same if no matching sell orders found, 0 if the quantity is less than the quantity in matching sell orders
*/
function autoPurchaseLimitSet($user_username, $artist_username, $request_quantity, $buy_limit, $current_market_price)
{
    $conn = connect();
    $connPDO = connectPDO();
    $buy_mode = ShareInteraction::NONE;
    $current_date = date('Y-m-d H:i:s');
    $update_pps = false;
    $new_pps = $buy_limit;

    $res = searchMatchingSellOrderLimit($conn, $user_username, $artist_username, $buy_limit, $current_market_price);
    while($row = $res->fetch_assoc())
    {
        if($request_quantity <= 0)
        {
            break;
        }

        hx_debug(HX::BUY_SHARES, "request_quantity: ".$request_quantity.", row['no_of_share']: ".$row['no_of_share']."\n");
        $will_execute = false;

        //Purchasing price always favors the buyer in the case of limit set, except for the case when a sell order is at market price
        $purchase_price = $buy_limit;

        $result = searchAccount($conn, $row['user_username']);
        $seller_account_info = $result->fetch_assoc();

        $res_1 = searchAccount($conn, $user_username);
        $buyer_account_info = $res_1->fetch_assoc();

        $buyer_account_type = getAccountType($user_username);
        $seller_account_type = getAccountType($row['user_username']);

        $buyer_new_balance = $buyer_account_info['balance'];
        $seller_new_balance = $seller_account_info['balance'];

        $buyer_new_share_amount = $buyer_account_info['Shares'];
        $seller_new_share_amount = $seller_account_info['Shares'];

        if($row['is_from_injection'])
        {
            $buy_mode = ShareInteraction::BUY_FROM_INJECTION;
        }
        else
        {
            $buy_mode = ShareInteraction::BUY;
        }

        if($request_quantity >= $row['no_of_share'])
        {
            hx_debug(HX::BUY_SHARES, "Case request_quantity >= row['no_of_share']\n".
                                     "Match check on order id: ".$row['id']."\n");
            
            if($row['selling_price'] == $current_market_price)
            {
                $purchase_price = $current_market_price;
                $new_pps = $current_market_price;
                //For market price orders, they only match if the user set their buy limit to be >= to market price
                if($buy_limit >= $current_market_price)
                {
                    $will_execute = true;
                }
            }
            else
            {
                $new_pps = $buy_limit;
                $update_pps = true;
                $will_execute = true;
            }

            if($will_execute)
            {
                hx_info(HX::SELL_SHARES, "Auto purchasing sell order id ".$row['id'].", amount $".($row['no_of_share'] * $purchase_price)." was transfered between buyer ".$user_username." and seller ".$row['user_username']);

                $buyer_new_balance = $buyer_account_info['balance'] - ($row['no_of_share'] * $purchase_price);
                $seller_new_balance = $seller_account_info['balance'] + ($row['no_of_share'] * $purchase_price);

                $buyer_new_share_amount = $buyer_account_info['Shares'] + $row['no_of_share'];
                $seller_new_share_amount = $seller_account_info['Shares'] - $row['no_of_share'];

                hx_debug(HX::BUY_SHARES, "Executing sell order id: ".$row['id']."\n".
                                         "no_of_share: ".$row['no_of_share']."\n".
                                         "purchase_price: ".$purchase_price."\n".
                                         "Buyer: ".$user_username."\n".
                                         "Seller: ".$row['user_username']."\n".
                                         "Buyer old balance: ".$buyer_account_info['balance']."\n".
                                         "Buyer new balance: ".$buyer_new_balance."\n".
                                         "Seller old balance: ".$seller_account_info['balance']."\n".
                                         "Seller new balance: ".$seller_new_balance."\n".
                                         "Buyer old share amount: ".$buyer_account_info['Shares']."\n".
                                         "Buyer new share amount: ".$buyer_new_share_amount."\n". 
                                         "Seller old share amount: ".$seller_account_info['Shares']."\n".
                                         "Seller new share amount: ".$seller_new_share_amount."\n".
                                         "current_market_price: ".$current_market_price."\n".
                                         "new_pps: ".$new_pps."\n".
                                         "buy_mode: ".$buy_mode."\n".
                                         "--------------------------------\n");

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
                                        $row['no_of_share'],
                                        $purchase_price,
                                        $row['id'],
                                        $current_date,
                                        "AUTO_PURCHASE",
                                        $buy_mode);

                $request_quantity = $request_quantity - $row['no_of_share'];
            }
            else
            {
                hx_debug(HX::BUY_SHARES, "Order ".$row['id']." did not match\n");
            }

        }
        else
        {
            hx_debug(HX::BUY_SHARES, "Case request_quantity < row['no_of_share']\n".
                                    "Match check on order id: ".$row['id']."\n");
            if($row['selling_price'] == $current_market_price)
            {
                //For market price orders, they only match if the user set their buy limit to be >= to market price
                if($buy_limit >= $current_market_price)
                {
                    $purchase_price = $current_market_price;
                    $new_pps = $current_market_price;
                    //For market price orders, they only match if the user set their buy limit to be >= to market price
                    if($buy_limit >= $current_market_price)
                    {
                        $will_execute = true;
                    }
                }
            }
            else
            {
                $new_pps = $buy_limit;
                $update_pps = true;
                $will_execute = true;
            }

            if($will_execute)
            {
                hx_info(HX::SELL_SHARES, "Auto purchasing sell order id ".$row['id'].", amount $".($row['no_of_share'] * $purchase_price)." was transfered between buyer ".$user_username." and seller ".$row['user_username']);

                $buyer_new_balance = $buyer_account_info['balance'] - ($request_quantity * $purchase_price);
                $seller_new_balance = $seller_account_info['balance'] + ($request_quantity * $purchase_price);

                $buyer_new_share_amount = $buyer_account_info['Shares'] + $request_quantity;
                $seller_new_share_amount = $seller_account_info['Shares'] - $request_quantity;

                hx_debug(HX::BUY_SHARES, "Executing sell order id: ".$row['id']."\n".
                                         "no_of_share: ".$row['no_of_share']."\n".
                                         "purchase_price: ".$purchase_price."\n".
                                         "Buyer: ".$user_username."\n".
                                         "Seller: ".$row['user_username']."\n".
                                         "Buyer old balance: ".$buyer_account_info['balance']."\n".
                                         "Buyer new balance: ".$buyer_new_balance."\n".
                                         "Seller old balance: ".$seller_account_info['balance']."\n".
                                         "Seller new balance: ".$seller_new_balance."\n".
                                         "Buyer old share amount: ".$buyer_account_info['Shares']."\n".
                                         "Buyer new share amount: ".$buyer_new_share_amount."\n". 
                                         "Seller old share amount: ".$seller_account_info['Shares']."\n".
                                         "Seller new share amount: ".$seller_new_share_amount."\n".
                                         "current_market_price: ".$current_market_price."\n".
                                         "new_pps: ".$new_pps."\n".
                                         "buy_mode: ".$buy_mode."\n".
                                         "--------------------------------\n");

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
                                        $request_quantity,
                                        $purchase_price,
                                        $row['id'],
                                        $current_date,
                                        "AUTO_PURCHASE",
                                        $buy_mode);

                $request_quantity = $request_quantity - $row['no_of_share'];
            }
            else
            {
                hx_debug(HX::BUY_SHARES, "Order ".$row['id']." did not match\n");
            }
        }
    }

    if($update_pps)
    {
        updateMarketPriceOrderToPPS($new_pps, $artist_username);
    }

    return $request_quantity;
}
?>