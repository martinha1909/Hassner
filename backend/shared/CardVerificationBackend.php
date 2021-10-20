<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/dependencies.php';
    include '../constants/LoggingModes.php';
    include '../constants/StatusCodes.php';
    include '../constants/AccountTypes.php';

    $_SESSION['logging_mode'] = LogModes::DEPOSIT;

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
        $_SESSION['status'] = StatusCodes::ErrCard;
    }
    else if($save_info == "Yes")
    {
        if(!empty($full_name) && !empty($email) && !empty($address) && !empty($city) && !empty($state) && !empty($zip) && !empty($card_name) && !empty($card_number) && !empty($expmonth) && !empty($expyear) && !empty($cvv))
        {
            $_SESSION['cad'] = round($_SESSION['cad'], 2);
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
            $_SESSION['status'] = purchaseSiliqas($conn, $_SESSION['username'], $_SESSION['cad']);
            $_SESSION['btn_show'] = 0;
            $_SESSION['cad'] = 0;
            $_SESSION['cad'] = 0;
            $_SESSION['saved'] = 0; 
            $_SESSION['fiat'] = 0;
        }
        else
        {
            $_SESSION['status'] = StatusCodes::ErrEmpty;
        }
    }
    else
    {
        if(!empty($full_name) && !empty($email) && !empty($address) && !empty($city) && !empty($state) && !empty($zip) && !empty($card_name) && !empty($card_number) && !empty($expmonth) && !empty($expyear) && !empty($cvv))
        {
            $_SESSION['cad'] = round($_SESSION['cad'], 2);
            $_SESSION['status'] = purchaseSiliqas($conn, $_SESSION['username'], $_SESSION['cad']);
            $_SESSION['cad'] = 0;
            $_SESSION['cad'] = 0;
            $_SESSION['fiat'] = 0;
            $_SESSION['saved'] = 0;
        }
        else
        {
            $_SESSION['status'] = StatusCodes::ErrCard;
        }
    }

    $_SESSION['dependencies'] = "FRONTEND";

    if($_SESSION['account_type'] == AccountType::Artist)
    {
        $_SESSION['display'] = 0;
    }
     
    returnToMainPage();
?>