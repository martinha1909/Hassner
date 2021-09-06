<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $option = $_POST['buy_options'];

    if($option == "Market Price")
    {
        $_SESSION['buy_options'] = "MARKET";
    }
    else if($option == "Bid Price")
    {
        $_SESSION['buy_options'] = "BID";
    }

    $_SESSION['dependencies'] = "FRONTEND";

    header("Location: ../../frontend/listener/ArtistUserShareInfo.php");
?>