<?php
    header('Content-Type: application/json');
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../shared/include/MarketplaceHelpers.php';
    include '../shared/include/StockTradeHelpers.php';
    include '../shared/include/CampaignHelpers.php';
    include '../constants/ShareInteraction.php';
    include '../constants/StatusCodes.php';
    include '../constants/MenuOption.php';
    include '../object/SellOrder.php';
    include '../object/AutoTransact.php';

    date_default_timezone_set(Timezone::MST);

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

    print json_encode($json_response);
?>