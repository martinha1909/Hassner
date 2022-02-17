<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $conn = connect();
    $order_id = key($_POST['remove_id']);

    removeBuyOrder($conn, $order_id);

    $_SESSION['dependencies'] == "FRONTEND";
    returnToMainPage();
?>