<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

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

    $_SESSION['dependencies'] = "FRONTEND";

    returnToMainPage();
?>