<?php
    session_start();
    if($_SESSION['saved'] == 0)
    {
        $_SESSION['saved'] = 1;
    }
    else if($_SESSION['saved'] == 1)
    {
        $_SESSION['saved'] = 0;
    }
    header("Location: ../../frontend/shared/Sellout.php");
?>