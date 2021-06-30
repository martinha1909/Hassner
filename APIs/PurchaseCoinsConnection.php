<?php
    session_start();
    include 'logic.php';
    include 'connection.php';
    $conn = connect();
    // $cad = $_SESSION['coins'] / (1+ $_SESSION['conversion_rate']);
    $card_number = $_POST['card_number'];
    $brand = $_POST['brand'];
    $expiry_date = $_POST['expiry_date'];
    $Card_holder = $_POST['Card_holder'];
    $cvv = $_POST['cvv'];
    if($card_number == 123456789 && $cvv == 111)
    {
        $_SESSION['notify'] = purchaseCoins($conn, $_SESSION['username'], $_SESSION['coins']);
        $_SESSION['cad'] = 0;
        $_SESSION['coins'] = 0;
    }
    else
    {
        $_SESSION['notify'] = 2;
    }

    // echo $_SESSION['notify'];
    header("Location: ../frontend/listener.php");
?>