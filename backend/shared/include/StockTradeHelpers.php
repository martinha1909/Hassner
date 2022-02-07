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
        else
        {
            hx_info(HX::SELL_ORDER, "Sell order with id ".$row['id']." updated selling price to ".$new_pps);
        }
    }

    $res_buy_order = searchAllBuyOrdersNoLimitStop($conn, $artist_username);
    while($row = $res_buy_order->fetch_assoc())
    {
        //Need to do this for buy orders at market price since the price has now changed,
        //the user might not be able to afford buying the same quantity at the new price
        if($new_pps > $row['siliqas_requested'])
        {
            $user_balance = getUserBalance($row['user_username']);
            $max_num_of_shares = (int)($user_balance/$new_pps);

            hx_debug(HX::BUY_ORDER, "Quantity check on market price buy order (id: ".$row['id']."):\n".
                                    "Buyer: ".$row['user_username']."\n".
                                    "Buyer current balance: ".$user_balance."\n".
                                    "Old sub-total from order before price change: ".($row['quantity'] * $row['siliqas_requested']),"\n".
                                    "New sub-total from order after price change: ".($row['quantity'] * $new_pps));

            if($max_num_of_shares < $row['quantity'])
            {
                $quantity_err_code = StatusCodes::NONE;
                $quantity_err_code = updateBuyOrderQuantity($conn, $row['id'], $max_num_of_shares);
                if($quantity_err_code != StatusCodes::Success)
                {
                    hx_error(HX::BUY_ORDER, "Failed to update quantity for buy order id ".$row['id']);
                }
                else
                {
                    hx_info(HX::BUY_ORDER, "Buy order with id ".$row['id']." updated requesting quantity to ".$max_num_of_shares);
                }
            }
            else
            {
                hx_debug (HX::BUY_ORDER, "Did not update buy order requesting quantity for buy order ".$row['id']);
            }
        }

        $update_err_code = updateBuyOrderPPS($new_pps, $row['id']);
        if($update_err_code != StatusCodes::Success)
        {
            hx_error(HX::SELL_ORDER, "Could not update requesting price for buy order ".$row['id']);
        }
        else
        {
            hx_info(HX::BUY_ORDER, "Buy order with id ".$row['id']." updated requesting price to ".$new_pps);
        }
    }
}

/**
* Initializes auto purchasing process, which includes:
* - Determining who the seller is and their information
* - Determining who the buyer is and their information
* - Determining who the artist whose stock is being traded is
*
* @param  	conn                            db connection
* @param  	buyer_username                  buyer in this transaction, could be the same as artist_username in the case of a buy back
* @param  	seller_username                 seller in this transaction
* @param  	artist_username                 artist whose stock is being traded
*
* @param  	ret                             an AutoTransaction object, containing the buyer information, seller information, and the artist username
*
*/
function autoPurchaseInit($conn, $buyer_username, $seller_username, $artist_username): AutoTransact
{
    $ret = new AutoTransact();

    $result = searchAccount($conn, $buyer_username);
    $buyer_account_info = $result->fetch_assoc();

    $result = searchAccount($conn, $seller_username);
    $seller_account_info = $result->fetch_assoc();

    $ret->setBuyerInfo($buyer_account_info);
    $ret->setSellerInfo($seller_account_info);
    $ret->setArtist($artist_username);

    return $ret;
}

/**
* Performs the transaction between the buyer and the seller. Responsible for:
* - updating the shares amount of the buyer and seller after the transaction
* - Update the balance of the buyer and seller after the transaction
* - Update the artist stock price
* - Add a row to buy_history table
* - Update the buy/sell order that was being executed
* - If stock price changes, update all market price buy and sell orders to the new stock price
*
* @param  	connPDO                 db connection
* @param  	transact                AutoTransact object, containing buyer, seller, and artist info
* @param  	old_pps                 current stock price
* @param  	new_pps                 value of the stock after this transaction is done
* @param  	purchase_price          purchasing price
* @param  	execute_quantity        stock quantity that is being traded
* @param  	order_info              buy/sell order information
* @param  	buy_mode                buying mode (buy from injection, p2p, etc.)
* @param  	buy_or_sell             a boolean, indicates if the transaction is buying or selling
*/
function doTransaction($connPDO, $transact, $old_pps, $new_pps, $purchase_price, $execute_quantity, $order_info, $buy_mode, $buy_or_sell)
{
    $auto_param = "AUTO_PURCHASE";

    if($buy_or_sell == ShareInteraction::SELL)
    {
        $auto_param = "AUTO_SELL";
    }

    $current_date = date('Y-m-d H:i:s');

    $buyer_new_balance = $transact->getBuyerInfo()['balance'] - ($execute_quantity * $purchase_price);
    $seller_new_balance = $transact->getSellerInfo()['balance'] + ($execute_quantity * $purchase_price);

    $buyer_new_share_amount = $transact->getBuyerInfo()['Shares'] + $execute_quantity;
    $seller_new_share_amount = $transact->getSellerInfo()['Shares'] - $execute_quantity;

    hx_debug(HX::BUY_SHARES, "Executing sell order id: ".$order_info['id']."\n".
                             "no_of_share: ".$execute_quantity."\n".
                             "purchase_price: ".$purchase_price."\n".
                             "Buyer: ".$transact->getBuyerInfo()['username']."\n".
                             "Seller: ".$transact->getSellerInfo()['username']."\n".
                             "Buyer old balance: ".$transact->getBuyerInfo()['balance']."\n".
                             "Buyer new balance: ".$buyer_new_balance."\n".
                             "Seller old balance: ".$transact->getSellerInfo()['balance']."\n".
                             "Seller new balance: ".$seller_new_balance."\n".
                             "Buyer old share amount: ".$transact->getBuyerInfo()['Shares']."\n".
                             "Buyer new share amount: ".$buyer_new_share_amount."\n". 
                             "Seller old share amount: ".$transact->getSellerInfo()['Shares']."\n".
                             "Seller new share amount: ".$seller_new_share_amount."\n".
                             "current_market_price: ".$old_pps."\n".
                             "new_pps: ".$new_pps."\n".
                             "buy_mode: ".$buy_mode."\n".
                             "--------------------------------\n");

    purchaseAskedPriceShare($connPDO,
                            $transact->getBuyerInfo()['username'],
                            $transact->getSellerInfo()['username'],
                            $transact->getBuyerInfo()['account_type'],
                            $transact->getSellerInfo()['account_type'],
                            $transact->getArtist(),
                            $buyer_new_balance,
                            $seller_new_balance,
                            $old_pps,
                            $new_pps,
                            $buyer_new_share_amount,
                            $seller_new_share_amount,
                            $execute_quantity,
                            $purchase_price,
                            $order_info['id'],
                            $current_date,
                            $auto_param,
                            $buy_mode);
}

/**
* Buys all market price buy order. Will exit if:
* - request quantity becomes 0 (after executing, if the request amount is less than the amount specified in the order)
* - no orders found (the current executing order is older than the other market price buy orders)
*
* @param  	conn                            db connection
* @param  	connPDO                         transaction-lock db connection
* @param  	user_username                   seller
* @param  	artist_username                 artist whose stock is being traded
* @param  	request_quantity                quantity that the seller is selling
* @param  	current_exe_date                current date of the sell order being sold
* @param  	market_price                    current artist's stock price
* @param  	is_from_injection               determine if this sell order that is being sold to buy orders is from an injection or not
*
* @return   ret                             the remaining quantity of the sell order that was sent to execute market price buy orders
*/
function executeMarketPriceBuyOrders($conn, $connPDO, $user_username, $artist_username, $request_quantity, $current_exe_date, $market_price, $is_from_injection)
{
    hx_debug(HX::SELL_SHARES, "Performing market price buy orders execution...");
    $buy_mode = ShareInteraction::NONE;

    $res = searchOlderBuyOrders($conn, $user_username, $artist_username, $current_exe_date);
    hx_debug(HX::SELL_SHARES, "found ".$res->num_rows." matching market price buy orders");
    if($res->num_rows > 0)
    {
        while($row = $res->fetch_assoc())
        {
            $will_execute = false;
            if($row['siliqas_requested'] != -1 && $row['siliqas_requested'] == $market_price)
            {
                $will_execute = true;
            }
            hx_debug(HX::BUY_ORDER, "current_exe_date: ".$current_exe_date.", buy order's date_posted: ".$row['date_posted']."\n".
                                    "Found matching market price buy order with id ".$row['id']."\n".
                                    "------------------------------------");
            if($request_quantity <= 0)
            {
                break;
            }

            if($will_execute)
            {

                $transact = autoPurchaseInit($conn, $row['user_username'], $user_username, $artist_username);

                if($is_from_injection)
                {
                    $buy_mode = ShareInteraction::BUY_FROM_INJECTION;
                }
                else
                {
                    $buy_mode = ShareInteraction::BUY;
                }

                if($request_quantity >= $row['quantity'])
                {
                    hx_debug(HX::SELL_SHARES, "Case request_quantity >= row['quantity'] in executeMarketPriceBuyOrders\n".
                                            "Match check on order id: ".$row['id']);
                    doTransaction($connPDO,
                                $transact,
                                $market_price,
                                $market_price,
                                $row['siliqas_requested'],
                                $row['quantity'],
                                $row,
                                $buy_mode,
                                ShareInteraction::SELL);

                    hx_info(HX::SELL_SHARES, "Auto selling buy order id ".$row['id'].", amount $".($row['quantity'] * $row['siliqas_requested'])." was transfered between buyer ".$row['user_username']." and seller ".$user_username);
                    
                    removeBuyOrder($conn, $row['id']);
                }
                else
                {
                    hx_debug(HX::SELL_SHARES, "Case request_quantity < row['quantity'] in executeMarketPriceBuyOrders\n".
                                            "Match check on order id: ".$row['id']);
                    doTransaction($connPDO,
                                $transact,
                                $market_price,
                                $market_price,
                                $row['siliqas_requested'],
                                $request_quantity,
                                $row,
                                $buy_mode,
                                ShareInteraction::SELL);

                    hx_info(HX::SELL_SHARES, "Auto selling buy order id ".$row['id'].", amount $".($request_quantity * $row['siliqas_requested'])." was transfered between buyer ".$row['user_username']." and seller ".$user_username);
                }

                $request_quantity -= $row['quantity'];
            }
        }
    }
    else
    {
        hx_debug(HX::SELL_SHARES, "No market price buy orders found\nExitting market price orders execution...");
    }

    return $request_quantity;
}

/**
* Sells all market price sell order. Will exit if:
* - request quantity becomes 0 (after executing, if the request amount is less than the amount specified in the order)
* - no orders found (the current executing order is older than the other market price sell orders)
*
* @param  	conn                            db connection
* @param  	connPDO                         transaction-lock db connection
* @param  	user_username                   buyer
* @param  	artist_username                 artist whose stock is being traded
* @param  	request_quantity                quantity that the buyer is requesting
* @param  	current_exe_date                current date of the buy order being bought
* @param  	market_price                    current artist's stock price
*
* @return   ret                             the remaining quantity of the buy order that was sent to execute market price sell orders
*/
function executeMarketPriceSellOrders($conn, $connPDO, $user_username, $artist_username, $request_quantity, $current_exe_date, $market_price)
{
    $buy_mode = ShareInteraction::NONE;

    $res = searchOlderSellOrders($conn, $user_username, $artist_username, $current_exe_date);
    hx_debug(HX::BUY_SHARES, "Found ".$res->num_rows." older sell orders");
    while($row = $res->fetch_assoc())
    {
        $will_execute = false;
        hx_debug(HX::SELL_SHARES, "execution check on sell order ".$row['id'].": selling_price = ".$row['selling_price'].", market_price = ".$market_price);
        if($row['selling_price'] != -1 && $row['selling_price'] == $market_price)
        {
            $will_execute = true;
            hx_debug(HX::SELL_SHARES, "Will execute on sell order id ".$row['id']);
        }
        else
        {
            hx_debug(HX::SELL_SHARES, "Will not execute on sell order id ".$row['id']);
        }
        if($request_quantity <= 0)
        {
            break;
        }
        if($will_execute)
        {
            hx_debug(HX::BUY_SHARES, "current_exe_date: ".$current_exe_date.", sell order's date_posted: ".$row['date_posted']."\n".
                                  "Found matching market price sell order with id ".$row['id']."\n".
                                  "------------------------------------");
    
            $transact = autoPurchaseInit($conn, $user_username, $row['user_username'], $artist_username);

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
                hx_debug(HX::BUY_SHARES, "Case request_quantity >= row['no_of_share'] in executeMarketPriceSellOrders\n".
                                        "Match check on order id: ".$row['id']);
                doTransaction($connPDO,
                            $transact,
                            $market_price,
                            $market_price,
                            $row['selling_price'],
                            $row['no_of_share'],
                            $row,
                            $buy_mode,
                            ShareInteraction::BUY);

                hx_info(HX::BUY_SHARES, "Auto purchasing sell order id ".$row['id'].", amount $".($row['no_of_share'] * $row['selling_price'])." was transfered between buyer ".$user_username." and seller ".$row['user_username']);
                removeSellOrder($conn, $row['id']);
            }
            else
            {
                hx_debug(HX::BUY_SHARES, "Case request_quantity < row['no_of_share'] in executeMarketPriceSellOrders\n".
                                        "Match check on order id: ".$row['id']);
                doTransaction($connPDO,
                            $transact,
                            $market_price,
                            $market_price,
                            $row['selling_price'],
                            $request_quantity,
                            $row,
                            $buy_mode,
                            ShareInteraction::BUY);
                            
                hx_info(HX::BUY_SHARES, "Auto purchasing sell order id ".$row['id'].", amount $".($request_quantity * $row['selling_price'])." was transfered between buyer ".$user_username." and seller ".$row['user_username']);
            }

            $request_quantity -= $row['no_of_share'];
        }
    }

    return $request_quantity;
}

/**
* Goes through all sell orders that have limit <= market price or stop >= market price and execute them with market price buy orders (if found any)
*
* @param  	conn	                    connection to db
*
* @param  	connPDO	                    transaction-locked connection to db
*
* @param  	artist_username	            artist username whose shares are being requested from
*
* @param  	market_price                current market price
*
*/
function checkForExecutableSellOrders($conn, $connPDO, $artist_username, $market_price)
{
    $res = searchMarketExeLimitStopSellOrders($conn, $artist_username, $market_price);
    while($row = $res->fetch_assoc())
    {
        hx_debug(HX::SELL_SHARES, "Executing sell order id ".$row['id']);
        $selling_quantity = $row['no_of_share'];

        $selling_quantity = executeMarketPriceBuyOrders($conn,
                                                        $connPDO,
                                                        $row['user_username'],
                                                        $row['artist_username'],
                                                        $row['no_of_share'],
                                                        $row['date_posted'],
                                                        $market_price,
                                                        $row['is_from_injection']);
        if($selling_quantity <= 0)
        {
            removeSellOrder($conn, $row['id']);
        }
        else
        {
            updateSellOrderNoOfShare($connPDO, $row['id'], $selling_quantity);
            //Exit the loop since this sell order has sold to all market price buy orders and still have some left
            //Meaning the following sell orders won't have anything to sell
            break;
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
* Automatically executes buy orders that have limit set  
* Matching candidates will be:
* - Sell orders that are selling at market price and the current market price is lower than the limit
* - Sell orders that have sell limit set and sell limit <= buy limit 
* Note: after an execution of a matching limit sell order, the stock price will become that limit value. 
* Therefore, before we load up the next sell order, we need to go back and execute any market price orders that was older than the current executing sell order. 
* Special case: if the requesting quantity is less than the first sell order with limit set that we encounter, the stock price will still change to the new limit value, 
* hence, that sell order would need to go and find any market-price buy orders that are older than the date_posted of this sell order and execute them.
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
*/
function autoPurchaseLimitSet($user_username, $artist_username, $request_quantity, $buy_limit, $current_market_price)
{
    $conn = connect();
    $connPDO = connectPDO();
    $buy_mode = ShareInteraction::NONE;
    $include_market_orders = false;

    if($buy_limit >= $current_market_price)
    {
        $include_market_orders = true;
    }

    $res = searchMatchingSellOrderLimit($conn, $user_username, $artist_username, $buy_limit, $current_market_price, $include_market_orders);
    while($row = $res->fetch_assoc())
    {
        if($request_quantity <= 0)
        {
            break;
        }

        hx_debug(HX::BUY_SHARES, "request_quantity: ".$request_quantity.", row['no_of_share']: ".$row['no_of_share']."\n");

        //Purchasing price always favors the seller in the case of limit set, except for the case when a sell order is at market price
        $purchase_price = $row['sell_limit'];
        $new_pps = $row['sell_limit'];
        //This check will always fail if $include_market_orders is false
        if($row['selling_price'] != -1 && $row['sell_limit'] == -1 && $row['sell_stop'] == -1)
        {
            //Case of a market price sell order
            $new_pps = $row['selling_price'];
            $purchase_price = $row['selling_price'];
        }

        $transact = autoPurchaseInit($conn, $user_username, $row['user_username'], $artist_username);

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

            hx_info(HX::SELL_SHARES, "Auto purchasing sell order id ".$row['id'].", amount $".($row['no_of_share'] * $purchase_price)." was transfered between buyer ".$user_username." and seller ".$row['user_username']);

            doTransaction($connPDO, 
                          $transact, 
                          $current_market_price, 
                          $row['sell_limit'], 
                          $purchase_price, 
                          $row['no_of_share'], 
                          $row, 
                          $buy_mode,
                          ShareInteraction::BUY);

            //Remove since all the shares have been sold at this point
            removeSellOrder($conn, $row['id']);

            //Updates the stock price after execution
            $current_market_price = $new_pps;
            $current_quantity = $request_quantity - $row['no_of_share'];

            if($current_quantity > 0)
            {
                $request_quantity = executeMarketPriceSellOrders($conn, 
                                                                 $connPDO, 
                                                                 $user_username, 
                                                                 $artist_username, 
                                                                 $current_quantity, 
                                                                 $row['date_posted'], 
                                                                 $new_pps);
            }
            else
            {
                //exit the loop
                $request_quantity = $current_quantity;
            }
        }
        else
        {
            hx_debug(HX::BUY_SHARES, "Case request_quantity < row['no_of_share']\n".
                                     "Match check on order id: ".$row['id']."\n");

            hx_info(HX::SELL_SHARES, "Auto purchasing sell order id ".$row['id'].", amount $".($row['no_of_share'] * $purchase_price)." was transfered between buyer ".$user_username." and seller ".$row['user_username']);

            $execute_quantity = $request_quantity;

            doTransaction($connPDO,
                          $transact,
                          $current_market_price,
                          $row['sell_limit'],
                          $purchase_price,
                          $execute_quantity,
                          $row,
                          $buy_mode,
                          ShareInteraction::BUY);

            //Updates the stock price after execution
            $current_market_price = $new_pps;
            //This has already been update in the function doTransaction through purchaseAskedPriceShare, but we do this to skip having to renew the query
            $current_sell_order_quantity = $row['no_of_share'] - $request_quantity;
            $new_sell_order_quantity = executeMarketPriceBuyOrders($conn,
                                                                   $connPDO,
                                                                   $row['user_username'],
                                                                   $artist_username,
                                                                   $current_sell_order_quantity,
                                                                   $row['date_posted'],
                                                                   $new_pps,
                                                                   $row['is_from_injection']);
            if($new_sell_order_quantity <= 0)
            {
                removeSellOrder($conn, $row['id']);
            }
            else
            {
                updateSellOrderNoOfShare($connPDO, $row['id'], $new_sell_order_quantity);
            }
            //do this so we can exit the loop
            $request_quantity = $request_quantity - $row['no_of_share'];
        }
    }
    checkForExecutableSellOrders($conn, $connPDO, $artist_username, $current_market_price);
    closeCon($conn);
}

/**
* Automatically executes buy orders that have stop set  
* Matching candidates will be:
* - Sell orders that are selling at market price and the current market price is >= the stop
* - Sell orders that have sell stop set and sell stop >= buy stop 
* Note: after an execution of a matching stop sell order, the stock price will become the buy order's stop value. 
* Therefore, before we load up the next sell order, we need to go back and execute any market price orders that was older than the current executing sell order. 
* Special case: if the requesting quantity is less than the first sell order with limit set that we encounter, the stock price will still change to the buy stop value, 
* hence, that sell order would need to go and find any market-price buy orders that are older than the date_posted of this sell order and execute them.
*
* @param  	user_username	            username of the buyer who is posting the buy order
*
* @param  	artist_username	            artist username whose shares are being requested from
*
* @param  	request_quantity            amount of shares the buyer is requesting
*
* @param  	buy_stop                    stop of the buy order
*
* @param  	current_market_price	    current stock price of the artist's stock
*
*/
function autoPurchaseStopSet($user_username, $artist_username, $request_quantity, $buy_stop, $current_market_price)
{
    $conn = connect();
    $connPDO = connectPDO();
    $buy_mode = ShareInteraction::NONE;
    $include_market_orders = false;

    if($buy_stop <= $current_market_price)
    {
        $include_market_orders = true;
    }

    $res = searchMatchingSellOrderStop($conn, $user_username, $artist_username, $buy_stop, $current_market_price, $include_market_orders);
    while($row = $res->fetch_assoc())
    {
        if($request_quantity <= 0)
        {
            break;
        }

        hx_debug(HX::BUY_SHARES, "request_quantity: ".$request_quantity.", row['no_of_share']: ".$row['no_of_share']."\n");

        //Purchasing price always favors the buyer in the case of stop set, except for the case when a sell order is at market price
        $purchase_price = $buy_stop;
        $new_pps = $buy_stop;
        //This check will always fail if $include_market_orders is false
        if($row['selling_price'] != -1 && $row['sell_limit'] == -1 && $row['sell_stop'] == -1)
        {
            //Case of a market price sell order
            $new_pps = $row['selling_price'];
            $purchase_price = $row['selling_price'];
        }
        $transact = autoPurchaseInit($conn, $user_username, $row['user_username'], $artist_username);

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
            hx_info(HX::SELL_SHARES, "Auto purchasing sell order id ".$row['id'].", amount $".($row['no_of_share'] * $purchase_price)." was transfered between buyer ".$user_username." and seller ".$row['user_username']);

            doTransaction($connPDO,
                          $transact,
                          $current_market_price,
                          $new_pps,
                          $purchase_price,
                          $row['no_of_share'],
                          $row,
                          $buy_mode,
                          ShareInteraction::BUY);

            //Remove since all the shares have been sold at this point
            removeSellOrder($conn, $row['id']);

            $current_market_price = $new_pps;
            $current_quantity = $request_quantity - $row['no_of_share'];
            if($current_quantity > 0)
            {
                $request_quantity = executeMarketPriceSellOrders($conn, 
                                                                 $connPDO, 
                                                                 $user_username, 
                                                                 $artist_username, 
                                                                 $current_quantity, 
                                                                 $row['date_posted'], 
                                                                 $new_pps);
            }
            else
            {
                //exit the loop
                $request_quantity = $current_quantity;
            }
        }
        else
        {
            hx_debug(HX::BUY_SHARES, "Case request_quantity < row['no_of_share']\n".
            "Match check on order id: ".$row['id']."\n");
            hx_info(HX::SELL_SHARES, "Auto purchasing sell order id ".$row['id'].", amount $".($row['no_of_share'] * $purchase_price)." was transfered between buyer ".$user_username." and seller ".$row['user_username']);

            doTransaction($connPDO,
                          $transact,
                          $current_market_price,
                          $new_pps,
                          $purchase_price,
                          $request_quantity,
                          $row,
                          $buy_mode,
                          ShareInteraction::BUY);

            //Updates the stock price after execution
            $current_market_price = $new_pps;
            //This has already been update in the function doTransaction through purchaseAskedPriceShare, but we do this to skip having to renew the query
            $current_sell_order_quantity = $row['no_of_share'] - $request_quantity;
            $new_sell_order_quantity = executeMarketPriceBuyOrders($conn,
                                                                   $connPDO,
                                                                   $row['user_username'],
                                                                   $artist_username,
                                                                   $current_sell_order_quantity,
                                                                   $row['date_posted'],
                                                                   $new_pps,
                                                                   $row['is_from_injection']);
            if($new_sell_order_quantity <= 0)
            {
                removeSellOrder($conn, $row['id']);
            }
            else
            {
                updateSellOrderNoOfShare($connPDO, $row['id'], $new_sell_order_quantity);
            }

            //do this so we can exit the loop
            $request_quantity = $request_quantity - $row['no_of_share'];
        }
    }

    checkForExecutableSellOrders($conn, $connPDO, $artist_username, $current_market_price);
    closeCon($conn);
}

function autoPurchaseLimitStopSet($user_username, $artist_username, $request_quantity, $buy_limit, $buy_stop, $current_market_price)
{
    $conn = connect();
    $connPDO = connectPDO();
    $buy_mode = ShareInteraction::NONE;
    $include_market_orders = false;

    if($buy_limit >= $current_market_price || $buy_stop <= $current_market_price)
    {
        $include_market_orders = true;
    }

    $res = searchMatchingSellOrderLimitStop($conn, 
                                            $user_username, 
                                            $artist_username, 
                                            $buy_limit, 
                                            $buy_stop, 
                                            $current_market_price, 
                                            $include_market_orders);
                                
    while($row = $res->fetch_assoc())
    {
        $will_execute = false;
        $purchase_price = 0;
        $new_pps = 0;

        if($request_quantity <= 0)
        {
            break;
        }

        hx_debug(HX::BUY_SHARES, "request_quantity: ".$request_quantity.", row['no_of_share']: ".$row['no_of_share']."\n");
        $transact = autoPurchaseInit($conn, $user_username, $row['user_username'], $artist_username);

        if($row['is_from_injection'])
        {
            $buy_mode = ShareInteraction::BUY_FROM_INJECTION;
        }
        else
        {
            $buy_mode = ShareInteraction::BUY;
        }

        //case of market price, shoult only hit this if block if $include_market_orders is true
        if($row['selling_price'] != -1 && $row['sell_limit'] == -1 && $row['sell_stop'] == -1)
        {
            //price and new stock price will be at market price for this case
            $purchase_price = $current_market_price;
            $new_pps = $current_market_price;
            $will_execute = true;
        }
        else if($row['sell_limit'] <= $buy_limit && $row['sell_limit'] != -1)
        {
            $purchase_price = $row['sell_limit'];
            $new_pps = $row['sell_limit'];
            $will_execute = true;
        }
        else if($row['sell_stop'] >= $buy_stop)
        {
            $purchase_price = $buy_stop;
            $new_pps = $buy_stop;
            $will_execute = true;
        }

        if($will_execute)
        {
            if($request_quantity >= $row['no_of_share'])
            {
                hx_debug(HX::BUY_SHARES, "Case request_quantity >= row['no_of_share']\n".
                                        "Match check on order id: ".$row['id']);
                hx_info(HX::BUY_SHARES, "Auto purchasing sell order id ".$row['id'].", amount $".($row['no_of_share'] * $purchase_price)."; transferring between buyer ".$user_username." and seller ".$row['user_username']);

                doTransaction($connPDO,
                            $transact,
                            $current_market_price,
                            $new_pps,
                            $purchase_price,
                            $row['no_of_share'],
                            $row,
                            $buy_mode,
                            ShareInteraction::BUY);

                removeSellOrder($conn, $row['id']);

                //Update new market price
                $current_market_price = $new_pps;
                $current_quantity = $request_quantity - $row['no_of_share'];

                if($current_quantity > 0)
                {
                    hx_debug (HX::BUY_SHARES, "Executing market price sell orders....");
                    $request_quantity = executeMarketPriceSellOrders($conn,
                                                                    $connPDO,
                                                                    $user_username,
                                                                    $artist_username,
                                                                    $current_quantity,
                                                                    $row['date_posted'],
                                                                    $new_pps);
                }
                else
                {
                    $request_quantity = $current_quantity;
                }
            }
            else
            {
                hx_debug(HX::BUY_SHARES, "Case request_quantity < row['no_of_share']\n".
                                        "Match check on order id: ".$row['id']);
                hx_info(HX::BUY_SHARES, "Auto purchasing sell order id ".$row['id'].", amount $".($row['no_of_share'] * $purchase_price).", transfering between buyer ".$user_username." and seller ".$row['user_username']);

                doTransaction($connPDO,
                            $transact,
                            $current_market_price,
                            $new_pps,
                            $purchase_price,
                            $request_quantity,
                            $row,
                            $buy_mode,
                            ShareInteraction::BUY);

                $current_market_price = $new_pps;
                $current_sell_order_quantity = $row['no_of_share'] - $request_quantity;
                hx_debug(HX::BUY_SHARES, "Executing market price buy orders....");
                $new_sell_order_quantity = executeMarketPriceBuyOrders($conn,
                                                                       $connPDO,
                                                                       $row['user_username'],
                                                                       $artist_username,
                                                                       $current_sell_order_quantity,
                                                                       $row['date_posted'],
                                                                       $new_pps,
                                                                       $row['is_from_injection']);

                if($new_sell_order_quantity <= 0)
                {
                    removeSellOrder($conn, $row['id']);
                }
                else
                {
                    updateSellOrderNoOfShare($connPDO, $row['id'], $new_sell_order_quantity);
                }
                $request_quantity = $request_quantity - $row['no_of_share'];
            }
        }
    }
    hx_debug (HX::SELL_SHARES, "Checking for executable sell orders after stock price has changed...");
    checkForExecutableSellOrders($conn, $connPDO, $artist_username, $current_market_price);
    closeCon($conn);
}
?>