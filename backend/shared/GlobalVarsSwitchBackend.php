<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    if($_SESSION['account_type'] == "artist" && $_SESSION['display'] = "ETHOS")
    {
        $_SESSION['share_distribute'] = 1;
    }

    $_SESSION['dependencies'] = "FRONTEND";

    returnToMainPage();
?>