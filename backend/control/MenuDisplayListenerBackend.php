<?php
    session_start();
    $type = $_POST['display_type'];
    if(in_array($type, ["Artists", "Portfolio", "Balance", "Account", "Campaign"]))
    {
        $_SESSION['display'] = strtoupper($type);
    }
    header("Location: ../../frontend/listener/Listener.php");
?>