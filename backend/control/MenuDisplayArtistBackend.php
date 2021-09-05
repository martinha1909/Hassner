<?php
    session_start();
    $type = $_POST['display_type'];
    if($type == "Campaign")
    {
        $_SESSION['display'] = "CAMPAIGN";
    }
    else if($type == "Ethos")
    {
        $_SESSION['display'] = "ETHOS";
    }
    else if($type == "Account")
    {
        $_SESSION['display'] = "ACCOUNT";
    }
    else if($type == "Siliqas")
    {
        $_SESSION['display'] = "SILIQAS";
    }
    else if($type == "Artists")
    {
        $_SESSION['display'] = "ARTISTS";
    }
    else if($type == "+")
    {
        $_SESSION['display'] = 3;
    }
    header("Location: ../../frontend/artist/Artist.php");
?>