<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../shared/MarketplaceHelpers.php';
    include '../constants/LoggingModes.php';
    include '../constants/StatusCodes.php';

    //Amount of money that user input in
    $_SESSION['fiat'] = $_POST['currency'];

    if($_SESSION['fiat_options'] == "DEPOSIT")
    {
        $_SESSION['logging_mode'] = LogModes::DEPOSIT;
        if(empty($_SESSION['fiat']))
        {
            $_SESSION['status'] = StatusCodes::ErrEmpty;
            returnToMainPage();
        }
        else if(!is_numeric($_SESSION['fiat']))
        {
            $_SESSION['status'] = StatusCodes::ErrNum;
            returnToMainPage();
        }

        $_SESSION['cad'] = currenciesToCAD($_SESSION['fiat'], $_SESSION['currency']);
    }
    else if($_SESSION['fiat_options'] == "WITHDRAW")
    {
        if(!empty($_SESSION['fiat']) && is_numeric($_SESSION['fiat']))
        {
            $conn = connect();
            $res = searchAccount($conn, $_SESSION['username']);
            $balance = $res->fetch_assoc();
            if($balance['balance'] < $_SESSION['fiat'])
            {
                $_SESSION['status'] = StatusCodes::ErrNotEnough;
            }
            else
            {
                // $_SESSION['cad'] = siliqasToFiat($_SESSION['fiat'], $_SESSION['conversion_rate'], $_SESSION['currency']);
                $_SESSION['btn_show'] = 1;
            }
        }
        else
        {
            $_SESSION['status'] = StatusCodes::ErrEmpty;
        }
    }

    $_SESSION['dependencies'] = "FRONTEND";

    header("Location: ../../frontend/shared/Checkout.php");
?>