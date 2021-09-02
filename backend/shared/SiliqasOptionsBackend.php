<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../../backend/control/Dependencies.php';

    $option = $_POST['options'];
    $option_split = explode(" ", $option);

    //We want to reset the values of these conversion variables everytime we switch operation
    $_SESSION['coins'] = 0;
    $_SESSION['siliqas'] = 0;

    if($option_split[0] == "Siliqas")
    {
        $_SESSION['siliqas_or_fiat'] = "SELL_SILIQAS";
    }
    else
    {
        $_SESSION['siliqas_or_fiat'] = "BUY_SILIQAS";
    }

    $_SESSION['dependencies'] = "FRONTEND";

    returnToMainPage();
?>