<?php
    session_start();
    $_SESSION['siliqas'] = $_POST['currency'];
    if(!empty($_SESSION['siliqas']) && is_numeric($_SESSION['siliqas']))
    {
        $_SESSION['coins'] = $_SESSION['siliqas'] * (1 + $_SESSION['conversion_rate']);
        if($_SESSION['currency'] == "USD")
            $_SESSION['coins'] = $_SESSION['coins'] * 1.25;
        else if($_SESSION['currency'] == "EURO")
            $_SESSION['coins'] = $_SESSION['coins'] * 1.47;
        $_SESSION['btn_show'] = 1;
        
    }

    header("Location: ../../frontend/listener/listener.php");
?>