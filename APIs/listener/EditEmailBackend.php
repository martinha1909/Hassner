<?php
    session_start();
    if($_SESSION['edit'] == 0)
    {
        $_SESSION['edit'] = 2;
    }
    else if($_SESSION['edit'] == 2)
    {
        $_SESSION['edit'] = 0;
    }
    
    header("Location: ../../frontend/listener/PersonalPage.php");
?>