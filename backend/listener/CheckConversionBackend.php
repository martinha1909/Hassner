<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    
    //Amount of money that user input in
    $_SESSION['siliqas'] = $_POST['currency'];
    echo $_SESSION['siliqas'];
    if(!empty($_SESSION['siliqas']) && is_numeric($_SESSION['siliqas']))
    {
        //amount of siliqas that will be converted into
        $_SESSION['coins'] = convertToSiliqas($_SESSION['siliqas'], $_SESSION['conversion_rate'], $_SESSION['currency']);
        $_SESSION['btn_show'] = 1;
        
    }

    $_SESSION['dependencies'] = "FRONTEND";

    header("Location: ../../frontend/listener/listener.php");
?>