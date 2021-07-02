<?php
    session_start();
    if($_SESSION['sort_type'] == 3)
        $_SESSION['sort_type'] = 6;
    else
        $_SESSION['sort_type'] = 3;
    // echo $_SESSION['sort_type'];
    // $_SESSION['display'] = 2;
    header("Location: ../../frontend/listener/Listener.php");
?>