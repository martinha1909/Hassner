<?php
    session_start();
    include '../logic.php';
    include '../connection.php';
    $conn = connect();
    $save_info = $_POST['save_info'];
    if($save_info == "Yes")
    {
        $full_name = $_POST['firstname'];
        $email = $_POST['email'];
        $address=$_POST['address'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $zip = $_POST['zip'];
        $card_name = $_POST['cardname'];
        $card_number = $_POST['cardnumber'];
        $expmonth = $_POST['expmonth'];
        $expyear = $_POST['expyear'];
        $cvv = $_POST['cvv'];
        if(!empty($full_name) && !empty($email) && !empty($address) && !empty($city) && !empty($state) && !empty($zip) && !empty($card_name) && !empty($card_number) && !empty($expmonth) && !empty($expyear) && !empty($cvv))
        {
            $_SESSION['coins'] = round($_SESSION['coins'], 2);
            saveUserPaymentInfo($conn, $_SESSION['username'], $full_name, $email, $address, $city, $state, $zip, $card_name, $card_number);
            $_SESSION['notify'] = purchaseSiliqas($conn, $_SESSION['username'], $_SESSION['coins']);
            $_SESSION['btn_show'] = 0;
            $_SESSION['cad'] = 0;
            $_SESSION['coins'] = 0;
            $_SESSION['saved'] = 0; 
            $_SESSION['siliqas'] = 0;
            $_SESSION['currency'] = 0;
        }
        else
        {
            $_SESSION['notify'] = 2; 
        }
    }
    else
    {
        $full_name = $_POST['firstname'];
        $email = $_POST['email'];
        $address=$_POST['address'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $zip = $_POST['zip'];
        $card_name = $_POST['cardname'];
        $card_number = $_POST['cardnumber'];
        $expmonth = $_POST['expmonth'];
        $expyear = $_POST['expyear'];
        $cvv = $_POST['cvv'];
        if(!empty($full_name) && !empty($email) && !empty($address) && !empty($city) && !empty($state) && !empty($zip) && !empty($card_name) && !empty($card_number) && !empty($expmonth) && !empty($expyear) && !empty($cvv))
        {
            $_SESSION['coins'] = round($_SESSION['coins'], 2);
            $_SESSION['notify'] = purchaseSiliqas($conn, $_SESSION['username'], $_SESSION['coins']);
            $_SESSION['cad'] = 0;
            $_SESSION['coins'] = 0;
            $_SESSION['siliqas'] = 0;
            $_SESSION['saved'] = 0; 
            $_SESSION['currency'] = 0;
        }
        else
        {
            $_SESSION['notify'] = 2;
        }
    }
    header("Location: ../../frontend/listener/listener.php");
    
?>