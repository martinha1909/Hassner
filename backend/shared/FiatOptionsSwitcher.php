<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../../backend/control/Dependencies.php';

    $option = $_POST['options'];

    if($option == "Withdraw")
    {
        $_SESSION['fiat_options'] = "WITHDRAW";
    }
    else
    {
        $_SESSION['fiat_options'] = "DEPOSIT";
    }

    $_SESSION['dependencies'] = "FRONTEND";

    returnToMainPage();
?>