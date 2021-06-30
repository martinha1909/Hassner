<?php
    session_start();
    include 'logic.php';
    include 'connection.php';
    $conn = connect();
    // $cad = $_SESSION['coins'] / (1+ $_SESSION['conversion_rate']);
    $_SESSION['notify'] = withdrawCoins($conn, $_SESSION['username'], $_SESSION['coins']);
    $_SESSION['btn_show'] = 0;
    $_SESSION['cad'] = 0;
    header("Location: ../frontend/listener.php");
?>