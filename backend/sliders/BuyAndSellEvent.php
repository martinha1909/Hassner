<?php
    header('Content-Type: application/json');
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../shared/include/MarketplaceHelpers.php';
    include '../shared/include/StockTradeHelpers.php';
    include '../constants/ShareInteraction.php';
    include '../constants/StatusCodes.php';
    include '../constants/MenuOption.php';
    include '../object/SellOrder.php';
    include '../object/AutoTransact.php';

    date_default_timezone_set(Timezone::MST);
    $_SESSION['lock_count']++;

    $json_response = StatusCodes::NONE;
    $current_date = date('Y-m-d H:i:s');
    $user_event = $_POST['user_event'];
    $quantity = $_POST['num_of_shares'];
    $chosen_min = $_POST['chosen_min'];
    $chosen_max = $_POST['chosen_max'];
    $min_lim = $_POST['min_lim'];
    $max_lim = $_POST['max_lim'];
    $market_price = $_POST['market_price'];
    $latest_market_price = getArtistPricePerShare($_SESSION['selected_artist']);

    //Error checking to see if there is any change between when the user click the buy button vs now 
    if($market_price != $latest_market_price)
    {
        $json_response = StatusCodes::PRICE_OUTDATED;
    }
    else
    {
        //only allow to handle request for the first request
        if($_SESSION['lock_count'] == 0)
        {
            if($user_event == ShareInteraction::BUY)
            {
                $connPDO = connectPDO();

                if($chosen_min == $min_lim && $chosen_max == $max_lim)
                {
                    $purchase_price = $latest_market_price;
                    $new_quantity = autoPurchaseNoLimitStop($_SESSION['username'], 
                                                            $_SESSION['selected_artist'], 
                                                            $quantity, 
                                                            $purchase_price,
                                                            $latest_market_price);

                    refreshSellOrderTable();

                    if($new_quantity > 0)
                    {
                        //User posting buy order without limit and stop
                        postBuyOrder($connPDO, 
                                    $_SESSION['username'],
                                    $_SESSION['selected_artist'], 
                                    $new_quantity, 
                                    $purchase_price, 
                                    -1,
                                    -1,
                                    $current_date);
                    }

                    refreshBuyOrderTable();
                    $_SESSION['display'] = MenuOption::Portfolio;
                    $_SESSION['dependencies'] = "FRONTEND";
                    $json_response = StatusCodes::Success;
                }
                else if ($chosen_min > $min_lim && $chosen_max == $max_lim)
                {
                    autoPurchaseLimitSet($_SESSION['username'],
                                        $_SESSION['selected_artist'],
                                        $quantity,
                                        $chosen_min,
                                        $latest_market_price);
                    refreshBuyOrderTable();
                    refreshSellOrderTable();
                    
                    $_SESSION['display'] = MenuOption::Portfolio;
                    $_SESSION['dependencies'] = "FRONTEND";
                    $json_response = StatusCodes::Success;
                }
                else if ($chosen_min == $min_lim && $chosen_max < $max_lim)
                {
                    autoPurchaseStopSet($_SESSION['username'],
                                        $_SESSION['selected_artist'],
                                        $quantity,
                                        $chosen_max,
                                        $latest_market_price);

                    refreshBuyOrderTable();
                    refreshSellOrderTable();
                    
                    $_SESSION['display'] = MenuOption::Portfolio;
                    $_SESSION['dependencies'] = "FRONTEND";
                    $json_response = StatusCodes::Success;
                }
                else if ($chosen_min > $min_lim && $chosen_max < $max_lim)
                {
                    autoPurchaseLimitStopSet($_SESSION['username'],
                                            $_SESSION['selected_artist'],
                                            $quantity,
                                            $chosen_min,
                                            $chosen_max,
                                            $latest_market_price);

                    refreshBuyOrderTable();
                    refreshSellOrderTable();
                    
                    $_SESSION['display'] = MenuOption::Portfolio;
                    $_SESSION['dependencies'] = "FRONTEND";
                    $json_response = StatusCodes::Success;
                }
            }
            else if($user_event == ShareInteraction::SELL)
            {
                $conn = connect();
                $new_sellable_shares = 0;
                $total_shares_owned = 0;
                $total_shares_selling = 0;
                
                $res_shares_owned = searchSharesInArtistShareHolders($conn, $_SESSION['username'], $_SESSION['selected_artist']);
                if($res_shares_owned->num_rows > 0)
                {
                    $row = $res_shares_owned->fetch_assoc();
                    $total_shares_owned = $row['shares_owned'];
                }

                if($total_shares_owned > 0)
                {
                    $res_shares_selling = searchSharesSelling($conn, $_SESSION['username'], $_SESSION['selected_artist']);
                    if($res_shares_selling -> num_rows > 0)
                    {
                        while($row = $res_shares_selling->fetch_assoc())
                        {
                            $total_shares_selling += $row['no_of_share'];
                        }
                    }

                    $new_sellable_shares = $total_shares_owned - $total_shares_selling;
                }

                //error checking to see by the time the user post to sell some shares, there might have been auto selling actions prior to this point
                if($quantity > $new_sellable_shares)
                {
                    $json_response = StatusCodes::SELLABLE_OUTDATED;
                }
                else
                {
                    $connPDO = connectPDO();

                    if($chosen_min == $min_lim && $chosen_max == $max_lim)
                    {
                        $selling_price = $latest_market_price;
                        $new_quantity = autoSellNoLimitStop($_SESSION['username'], 
                                                            $_SESSION['selected_artist'], 
                                                            $quantity, 
                                                            $selling_price,
                                                            $latest_market_price,
                                                            false);

                        refreshSellOrderTable();

                        if($new_quantity > 0)
                        {
                            //Sell order posted by user with no limit and stop, setting those values to -1
                            postSellOrder($connPDO, 
                                        $_SESSION['username'],
                                        $_SESSION['selected_artist'], 
                                        $new_quantity, 
                                        $selling_price,
                                        -1,
                                        -1,
                                        $current_date,
                                        false);
                        }

                        refreshBuyOrderTable();
                        $_SESSION['display'] = MenuOption::Portfolio;
                        $_SESSION['dependencies'] = "FRONTEND";
                        $json_response = StatusCodes::Success;
                    }
                    else if ($chosen_min > $min_lim && $chosen_max == $max_lim)
                    {
                        $new_quantity = autoSellStopSet($_SESSION['username'],
                                                        $_SESSION['selected_artist'],
                                                        $quantity,
                                                        $chosen_min,
                                                        $latest_market_price);

                        refreshSellOrderTable();
                        if($new_quantity > 0)
                        {
                            //Sell order posted by user with limit set, setting selling_price to -1
                            postSellOrder($connPDO,
                                        $_SESSION['username'],
                                        $_SESSION['selected_artist'],
                                        $new_quantity,
                                        -1,
                                        -1,
                                        $chosen_min,
                                        $current_date,
                                        false);
                        }

                        refreshBuyOrderTable();
                        $_SESSION['display'] = MenuOption::Portfolio;
                        $_SESSION['dependencies'] = "FRONTEND";
                        $json_response = StatusCodes::Success;
                    }
                    else if ($chosen_min == $min_lim && $chosen_max < $max_lim)
                    {
                        $new_quantity = autoSellLimitSet( $_SESSION['username'],
                                                        $_SESSION['selected_artist'],
                                                        $quantity,
                                                        $chosen_max,
                                                        $latest_market_price);
                        refreshSellOrderTable();
                        if($new_quantity > 0)
                        {
                            //Sell order posted by user with limit set, setting selling_price to -1
                            postSellOrder($connPDO,
                                        $_SESSION['username'],
                                        $_SESSION['selected_artist'],
                                        $new_quantity,
                                        -1,
                                        $chosen_max,
                                        -1,
                                        $current_date,
                                        false);
                        }

                        refreshBuyOrderTable();
                        $_SESSION['display'] = MenuOption::Portfolio;
                        $_SESSION['dependencies'] = "FRONTEND";
                        $json_response = StatusCodes::Success;
                    }
                    else if ($chosen_min > $min_lim && $chosen_max < $max_lim)
                    {
                        $new_quantity = autoSellLimitStopSet($_SESSION['username'],
                                                            $_SESSION['selected_artist'],
                                                            $quantity,
                                                            $chosen_max,
                                                            $chosen_min,
                                                            $latest_market_price);
                        refreshSellOrderTable();
                        if($new_quantity > 0)
                        {
                            //Sell order posted by user with limit set, setting selling_price to -1
                            postSellOrder($connPDO,
                                        $_SESSION['username'],
                                        $_SESSION['selected_artist'],
                                        $new_quantity,
                                        -1,
                                        $chosen_max,
                                        $chosen_min,
                                        $current_date,
                                        false);
                        }

                        refreshBuyOrderTable();
                        $_SESSION['display'] = MenuOption::Portfolio;
                        $_SESSION['dependencies'] = "FRONTEND";
                        $json_response = StatusCodes::Success;
                    }
                }
            }
        }
        // Already handling another request made by this user
        else
        {
            $_SESSION['display'] = MenuOption::Portfolio;
            $_SESSION['dependencies'] = "FRONTEND";
            $json_response = StatusCodes::Success;
        }
    }

    print json_encode($json_response);
    $_SESSION['lock_count'] = -1;
?>