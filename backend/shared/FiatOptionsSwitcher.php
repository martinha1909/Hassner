<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../../backend/control/Dependencies.php';
    include '../../backend/constants/BalanceOption.php';

    $option = trim($_POST['options']);
    $msg = $option." has been selected";
    hx_debug(HX::CURRENCY, $msg);

    echo(json_encode(array(            
        "fiat_options"=> ($option == BalanceOption::WITHDRAW) ? BalanceOption::WITHDRAW : BalanceOption::DEPOSIT
    )));

    $_SESSION['fiat_options'] = ($option == BalanceOption::WITHDRAW) ? BalanceOption::WITHDRAW : BalanceOption::DEPOSIT;

    $_SESSION['dependencies'] = "FRONTEND";
?>