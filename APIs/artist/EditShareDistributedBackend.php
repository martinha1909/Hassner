<?php
    $_SESSION['dependencies'] = 1;
    include '../control/Dependencies.php';
    
    if($_SESSION['edit'] == 3)
    {
        $_SESSION['edit'] = 0;
    }
    else if($_SESSION['edit'] == 0)
    {
        $_SESSION['edit'] = 3;
    }

    $_SESSION['dependencies'] = 0;
    header("Location: ../../frontend/artist/PersonalPage.php");
?>