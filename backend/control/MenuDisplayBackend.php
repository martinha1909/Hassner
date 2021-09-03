<?php
    session_start();
    $type = $_POST['display_type'];
    if($type == "Top Invested Artists")
    {
        $_SESSION['display'] = 1;
    }
    else if($type == "My Portfolio")
    {
        $_SESSION['display'] = 2;
    }
    else if($type == "Siliqas")
    {
        $_SESSION['display'] = 3;
    }
    else if($type == "Account")
    {
        $_SESSION['display'] = 4;
    }
    else if($type == "Communities")
    {
        $_SESSION['display'] = 5;
    }
    header("Location: ../../frontend/listener/Listener.php");
?>