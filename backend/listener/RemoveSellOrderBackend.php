<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $conn = connect();

    $sell_order_id = $_POST['remove_id'];

    removeSellOrder($conn, $sell_order_id);

    $_SESSION['dependencies'] == "FRONTEND";
    header("Location: ../../frontend/listener/listener.php");
?>