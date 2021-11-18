<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../shared/include/MarketplaceHelpers.php';
    include '../constants/LoggingModes.php';
    include '../constants/StatusCodes.php';
    include '../constants/BalanceOption.php';

    if($_SESSION['fiat_options'] == BalanceOption::DEPOSIT_CAPS)
    {
        //Amount of money that user input in
        $_SESSION['fiat'] = $_POST['currency'];
        $_SESSION['logging_mode'] = LogModes::DEPOSIT;
        if(empty($_SESSION['fiat']))
        {
            $_SESSION['status'] = StatusCodes::ErrEmpty;

            $msg = "Empty deposit amount cannot be processed for user ".$_SESSION['username'];
            hx_error(ErrorLogType::CURRENCY, $msg);

            $_SESSION['dependencies'] = "FRONTEND";
            returnToMainPage();
        }
        else if(!is_numeric($_SESSION['fiat']))
        {
            $_SESSION['status'] = StatusCodes::ErrNum;

            $_SESSION['dependencies'] = "FRONTEND";
            returnToMainPage();
        }
        else
        {
            $_SESSION['usd'] = currenciesToUSD($_SESSION['fiat'], $_SESSION['currency']);
            $_SESSION['dependencies'] = "FRONTEND";
    
            header("Location: ../../frontend/shared/Checkout.php");
        }
    }
    else if($_SESSION['fiat_options'] == BalanceOption::WITHDRAW_CAPS)
    {
        //Amount of money that user input in
        $_SESSION['usd'] = $_POST['currency'];
        $_SESSION['logging_mode'] = LogModes::WITHDRAW;
        if(empty($_SESSION['usd']))
        {
            $_SESSION['status'] = StatusCodes::ErrEmpty;
            returnToMainPage();
        }
        else if(!is_numeric($_SESSION['fiat']))
        {
            $_SESSION['status'] = StatusCodes::ErrNum;
            returnToMainPage();
        }
        else
        {
            $conn = connect();
            $res = searchAccount($conn, $_SESSION['username']);
            $balance = $res->fetch_assoc();
            if($balance['balance'] < $_SESSION['usd'])
            {
                $_SESSION['status'] = StatusCodes::ErrNotEnough;
                returnToMainPage();
            }
            else
            {
                $_SESSION['fiat'] = USDToCurrencies($_SESSION['usd'], $_SESSION['currency']);
                $_SESSION['dependencies'] = "FRONTEND";

                header("Location: ../../frontend/shared/Sellout.php");
            }
        }
    }
?>