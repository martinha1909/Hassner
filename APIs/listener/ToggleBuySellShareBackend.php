<?php
    session_start();
    $buy_or_sell = $_POST['buy_sell'];
    if($buy_or_sell == "+Buy more shares")
    {
        $_SESSION['buy_sell'] = "BUY";
    }
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
    header("Location: ../../frontend/listener/ArtistUserShareInfo.php");
?>