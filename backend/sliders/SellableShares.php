<?php
    header('Content-Type: application/json');
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $min_lim = $_POST['min_lim'];
    $max_lim = $_POST['max_lim'];
    $chosen_min = $_POST['chosen_min'];
    $chosen_max = $_POST['chosen_max'];
    $conn = connect();
    $sellable_shares = 0;
    $total_shares_owned = 0;
    $total_shares_selling = 0;

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
        $shares_selling_stop = 0;
        $matching_shares_requested = 0;

        $res_buy_stop = searchQuantityStopBuyOrders($conn, $_SESSION['username'], $_SESSION['selected_artist'], $chosen_min);
        if($res_buy_stop->num_rows > 0)
        {
            while($row = $res_buy_stop->fetch_assoc())
            {
                $matching_shares_requested += $row['quantity'];
            }
        }

        $res_shares_selling_stop = searchStopOrderSharesSelling($conn, $_SESSION['username'], $_SESSION['selected_artist'], $chosen_min);
        if($res_shares_selling_stop->num_rows > 0)
        {
            while($row = $res_shares_selling_stop->fetch_assoc())
            {
                $shares_selling_stop += $row['no_of_share'];
            }
        }

        $res_shares_owned = searchSharesInArtistShareHolders($conn, $_SESSION['username'], $_SESSION['selected_artist']);
        if($res_shares_owned->num_rows > 0)
        {
            $row = $res_shares_owned->fetch_assoc();
            $total_shares_owned = $row['shares_owned'];
        }

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

        $shares_selling_stop_avai = $total_shares_owned - $total_shares_selling - $shares_selling_stop;

        if($shares_selling_stop_avai > $matching_shares_requested)
        {
            $sellable_shares = $matching_shares_requested;
        }
        else
        {
            $sellable_shares = $shares_selling_stop_avai;
        }
    }
    else if ($chosen_min == $min_lim && $chosen_max < $max_lim)
    {

    }
    else if ($chosen_min > $min_lim && $chosen_max < $max_lim)
    {
        
    }

    

    // print json_encode($sellable_shares);
?>