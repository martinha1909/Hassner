<?php
    session_start();
    //seller would have the form of Buy From <seller_name>
    $seller = $_POST['buy_user_selling_price'];
    //splits at the 9th index
    $splitted = str_split($seller, 9);
    //splitted[0] would contain "Buy From"
    //splitted[1] would contain the seller name
    $_SESSION['seller'] = $splitted[1];

    //if else structure to cancel/enable buy asked price from chosen seller
    if($_SESSION['buy_asked_price'] == 0)
    {
        $_SESSION['buy_asked_price'] = 1;
    }
    else if($_SESSION['buy_asked_price'] == 1 && (strcmp($_SESSION['seller_toggle'], $_SESSION['seller']) == 0))
    {
        $_SESSION['buy_asked_price'] = 0;
    }

    //if the user is buying from bid price, disable market buying options
    $_SESSION['buy_market_price'] = 0;

    if($_SESSION['account_type'] == "user")
    {
        header("Location: ../../frontend/listener/ArtistUserShareInfo.php");
    }
    else if($_SESSION['account_type'] == "artist")
    {
        header("Location: ../../frontend/artist/Artist.php");
    }
?>