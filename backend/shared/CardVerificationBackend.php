<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/dependencies.php';
    include '../constants/LoggingModes.php';
    include '../constants/StatusCodes.php';
    include '../constants/AccountTypes.php';
    include '../constants/BalanceOption.php';

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

    $msg = "Checkout verification data: \n
            Full name: ".$full_name."\n
            Email: ".$email."\n
            Address: ".$address."\n
            City: ".$city."\n
            State: ".$state."\n
            ZIP: ".$zip."\n
            Card name: ".$card_name."\n
            Card number: ".$card_number."\n
            Expiration month: ".$expmonth."\n
            Expiration year: ".$expyear."\n
            CVV: ".$cvv."\n"; 
    hx_debug(HX::CURRENCY, $msg);

    # Basic CC verification. This would be done by a payment processor in the future
    # Comment out for now. TODO: use card verification API
    // if((strlen($card_number) < 14) || (strlen($card_number) > 16))
    // {
    //     $_SESSION['status'] = StatusCodes::ErrCard;
    //     $msg = "Not a valid card for user ".$_SESSION['username'];
    //     hx_error(HX::CURRENCY, $msg);
    // }
    if($save_info == "Yes")
    {
        if(!empty($full_name) && !empty($email) && !empty($address) && !empty($city) && !empty($state) && !empty($zip) && !empty($card_name) && !empty($card_number) && !empty($expmonth) && !empty($expyear) && !empty($cvv))
        {
            $_SESSION['usd'] = round($_SESSION['usd'], 2);
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
            $_SESSION['status'] = deposit($conn, $_SESSION['username'], $_SESSION['usd']);
            if($_SESSION['status'] == StatusCodes::Success)
            {
                $msg = "user ".$_SESSION['username']." just deposited ".$_SESSION['usd']." USD";
                hx_info(HX::CURRENCY, $msg);
            }
            $_SESSION['usd'] = 0;
            $_SESSION['saved'] = 0; 
            $_SESSION['fiat'] = 0;
            $_SESSION['currency'] = 0;
            $_SESSION['fiat_options'] = BalanceOption::NONE;
        }
        else
        {
            $_SESSION['status'] = StatusCodes::ErrEmpty;
            $msg = "One of the payment fields is empty for user ".$_SESSION['username'];
            hx_error(HX::CURRENCY, $msg);
        }
    }
    else
    {
        if(!empty($full_name) && !empty($email) && !empty($address) && !empty($city) && !empty($state) && !empty($zip) && !empty($card_name) && !empty($card_number) && !empty($expmonth) && !empty($expyear) && !empty($cvv))
        {
            $_SESSION['usd'] = round($_SESSION['usd'], 2);
            $_SESSION['status'] = deposit($conn, $_SESSION['username'], $_SESSION['usd']);
            if($_SESSION['status'] == StatusCodes::Success)
            {
                $msg = "user ".$_SESSION['username']." just deposited ".$_SESSION['usd']." USD";
                hx_info(HX::CURRENCY, $msg);
            }
            $_SESSION['usd'] = 0;
            $_SESSION['fiat'] = 0;
            $_SESSION['saved'] = 0;
            $_SESSION['currency'] = 0;
            $_SESSION['fiat_options'] = BalanceOption::NONE;
        }
        else
        {
            $_SESSION['status'] = StatusCodes::ErrCard;
            $msg = "One of the payment fields is empty for user ".$_SESSION['username'];
            hx_error(HX::CURRENCY, $msg);
        }
    }

    $_SESSION['dependencies'] = "FRONTEND";
     
    returnToMainPage();
?>