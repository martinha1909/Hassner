<?php
    header('Content-Type: application/json');
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../constants/StatusCodes.php';

    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $json_data = 1;
    $artist_balance = getUserBalance($_SESSION['username']);
    $sub_total = $price * $quantity;

    if($sub_total > $artist_balance)
    {
        $json_data = $artist_balance/$price;
    }
    else
    {
        $json_data = $quantity;
    }


    echo json_encode($json_data);
?>