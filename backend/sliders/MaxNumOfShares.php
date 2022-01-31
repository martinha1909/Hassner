<?php
    header('Content-Type: application/json');
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../constants/StatusCodes.php';
    include '../shared/include/MarketplaceHelpers.php';

    $min_lim = $_POST['min_lim'];
    $max_lim = $_POST['max_lim'];
    $chosen_min = $_POST['chosen_min'];
    $chosen_max = $_POST['chosen_max'];
    //By default max num of shares that can be purchased is 1
    $json_data = 1;
    $user_balance = getUserBalance($_SESSION['username']);
    $artist_pps = getArtistPricePerShare($_SESSION['selected_artist']);
    $artist_share_distributed = getArtistShareDistributed($_SESSION['selected_artist']);
    $num_of_shares_invested = getShareInvestedInArtist($_SESSION['username'], $_SESSION['selected_artist']);
    //User should be able to create for a buy order up to the max number of share distributed - total shares bought by him
    $num_of_available_shares = $artist_share_distributed - $num_of_shares_invested;

    if($chosen_min == $min_lim && $chosen_max == $max_lim)
    {
        //maximum amount of shares user can buy at current price per share with current amount of balance
        $max_amount_can_purchase = $user_balance/$artist_pps;

        //If the maximum amount that user can buy is greater than the amount of available shares, the slider
        //will be capped at the number of available shares
        if($max_amount_can_purchase >= $num_of_available_shares)
        {
            $json_data = $num_of_available_shares;
        }
        //otherwise the slider will be capped at the masimum number of shares the user can afford
        else
        {
            $json_data = $max_amount_can_purchase;
        }
    }
    else if ($chosen_min > $min_lim && $chosen_max == $max_lim)
    {
        $conn = connect();
        $all_available_shares = 0;
        $matching_shares_sold = 0;
        $num_of_shares_market_price = 0;

        $res_sell_limit = searchNumOfSharesLimitSellOrders($conn, $_SESSION['username'], $_SESSION['selected_artist'], $chosen_min);
        if($res_sell_limit->num_rows > 0)
        {
            while($row = $res_sell_limit->fetch_assoc())
            {
                $matching_shares_sold += $row['no_of_share'];
            }
        }

        if($chosen_min >= $artist_pps)
        {
            $res_market_price = searchNumOfSharesNoLimitStopSellOrders($conn, $_SESSION['username'], $_SESSION['selected_artist'], $artist_pps);
            if($res_market_price->num_rows > 0)
            {
                while($row = $res_market_price->fetch_assoc())
                {
                    $num_of_shares_market_price += $row['no_of_share'];
                }
            }
        }

        $max_amount_can_purchase = $user_balance/$chosen_min;
        $all_available_shares = $matching_shares_sold + $num_of_shares_market_price;
            

        if($max_amount_can_purchase < $all_available_shares)
        {
            $json_data = $max_amount_can_purchase;
        }
        else
        {
            $json_data = $all_available_shares;
        }
    }
    else if ($chosen_min == $min_lim && $chosen_max < $max_lim)
    {
        $conn = connect();
        $all_available_shares = 0;
        $matching_shares_sold = 0;
        $num_of_shares_market_price = 0;

        $res_sell_stop = searchNumOfSharesStopSellOrders($conn, $_SESSION['username'], $_SESSION['selected_artist'], $chosen_min);
        if($res_sell_stop->num_rows > 0)
        {
            while($row = $res_sell_stop->fetch_assoc())
            {
                $matching_shares_sold += $row['no_of_share'];
            }
        }

        if($chosen_max <= $artist_pps)
        {
            $res_market_price = searchNumOfSharesNoLimitStopSellOrders($conn, $_SESSION['username'], $_SESSION['selected_artist'], $artist_pps);
            if($res_market_price->num_rows > 0)
            {
                while($row = $res_market_price->fetch_assoc())
                {
                    $num_of_shares_market_price += $row['no_of_share'];
                }
            }
        }

        $max_amount_can_purchase = $user_balance/$chosen_max;
        $all_available_shares = $matching_shares_sold + $num_of_shares_market_price;
            

        if($max_amount_can_purchase < $all_available_shares)
        {
            $json_data = $max_amount_can_purchase;
        }
        else
        {
            $json_data = $all_available_shares;
        }
    }
    else if ($chosen_min > $min_lim && $chosen_max < $max_lim)
    {
        $conn = connect();
        $all_available_shares = 0;
        $matching_shares_sold = 0;
        $num_of_shares_market_price = 0;

        $res_sell_limit = searchNumOfSharesLimitSellOrders($conn, $_SESSION['username'], $_SESSION['selected_artist'], $chosen_min);
        if($res_sell_limit->num_rows > 0)
        {
            while($row = $res_sell_limit->fetch_assoc())
            {
                $matching_shares_sold += $row['no_of_share'];
            }
        }

        $res_sell_stop = searchNumOfSharesStopSellOrders($conn, $_SESSION['username'], $_SESSION['selected_artist'], $chosen_max);
        if($res_sell_stop->num_rows > 0)
        {
            while($row = $res_sell_stop->fetch_assoc())
            {
                $matching_shares_sold += $row['no_of_share'];
            }
        }

        if($chosen_max <= $artist_pps || $chosen_min >= $artist_pps)
        {
            $res_market_price = searchNumOfSharesNoLimitStopSellOrders($conn, $_SESSION['username'], $_SESSION['selected_artist'], $artist_pps);
            if($res_market_price->num_rows > 0)
            {
                while($row = $res_market_price->fetch_assoc())
                {
                    $num_of_shares_market_price += $row['no_of_share'];
                }
            }
        }

        $max_amount_can_purchase = $user_balance/$chosen_max;
        $all_available_shares = $matching_shares_sold + $num_of_shares_market_price;
            
        if($max_amount_can_purchase < $all_available_shares)
        {
            $json_data = $max_amount_can_purchase;
        }
        else
        {
            $json_data = $all_available_shares;
        }
    }

    //Casting to int so it rounds down in case json_data is a float
    print json_encode((int)$json_data);

?>