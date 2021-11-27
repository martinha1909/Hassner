<?php
    header('Content-Type: application/json');
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../constants/StatusCodes.php';
    include '../listener/include/ListenerHelpers.php';

    $min_lim = $_POST['min_lim'];
    $max_lim = $_POST['max_lim'];
    $chosen_min = $_POST['chosen_min'];
    $chosen_max = $_POST['chosen_max'];
    //By default max num of shares that can be purchased is 1
    $json_data = 1;
    $user_balance = getUserBalance($_SESSION['username']);
    $artist_pps = getArtistPricePerShare($_SESSION['selected_artist']);
    $num_of_available_shares = calculateArtistAvailableShares($_SESSION['selected_artist']);

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
        //maximum amount of shares user can buy at chosen stop with current amount of balance
        $max_amount_can_purchase = $user_balance/$chosen_min;

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
    else if (($chosen_min > $min_lim && $chosen_max < $max_lim) || ($chosen_min == $min_lim && $chosen_max < $max_lim))
    {
        //maximum amount of shares user can buy at chosen stop with current amount of balance
        $max_amount_can_purchase = $user_balance/$chosen_max;

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

    //Casting to int so it rounds down in case json_data is a float
    print json_encode((int)$json_data);

?>