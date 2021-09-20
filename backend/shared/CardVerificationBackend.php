<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/dependencies.php';
    include '../constants/LoggingModes.php';

    $_SESSION['logging_mode'] = LogModes::BUY_SILIQAS;

    $conn = connect();
    $save_info = $_POST['save_info'];

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

    # Basic CC verification. This would be done by a payment processor in the future
    if((strlen($card_number) < 14) || (strlen($card_number) > 16))
    {
        $_SESSION['status'] = "EMPTY_ERR";
    }

    # Validation algo from https://web.archive.org/web/20040904082039/http://www.semaphorecorp.com/misc/cc.html
    $mult = 1;
    $sum = 0;
    for($i = strlen($card_number); $i > 0; $i--)
    {
        $char = $card_number[$i];
        $num = (int) $card_number[$i];
        $product = $num * $mult;
        $sum += ($product / 10) + $product % 10;
        $mult = 3 - $mult;
    }

    # Card failed checksum
    if($sum % 10 != 0)
    {
        $_SESSION['status'] = "EMPTY_ERR";
    } 
    else if($save_info == "Yes")
    {
        if(!empty($full_name) && !empty($email) && !empty($address) && !empty($city) && !empty($state) && !empty($zip) && !empty($card_name) && !empty($card_number) && !empty($expmonth) && !empty($expyear) && !empty($cvv))
        {
            $_SESSION['coins'] = round($_SESSION['coins'], 2);
            saveUserPaymentInfo($conn, 
                                $_SESSION['username'], 
                                $full_name, 
                                $email, 
                                $address, 
                                $city, 
                                $state, 
                                $zip, 
                                $card_name, 
                                $card_number);
            $_SESSION['status'] = purchaseSiliqas($conn, $_SESSION['username'], $_SESSION['coins']);
            $_SESSION['btn_show'] = 0;
            $_SESSION['cad'] = 0;
            $_SESSION['coins'] = 0;
            $_SESSION['saved'] = 0; 
            $_SESSION['siliqas'] = 0;
        }
        else
        {
            $_SESSION['status'] = "EMPTY_ERR";
        }
    }
    else
    {
        if(!empty($full_name) && !empty($email) && !empty($address) && !empty($city) && !empty($state) && !empty($zip) && !empty($card_name) && !empty($card_number) && !empty($expmonth) && !empty($expyear) && !empty($cvv))
        {
            $_SESSION['coins'] = round($_SESSION['coins'], 2);
            $_SESSION['status'] = purchaseSiliqas($conn, $_SESSION['username'], $_SESSION['coins']);
            $_SESSION['cad'] = 0;
            $_SESSION['coins'] = 0;
            $_SESSION['siliqas'] = 0;
            $_SESSION['saved'] = 0;
        }
        else
        {
            $_SESSION['status'] = "EMPTY_ERR";
        }
    }

    $_SESSION['dependencies'] = "FRONTEND";

    if($_SESSION['account_type'] == "artist")
    {
        $_SESSION['display'] = 0;
    }
     
    returnToMainPage();
?>