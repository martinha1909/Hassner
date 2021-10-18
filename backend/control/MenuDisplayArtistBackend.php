<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $type = $_POST['display_type'];

    if(in_array($type, ["Investors", "Ethos", "Siliqas", "Account", "Campaign"]))
    {
        $_SESSION['display'] = strtoupper($type);
    }

    $_SESSION['dependencies'] = "FRONTEND";

    returnToMainPage();
?>