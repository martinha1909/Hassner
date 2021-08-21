<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    
    if($_SESSION['edit'] == 3)
    {
        $_SESSION['edit'] = 0;
    }
    else if($_SESSION['edit'] == 0)
    {
        $_SESSION['edit'] = 3;
    }

    $_SESSION['dependencies'] = "FRONTEND";
    header("Location: ../../frontend/artist/PersonalPage.php");
?>