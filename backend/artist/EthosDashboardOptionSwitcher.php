<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../../backend/control/Dependencies.php';
    include '../../backend/constants/EthosOption.php';

    $option = $_POST['ethos_options'];

    if(in_array($option, [EthosOption::QUOTES, EthosOption::BUY_BACK_SHARES, EthosOption::HISTORY]))
    {
        $_SESSION['ethos_dashboard_options'] = $option;
    }

    $_SESSION['dependencies'] = "FRONTEND";

    returnToMainPage();
?>