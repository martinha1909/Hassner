<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    //Amount of money that user input in
    $_SESSION['siliqas'] = $_POST['currency'];

    if($_SESSION['siliqas_or_fiat'] == "BUY_SILIQAS")
    {
        $_SESSION['logging_mode'] = "BUY_SILIQAS";
        if(!empty($_SESSION['siliqas']) && is_numeric($_SESSION['siliqas']))
        {
            //amount of siliqas that will be converted into
            $_SESSION['coins'] = convertToSiliqas($_SESSION['siliqas'], $_SESSION['conversion_rate'], $_SESSION['currency']);
            $_SESSION['btn_show'] = 1;
        }
        else
        {
            $_SESSION['status'] = "EMPTY_ERR";
        }
    }
    else if($_SESSION['siliqas_or_fiat'] == "SELL_SILIQAS")
    {
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
                $_SESSION['coins'] = siliqasToFiat($_SESSION['siliqas'], $_SESSION['conversion_rate'], $_SESSION['currency']);
                $_SESSION['btn_show'] = 1;
            }
        }
        else
        {
            $_SESSION['status'] = "EMPTY_ERR";
        }
    }

    $_SESSION['dependencies'] = "FRONTEND";

    returnToMainPage();
?>