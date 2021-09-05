<?php
    session_start();
    $type = $_POST['display_type'];
    if($type == "Artists")
    {
        $_SESSION['display'] = "ARTISTS";
    }
    else if($type == "Portfolio")
    {
        $_SESSION['display'] = "PORTFOLIO";
    }
    else if($type == "Siliqas")
    {
        $_SESSION['display'] = "SILIQAS";
    }
    else if($type == "Account")
    {
        $_SESSION['display'] = "ACCOUNT";
    }
    else if($type == "Campaign")
    {
        $_SESSION['display'] = "CAMPAIGN";
    }
    header("Location: ../../frontend/listener/Listener.php");
?>