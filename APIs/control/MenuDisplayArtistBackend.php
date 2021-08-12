<?php
    session_start();
    $type = $_POST['display_type'];
    if($type == "Your Campaign")
    {
        $_SESSION['display'] = 1;
    }
    else if($type == "My Portfolio")
    {
        $_SESSION['display'] = 2;
    }
    else if($type == "Account")
    {
        $_SESSION['display'] = 3;
    }
    else if($type == "Sell Siliqas")
    {
        $_SESSION['display'] = 4;
    }
    else if($type == "Settings")
    {
        $_SESSION['display'] = 5;
    }
    else if($type == "+")
    {
        $_SESSION['display'] = 3;
    }
    header("Location: ../../frontend/artist/Artist.php");
?>