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
            $msg = "Empty deposit amount cannot be processed for user ".$_SESSION['username'];  
            hx_error(HX::CURRENCY, $msg);

            $_SESSION['dependencies'] = "FRONTEND";

            echo(json_encode(array(
                "logging_mode" => LogModes::DEPOSIT,          
                "status"=> StatusCodes::ErrEmpty,
                "msg"=> "Please fill out all fields and try again"
            )));
        }
        else if(!is_numeric($_SESSION['fiat']))
        {
            $msg = "Non numeric amount cannot be processed for user ".$_SESSION['username'];  
            hx_error(HX::CURRENCY, $msg);

            $_SESSION['dependencies'] = "FRONTEND";

            echo(json_encode(array(
                "logging_mode" => LogModes::DEPOSIT,          
                "status"=> StatusCodes::ErrNum,
                "msg"=> "Amount has to be a number"
            )));
        }
        else
        {
            $_SESSION['usd'] = currenciesToUSD($_SESSION['fiat'], $_SESSION['currency']);

            $msg = "currenciesToUSD returned ".$_SESSION['usd']." USD for user ".$_SESSION['username'];  
            hx_debug(HX::CURRENCY, $msg);

            $_SESSION['dependencies'] = "FRONTEND";

            echo(json_encode(array(
                "logging_mode" => LogModes::DEPOSIT,          
                "status"=> StatusCodes::Success,
            )));
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
            $msg = "Empty withdrawal amount cannot be processed for user ".$_SESSION['username'];  
            hx_error(HX::CURRENCY, $msg);

            echo(json_encode(array(
                "logging_mode" => LogModes::WITHDRAW,          
                "status"=> StatusCodes::ErrEmpty,
                "msg" => "Please fill out all fields and try again"
            )));
        }
        else if(!is_numeric($_SESSION['usd']))
        {
            $msg = "Non-numeric amount cannot be processed for user ".$_SESSION['username'];  
            hx_error(HX::CURRENCY, $msg);

            echo(json_encode(array(
                "logging_mode" => LogModes::WITHDRAW,          
                "status"=> StatusCodes::ErrNum,
                "msg" => "Amount has to be a number"
            )));
        }
        else
        {
            $conn = connect();
            $res = searchAccount($conn, $_SESSION['username']);
            $balance = $res->fetch_assoc();
            if($balance['balance'] < $_SESSION['usd'])
            {
                $msg = "Not enough to withdraw for user ".$_SESSION['username']."(".$balance['balance']."<".$_SESSION['usd'].")";  
                hx_error(HX::CURRENCY, $msg);

                $_SESSION['dependencies'] = "FRONTEND";

                echo(json_encode(array(
                    "logging_mode" => LogModes::WITHDRAW,          
                    "status"=> StatusCodes::ErrNotEnough,
                    "msg" => "Not enough USD"
                )));
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

                echo(json_encode(array(
                    "logging_mode" => LogModes::WITHDRAW,          
                    "status"=> StatusCodes::Success,
                )));
            }
        }
    }
?>