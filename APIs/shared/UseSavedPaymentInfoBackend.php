<?php
    session_start();
    $_SESSION['saved'] = 1;
    if($_SESSION['account_type'] == "user")
    {
        header("Location: ../../frontend/listener/Checkout.php");
    }
    else if($_SESSION['account_type'] == "artist")
    {
        header("Location: ../../frontend/artist/Checkout.php");
    }
?>