<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    //same as CheckConversionBackend.php but the multiplicative values are inversed
    $_SESSION['siliqas'] = $_POST['currency'];
    if(!empty($_SESSION['siliqas']) && is_numeric($_SESSION['siliqas']))
    {
        $_SESSION['coins'] = $_SESSION['siliqas'] * (1 + $_SESSION['conversion_rate']);
        if($_SESSION['currency'] == "USD")
            $_SESSION['coins'] = $_SESSION['coins'] * 0.8;
        else if($_SESSION['currency'] == "EURO")
            $_SESSION['coins'] = $_SESSION['coins'] * 0.68027;
        $_SESSION['btn_show'] = 1;
        
    }

    $_SESSION['dependencies'] = "FRONTEND";

    returnToMainPage();
?>