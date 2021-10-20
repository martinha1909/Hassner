<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../shared/MarketplaceHelpers.php';
    include '../constants/LoggingModes.php';
    include '../constants/StatusCodes.php';

    if($_SESSION['fiat_options'] == "DEPOSIT")
    {
        //Amount of money that user input in
        $_SESSION['fiat'] = $_POST['currency'];
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
        $_SESSION['dependencies'] = "FRONTEND";

        header("Location: ../../frontend/shared/Checkout.php");
    }
    else if($_SESSION['fiat_options'] == "WITHDRAW")
    {
        //Amount of money that user input in
        $_SESSION['cad'] = $_POST['currency'];
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
        else
        {
            $conn = connect();
            $res = searchAccount($conn, $_SESSION['username']);
            $balance = $res->fetch_assoc();
            if($balance['balance'] < $_SESSION['fiat'])
            {
                $_SESSION['status'] = StatusCodes::ErrNotEnough;
                returnToMainPage();
            }
            else
            {
                $_SESSION['fiat'] = CADToCurrencies($_SESSION['cad'], $_SESSION['currency']);
            }
        }

        $_SESSION['dependencies'] = "FRONTEND";

        header("Location: ../../frontend/shared/Sellout.php");
    }
?>