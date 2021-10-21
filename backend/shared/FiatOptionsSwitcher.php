<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../../backend/control/Dependencies.php';
    include '../../backend/constants/BalanceOption.php';

    $option = trim($_POST['options']);


    $_SESSION['fiat_options'] = ($option == BalanceOption::WITHDRAW) ? BalanceOption::WITHDRAW_CAPS : BalanceOption::DEPOSIT_CAPS;

    $_SESSION['dependencies'] = "FRONTEND";

    returnToMainPage();
?>