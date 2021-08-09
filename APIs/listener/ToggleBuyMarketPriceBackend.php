<?php
    session_start();
    if($_SESSION['buy_market_price'] == 0)
    {
        $_SESSION['buy_market_price'] = 1;
    }
    else if($_SESSION['buy_market_price'] == 1)
    {
        $_SESSION['buy_market_price'] = 0;
    }
    $_SESSION['buy_asked_price'] = 0;
    header("Location: ../../frontend/listener/ArtistUserShareInfo.php");
?>