<?php
    session_start();
    $buy_or_sell = $_POST['buy_sell'];
    if($buy_or_sell == "-Sell your shares")
    {
        if($_SESSION['buy_sell'] != "SELL")
        {
            $_SESSION['buy_sell'] = "SELL";
        }
        else
        {
            $_SESSION['buy_sell'] = 0;
        }
    }
    else if($buy_or_sell == "+Buy shares")
    {
        if($_SESSION['buy_sell'] != "BUY")
        {
            $_SESSION['buy_sell'] = "BUY";
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
    header("Location: ../../frontend/listener/ArtistUserShareInfo.php");
?>