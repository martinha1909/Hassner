<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../shared/include/MarketplaceHelpers.php';
    include '../constants/LoggingModes.php';
    include '../constants/StatusCodes.php';
    include '../constants/BalanceOption.php';
    include '../constants/Currency.php';

    if($_SESSION['fiat_options'] == BalanceOption::DEPOSIT_CAPS)
    {
        //Amount of money that user input in
        $_SESSION['fiat'] = $_POST['amount'];
        $msg = "empty message";
        if($_SESSION['currency'] == Currency::CAD)
        {
            $msg = $_SESSION['username']." entered ".$_SESSION['fiat']." CAD for deposit";  
        }
        else if($_SESSION['currency'] == Currency::USD)
        {
            $msg = $_SESSION['username']." entered ".$_SESSION['fiat']." USD for deposit";  
        }
        else if($_SESSION['currency'] == Currency::EUR)
        {
            $msg = $_SESSION['username']." entered ".$_SESSION['fiat']." EUR for deposit";  
        }
        hx_debug(HX::CURRENCY, $msg);

        $_SESSION['logging_mode'] = LogModes::DEPOSIT;
        if(empty($_SESSION['fiat']))
        {
            $_SESSION['status'] = StatusCodes::ErrEmpty;

            $msg = "Empty deposit amount cannot be processed for user ".$_SESSION['username'];  
            hx_error(HX::CURRENCY, $msg);

            $_SESSION['dependencies'] = "FRONTEND";
            returnToMainPage();
        }
        else if(!is_numeric($_SESSION['fiat']))
        {
            $_SESSION['status'] = StatusCodes::ErrNum;

            $msg = "Non numeric amount cannot be processed for user ".$_SESSION['username'];  
            hx_error(HX::CURRENCY, $msg);

            $_SESSION['dependencies'] = "FRONTEND";
            returnToMainPage();
        }
        else
        {
            $_SESSION['usd'] = currenciesToUSD($_SESSION['fiat'], $_SESSION['currency']);

            $msg = "currenciesToUSD returned ".$_SESSION['usd']." USD for user ".$_SESSION['username'];  
            hx_debug(HX::CURRENCY, $msg);

            $_SESSION['dependencies'] = "FRONTEND";
    
            header("Location: ../../frontend/shared/Checkout.php");
        }
    }
    else if($_SESSION['fiat_options'] == BalanceOption::WITHDRAW_CAPS)
    {
        //Amount of money that user input in
        $_SESSION['usd'] = $_POST['amount'];
        $msg = $_SESSION['username']." entered ".$_SESSION['usd']." USD for withdrawal";  
        hx_debug(HX::CURRENCY, $msg);

        $_SESSION['logging_mode'] = LogModes::WITHDRAW;
        if(empty($_SESSION['usd']))
        {
            $_SESSION['status'] = StatusCodes::ErrEmpty;

            $msg = "Empty withdrawal amount cannot be processed for user ".$_SESSION['username'];  
            hx_error(HX::CURRENCY, $msg);

            returnToMainPage();
        }
        else if(!is_numeric($_SESSION['fiat']))
        {
            $_SESSION['status'] = StatusCodes::ErrNum;

            $msg = "Non-numeric amount cannot be processed for user ".$_SESSION['username'];  
            hx_error(HX::CURRENCY, $msg);

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
                $msg = "Not enough to withdraw for user ".$_SESSION['username']."(".$balance['balance']."<".$_SESSION['usd'].")";  
                hx_error(HX::CURRENCY, $msg);

                returnToMainPage();
            }
            else
            {
                $_SESSION['fiat'] = USDToCurrencies($_SESSION['usd'], $_SESSION['currency']);
                $msg = "empty message";
                if($_SESSION['currency'] == Currency::CAD)
                {
                    $msg = "USDToCurrencies returned ".$_SESSION['fiat']." CAD for user ".$_SESSION['username'];
                }
                else if($_SESSION['currency'] == Currency::USD)
                {
                    $msg = "USDToCurrencies returned ".$_SESSION['fiat']." USD for user ".$_SESSION['username'];  
                }
                else if($_SESSION['currency'] == Currency::EUR)
                {
                    $msg = "USDToCurrencies returned ".$_SESSION['fiat']." EUR for user ".$_SESSION['username'];  
                }
                hx_debug(HX::CURRENCY, $msg);

                $_SESSION['dependencies'] = "FRONTEND";

                header("Location: ../../frontend/shared/Sellout.php");
            }
        }
    }
?>