<?php
    header('Content-Type: application/json');
    $_SESSION['dependencies'] = "BACKEND";

    $num_of_shares_bought = $_POST['num_of_shares'];
    $chosen_min = $_POST['chosen_min'];
    $chosen_max = $_POST['chosen_max'];
    $min_lim = $_POST['min_lim'];
    $max_lim = $_POST['max_lim'];
    $market_price = $_POST['market_price'];
?>