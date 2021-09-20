<?php

    include '../../backend/constants/StatusCodes.php';
    include '../../backend/constants/Currency.php';
    include '../../backend/constants/LoggingModes.php';

    function hassnerInit()
    {   
        $_SESSION['dependencies'] = "FRONTEND";
        $_SESSION['coins'] = 0;
        $_SESSION['display'] = 0;
        $_SESSION['sort_type'] = 0;
        $_SESSION['cad'] = 0;
        $_SESSION['edit'] = 0;
        $_SESSION['currency'] = 0;
        $_SESSION['btn_show'] = 0;
        $_SESSION['saved'] = 0;
        $_SESSION['buy_sell'] = 0;
        $_SESSION['buy_asked_price'] = 0;
        $_SESSION['buy_market_price'] = 0;
        $_SESSION['siliqas_or_fiat'] = 0;
        $_SESSION['share_distribute'] = 0;
        //conversion rate from CAD to Siliqas, 1 CAD = 0.95 Sililqas (brute force for now)
        $_SESSION['conversion_rate'] = -0.05;
        $_SESSION['current_date'] = getCurrentDate('America/Edmonton');
    }

    function getStatusMessage($err_msg, $suc_msg)
    {
        if($_SESSION['status'] == StatusCodes::ErrGeneric)
        {
            echo '<p style="color: red;">'.$err_msg.'</p>';
        }
        else if($_SESSION['status'] == StatusCodes::Success)
        {
            echo '<p style="color: green;">'.$suc_msg.'</p>';
        }

        $_SESSION['status'] = 0;
        $_SESSION['logging_mode'] = LogModes::NONE;
    }

    function getAccount($username)
    {
        $conn = connect();
        $result = searchAccount($conn, $username);
        $account = $result->fetch_assoc();
         
        return $account;
    }
    
    function getUserBalance($user_username)
    {
        $conn = connect();
        $result = searchAccount($conn, $user_username);
        $balance = $result->fetch_assoc();     
        return $balance['balance'];   
    }

    function convertToSiliqas($amount, $conversion_rate, $currency_type)
    {
        $ret = $amount;
        $ret = $amount * (1 + $conversion_rate);
        if($currency_type == Currency::USD)
            $ret *= 1.25;
        else if($currency_type == Currency::EUR)
            $ret *= 1.47;

        return $ret;
    }

    function siliqasToFiat($amount, $conversion_rate, $currency_type)
    {
        $ret = $amount;
        $ret = $amount / (1 + $conversion_rate);
        if($currency_type == Currency::USD)
            $ret /= 1.25;
        else if($currency_type == Currency::EUR)
            $ret /= 1.47;

        return $ret;
    }

    function returnToMainPage()
    {
        if($_SESSION['account_type'] == AccountType::User)
        {
            header("Location: ../../frontend/listener/listener.php");
        }
        else if($_SESSION['account_type'] ==  AccountType::Artist)
        {
            header("Location: ../../frontend/artist/Artist.php");
        }
    }

    function hasEnoughSiliqas($amount_spending, $balance)
    {
        if($balance >= $amount_spending)
            return true;
        
        return false;
    }
?>