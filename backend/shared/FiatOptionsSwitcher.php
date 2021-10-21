<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../../backend/control/Dependencies.php';

    $option = trim($_POST['options']);


    $_SESSION['fiat_options'] = ($option == "Withdraw") ? "WITHDRAW":"DEPOSIT";

    // if($option == "Withdraw")
    // {
    //     $_SESSION['fiat_options'] = "WITHDRAW";
    // }
    // else
    // {
    //     $_SESSION['fiat_options'] = "DEPOSIT";
    // }

    $_SESSION['dependencies'] = "FRONTEND";

    returnToMainPage();
?>