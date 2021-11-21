<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../../backend/control/Dependencies.php';
    include '../../backend/constants/BalanceOption.php';

    $option = trim($_POST['options']);
    $msg = $option." has been selected";
    hx_debug(HX::CURRENCY, $msg);


    $_SESSION['fiat_options'] = ($option == BalanceOption::WITHDRAW) ? BalanceOption::WITHDRAW_CAPS : BalanceOption::DEPOSIT_CAPS;

    $_SESSION['dependencies'] = "FRONTEND";

    returnToMainPage();
?>