<?php
    header('Content-Type: application/json');
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../shared/include/MarketplaceHelpers.php';
    include '../constants/ShareInteraction.php';
    include '../constants/StatusCodes.php';
    include '../constants/MenuOption.php';

    date_default_timezone_set(Timezone::MST);

    $json_response = StatusCodes::NONE;
    $purchase_price = 0;
    $selling_price = 0;
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
            $conn = connect();
            $connPDO = connectPDO();

            if($chosen_min == $min_lim && $chosen_max == $max_lim)
            {
                $purchase_price = $latest_market_price;
                $new_quantity = autoPurchase($conn, 
                                             $_SESSION['username'], 
                                             $_SESSION['selected_artist'], 
                                             $quantity, 
                                             $purchase_price);

                refreshSellOrderTable();

                if($new_quantity > 0)
                {
                    postBuyOrder($conn, 
                                 $_SESSION['username'],
                                 $_SESSION['selected_artist'], 
                                 $new_quantity, 
                                 $purchase_price, 
                                 $current_date);
                }

                refreshBuyOrderTable();
                $_SESSION['display'] = MenuOption::Portfolio;
                $_SESSION['dependencies'] = "FRONTEND";
                $json_response = StatusCodes::Success;
            }
            else if ($chosen_min > $min_lim && $chosen_max == $max_lim)
            {
                $purchase_price = $chosen_min;
                //TODO: Code to handle when limit is set
            }
            else if ($chosen_min == $min_lim && $chosen_max < $max_lim)
            {
                $purchase_price = $chosen_max;
                //TODO: Code to handle when stop is set
            }
            else if ($chosen_min > $min_lim && $chosen_max < $max_lim)
            {
                $purchase_price_limit = $chosen_min;
                $purchase_price_stop = $chosen_max;
                //TODO: Code to handle when both limit and stop are set
            }

            closeCon($conn);
        }
        else if($user_event == ShareInteraction::SELL)
        {
            $conn = connect();
            $connPDO = connectPDO();

            if($chosen_min == $min_lim && $chosen_max == $max_lim)
            {
                $selling_price = $latest_market_price;
                $new_quantity = autoSell($_SESSION['username'], 
                                         $_SESSION['selected_artist'], 
                                         $selling_price, 
                                         $quantity,
                                         $current_date,
                                         false);

                refreshSellOrderTable();

                if($new_quantity > 0)
                {
                    postSellOrder($conn, 
                                  $_SESSION['username'],
                                  $_SESSION['selected_artist'], 
                                  $new_quantity, 
                                  $selling_price,
                                  false, 
                                  $current_date);
                }

                refreshBuyOrderTable();
                $_SESSION['display'] = MenuOption::Portfolio;
                $_SESSION['dependencies'] = "FRONTEND";
                $json_response = StatusCodes::Success;
            }
            else if ($chosen_min > $min_lim && $chosen_max == $max_lim)
            {
                $purchase_price = $chosen_min;
                //TODO: Code to handle when limit is set
            }
            else if ($chosen_min == $min_lim && $chosen_max < $max_lim)
            {
                $purchase_price = $chosen_max;
                //TODO: Code to handle when stop is set
            }
            else if ($chosen_min > $min_lim && $chosen_max < $max_lim)
            {
                $purchase_price_limit = $chosen_min;
                $purchase_price_stop = $chosen_max;
                //TODO: Code to handle when both limit and stop are set
            }

            closeCon($conn);
        }
    }

    print json_encode($json_response);
?>