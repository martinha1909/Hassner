<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    //if the user selects the sell order that was prviously selected, simple just close that order
    if($_SESSION['seller'] == key($_POST['buy_user_selling_price']))
    {
        $_SESSION['buy_asked_price'] = 0;
        $_SESSION['seller'] = 0;
    }
    //otherwise switch to the option to buy the newly selected order
    else
    {
        $_SESSION['buy_asked_price'] = 1;
        //seller would have the form of Buy From <seller_name>
        $_SESSION['seller'] = key($_POST['buy_user_selling_price']);
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