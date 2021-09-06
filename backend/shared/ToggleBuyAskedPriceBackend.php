<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    //seller would have the form of Buy From <seller_name>
    $sell_id = key($_POST['buy_user_selling_price']);

    //if else structure to cancel/enable buy asked price from chosen seller
    if($_SESSION['buy_asked_price'] == 0)
    {
        $_SESSION['buy_asked_price'] = 1;
    }
    else if($_SESSION['buy_asked_price'] == 1 && (strcmp($_SESSION['seller_toggle'], $sell_id) == 0))
    {
        $_SESSION['buy_asked_price'] = 0;
    }

    //if the user is buying from bid price, disable market buying options
    $_SESSION['buy_market_price'] = 0;
    $_SESSION['dependencies'] = "FRONTEND";

    if($_SESSION['account_type'] == "user")
    {
        header("Location: ../../frontend/listener/ArtistUserShareInfo.php");
    }
    else if($_SESSION['account_type'] == "artist")
    {
        header("Location: ../../frontend/artist/Artist.php");
    }
?>