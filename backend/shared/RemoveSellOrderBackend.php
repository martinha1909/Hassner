<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../constants/AccountTypes.php';

    $conn = connect();

    $sell_order_id = key($_POST['remove_id']);

    removeSellOrder($conn, $sell_order_id);

    $_SESSION['dependencies'] == "FRONTEND";
    returnToMainPage();
?>