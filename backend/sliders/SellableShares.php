<?php
    header('Content-Type: application/json');
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../shared/include/MarketplaceHelpers.php';

    $min_lim = $_POST['min_lim'];
    $max_lim = $_POST['max_lim'];
    $chosen_min = $_POST['chosen_min'];
    $chosen_max = $_POST['chosen_max'];
    $conn = connect();
    $sellable_shares = 0;
    $total_shares_owned = 0;
    $total_shares_selling = 0;
    $current_pps = getArtistPricePerShare($_SESSION['selected_artist']);

    if($chosen_min == $min_lim && $chosen_max == $max_lim)
    {
        $res = searchSharesInArtistShareHolders($conn, $_SESSION['username'], $_SESSION['selected_artist']);
        if($res->num_rows > 0)
        {
            $row = $res->fetch_assoc();
            $total_shares_owned = $row['shares_owned'];
        }
    
        if($total_shares_owned > 0)
        {
            $res_2 = searchSharesSelling($conn, $_SESSION['username'], $_SESSION['selected_artist']);
            if($res_2 -> num_rows > 0)
            {
                while($row = $res_2->fetch_assoc())
                {
                    $total_shares_selling += $row['no_of_share'];
                }
            }
    
            $sellable_shares = $total_shares_owned - $total_shares_selling;
        }
    }
    else if ($chosen_min > $min_lim && $chosen_max == $max_lim)
    {
        $matching_shares_requested = 0;

        //These are the shares from buyers that have their stop match with this sell order's stop
        $res_buy_stop = searchQuantityStopBuyOrders($conn, $_SESSION['username'], $_SESSION['selected_artist'], $chosen_min);
        if($res_buy_stop->num_rows > 0)
        {
            while($row = $res_buy_stop->fetch_assoc())
            {
                $matching_shares_requested += $row['quantity'];
            }
        }

        if($chosen_min >= $current_pps)
        {
            $res = searchQuantityNoLimitStopBuyOrders($conn, $_SESSION['username'], $_SESSION['selected_artist'], $current_pps);
            while($row = $res->fetch_assoc())
            {
                $matching_shares_requested += $row['quantity'];
            }
        }

        //Total amount of shares the user has towards this current artist
        $res_shares_owned = searchSharesInArtistShareHolders($conn, $_SESSION['username'], $_SESSION['selected_artist']);
        if($res_shares_owned->num_rows > 0)
        {
            $row = $res_shares_owned->fetch_assoc();
            $total_shares_owned = $row['shares_owned'];
        }

        //If the user owns some shares, check to see how many of them he/she is selling, limit and stop doesn't matter
        if($total_shares_owned > 0)
        {
            $res_total_shares_selling = searchSharesSelling($conn, $_SESSION['username'], $_SESSION['selected_artist']);
            if($res_total_shares_selling -> num_rows > 0)
            {
                while($row = $res_total_shares_selling->fetch_assoc())
                {
                    $total_shares_selling += $row['no_of_share'];
                }
            }
        }

        //Total number of shares available to sell
        $shares_selling_stop_avai = $total_shares_owned - $total_shares_selling;

        //We do not let the user create a sell order at this chosen stop unless there is at least 1 matching buy order at this stop
        if($matching_shares_requested > 0)
        {
            if($shares_selling_stop_avai > $matching_shares_requested)
            {
                $sellable_shares = $matching_shares_requested;
            }
            else
            {
                $sellable_shares = $shares_selling_stop_avai;
            }
        }
    }
    else if ($chosen_min == $min_lim && $chosen_max < $max_lim)
    {
        $matching_shares_requested = 0;

        //These are the shares from buyers that have their limit match with this sell order's limit
        $res_buy_limit = searchQuantityLimitBuyOrders($conn, $_SESSION['username'], $_SESSION['selected_artist'], $chosen_max);
        if($res_buy_limit->num_rows > 0)
        {
            while($row = $res_buy_limit->fetch_assoc())
            {
                $matching_shares_requested += $row['quantity'];
            }
        }

        if($chosen_max <= $current_pps)
        {
            $res = searchQuantityNoLimitStopBuyOrders($conn, $_SESSION['username'], $_SESSION['selected_artist'], $current_pps);
            while($row = $res->fetch_assoc())
            {
                $matching_shares_requested += $row['quantity'];
            }
        }

        //Total amount of shares the user has towards this current artist
        $res_shares_owned = searchSharesInArtistShareHolders($conn, $_SESSION['username'], $_SESSION['selected_artist']);
        if($res_shares_owned->num_rows > 0)
        {
            $row = $res_shares_owned->fetch_assoc();
            $total_shares_owned = $row['shares_owned'];
        }

        //If the user owns some shares, check to see how many of them he/she is selling, limit and stop doesn't matter
        if($total_shares_owned > 0)
        {
            $res_total_shares_selling = searchSharesSelling($conn, $_SESSION['username'], $_SESSION['selected_artist']);
            if($res_total_shares_selling -> num_rows > 0)
            {
                while($row = $res_total_shares_selling->fetch_assoc())
                {
                    $total_shares_selling += $row['no_of_share'];
                }
            }
        }

        //Total number of shares available to sell
        $shares_selling_limit_avai = $total_shares_owned - $total_shares_selling;

        //We do not let the user create a sell order at this chosen stop unless there is at least 1 matching buy order at this stop
        if($matching_shares_requested > 0)
        {
            if($shares_selling_limit_avai > $matching_shares_requested)
            {
                $sellable_shares = $matching_shares_requested;
            }
            else
            {
                $sellable_shares = $shares_selling_limit_avai;
            }
        }
    }
    else if ($chosen_min > $min_lim && $chosen_max < $max_lim)
    {
        $matching_shares_requested = 0;

        $res_array_size = searchMaxIDBuyOrdersNotFromUser($conn, $_SESSION['username'], $_SESSION['selected_artist']);
        $max_arr_size = $res_array_size->fetch_assoc();
        //Using a hashmap for quicker lookup
        $already_matched_orders = array_fill(0, $max_arr_size['max_buy_order_id'] + 1, false);

        //These are the shares from buyers that have their limit match with this sell order's limit
        $res_buy_limit = searchQuantityLimitBuyOrders($conn, $_SESSION['username'], $_SESSION['selected_artist'], $chosen_max);
        if($res_buy_limit->num_rows > 0)
        {
            while($row = $res_buy_limit->fetch_assoc())
            {
                //We don't want to take into consideration of the same order twice
                if(!$already_matched_orders[$row['id']])
                {
                    $matching_shares_requested += $row['quantity'];
                }
                $already_matched_orders[$row['id']] = true;
            }
        }

        //These are the shares from buyers that have their stop match with this sell order's stop
        $res_buy_stop = searchQuantityStopBuyOrders($conn, $_SESSION['username'], $_SESSION['selected_artist'], $chosen_min);
        if($res_buy_stop->num_rows > 0)
        {
            while($row = $res_buy_stop->fetch_assoc())
            {
                //We don't want to take into consideration of the same order twice
                if(!$already_matched_orders[$row['id']])
                {
                    $matching_shares_requested += $row['quantity'];
                }
                $already_matched_orders[$row['id']] = true;
            }
        }

        if($chosen_min > $current_pps || $chosen_min < $current_pps)
        {
            $res = searchQuantityNoLimitStopBuyOrders($conn, $_SESSION['username'], $_SESSION['selected_artist'], $current_pps);
            while($row = $res->fetch_assoc())
            {
                //We don't want to take into consideration of the same order twice
                if(!$already_matched_orders[$row['id']])
                {
                    $matching_shares_requested += $row['quantity'];
                }
                $already_matched_orders[$row['id']] = true;
            }
        }

        //Total amount of shares the user has towards this current artist
        $res_shares_owned = searchSharesInArtistShareHolders($conn, $_SESSION['username'], $_SESSION['selected_artist']);
        if($res_shares_owned->num_rows > 0)
        {
            $row = $res_shares_owned->fetch_assoc();
            $total_shares_owned = $row['shares_owned'];
        }

        //If the user owns some shares, check to see how many of them he/she is selling, limit and stop doesn't matter
        if($total_shares_owned > 0)
        {
            $res_total_shares_selling = searchSharesSelling($conn, $_SESSION['username'], $_SESSION['selected_artist']);
            if($res_total_shares_selling -> num_rows > 0)
            {
                while($row = $res_total_shares_selling->fetch_assoc())
                {
                    $total_shares_selling += $row['no_of_share'];
                }
            }
        }

        //Total number of shares available to sell
        $shares_selling_avai = $total_shares_owned - $total_shares_selling;

        //We do not let the user create a sell order at this chosen stop unless there is at least 1 matching buy order at this stop
        if($matching_shares_requested > 0)
        {
            if($shares_selling_avai > $matching_shares_requested)
            {
                $sellable_shares = $matching_shares_requested;
            }
            else
            {
                $sellable_shares = $shares_selling_avai;
            }
        }
    }

    print json_encode($sellable_shares);
?>