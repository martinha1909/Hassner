<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../constants/AccountTypes.php';

    $buy_or_sell = $_POST['buy_sell'];
    if($buy_or_sell == "-Sell your shares")
    {
        if($_SESSION['buy_sell'] != ShareInteraction::SELL)
        {
            $_SESSION['buy_sell'] = ShareInteraction::SELL;
        }
        else
        {
            $_SESSION['buy_sell'] = 0;
        }
    }
    else if($buy_or_sell == "+Buy shares")
    {
        if($_SESSION['buy_sell'] != ShareInteraction::BUY)
        {
            $_SESSION['buy_sell'] = ShareInteraction::BUY;
        }
        else
        {
            $_SESSION['buy_sell'] = 0;
        }
    }
    else if($buy_or_sell == "+Create buy order")
    {
        if($_SESSION['buy_sell'] != "BUY_ORDER")
        {
            $_SESSION['buy_sell'] = "BUY_ORDER";
        }
        else
        {
            $_SESSION['buy_sell'] = 0;
        }
    }

    $_SESSION['dependencies'] = "FRONTEND";

    if($_SESSION['account_type'] == AccountType::User)
    {
        header("Location: ../../frontend/listener/ArtistUserShareInfo.php");
    }
    else if($_SESSION['account_type'] == AccountType::Artist)
    {
        returnToMainPage();
    }
?>