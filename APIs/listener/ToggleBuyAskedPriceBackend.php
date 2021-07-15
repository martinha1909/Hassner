<?php
    session_start();
    $seller = $_POST['buy_user_selling_price'];
    $splitted = str_split($seller, 9);
    $_SESSION['seller'] = $splitted[1];
    echo $_SESSION['seller'];
    if($_SESSION['buy_asked_price'] == 0)
    {
        $_SESSION['buy_asked_price'] = 1;
    }
    else if($_SESSION['buy_asked_price'] == 1 && (strcmp($_SESSION['seller_toggle'], $_SESSION['seller']) == 0))
    {
        $_SESSION['buy_asked_price'] = 0;
    }
    $_SESSION['buy_market_price'] = 0;
    header("Location: ../../frontend/listener/ArtistUserShareInfo.php");
?>