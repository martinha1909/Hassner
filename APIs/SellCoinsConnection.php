<?php
    session_start();
    $_SESSION['coins'] = $_POST['coins'];
    if(!empty($_SESSION['coins']) && is_numeric($_SESSION['coins']))
    {
        $_SESSION['cad'] = $_SESSION['coins'] * (1 - $_SESSION['conversion_rate']);
        if($_SESSION['currency'] == "USD")
            $_SESSION['cad'] = $_SESSION['cad'] * 0.83;
        else if($_SESSION['currency'] == "EURO")
            $_SESSION['cad'] = $_SESSION['cad'] * 0.68;
        $_SESSION['btn_show'] = 1;
    }
    header("Location: ../frontend/listener.php");
?>