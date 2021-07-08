<<<<<<< HEAD
<?php
// same as CheckConversionBackend.php but the multiplicative values are inversed
    session_start();
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

    header("Location: ../../frontend/listener/listener.php");
=======
<?php
// same as CheckConversionBackend.php but the multiplicative values are inversed
    session_start();
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

    header("Location: ../../frontend/listener/listener.php");
>>>>>>> 63b7abbbeeccfa4ad61ebb37b8e51de44957a2a0
?>