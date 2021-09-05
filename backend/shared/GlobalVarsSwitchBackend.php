<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    if($_SESSION['share_distribute'] == 0)
    {
        $_SESSION['share_distribute'] = 1;
    }
    else
    {
        $_SESSION['share_distribute'] = 0;
    }

    $_SESSION['dependencies'] = "FRONTEND";

    returnToMainPage();
?>