<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $_SESSION['logging_mode'] = "SELL_SILIQAS";
    //same as CheckConversionBackend.php but the multiplicative values are inversed
    $_SESSION['siliqas'] = $_POST['currency'];
    if(!empty($_SESSION['siliqas']) && is_numeric($_SESSION['siliqas']))
    {
        $conn = connect();
        $res = searchAccount($conn, $_SESSION['username']);
        $balance = $res->fetch_assoc();
        if($balance['balance'] < $_SESSION['siliqas'])
        {
            $_SESSION['status'] = "NOT_ENOUGH_ERR";
        }
        else
        {
            $_SESSION['coins'] = $_SESSION['siliqas'] * (1 + $_SESSION['conversion_rate']);
            if($_SESSION['currency'] == "USD")
                $_SESSION['coins'] = $_SESSION['coins'] * 0.8;
            else if($_SESSION['currency'] == "EURO")
                $_SESSION['coins'] = $_SESSION['coins'] * 0.68027;
            $_SESSION['btn_show'] = 1;
        }
    }
    else
    {
        $_SESSION['status'] = "EMPTY_ERR";
    }

    $_SESSION['dependencies'] = "FRONTEND";

    returnToMainPage();
?>