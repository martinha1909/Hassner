<?php
    header('Content-Type: application/json');
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../constants/ShareInteraction.php';

    $json_array = array();

    $user_event = $_POST['user_event'];
    $num_of_shares_bought = $_POST['num_of_shares'];
    $chosen_min = $_POST['chosen_min'];
    $chosen_max = $_POST['chosen_max'];
    $min_lim = $_POST['min_lim'];
    $max_lim = $_POST['max_lim'];
    $market_price = $_POST['market_price'];

    // echo $chosen_min." ".$chosen_max;
    array_push($json_array, $user_event, $num_of_shares_bought, $chosen_min, $chosen_max, $min_lim, $max_lim, $market_price);

    if($user_event == ShareInteraction::BUY)
    {
        $balance = getUserBalance($_SESSION['username']);
        
    }
    else if($user_event == ShareInteraction::SELL)
    {

    }

    // print json_encode($json_array);
?>