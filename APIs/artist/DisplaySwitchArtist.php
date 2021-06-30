<?php
    session_start();
    $type = $_POST['display_type'];
    if($type == "Songs")
        $_SESSION['display'] = 1;
    else if($type == "My Portfolio")
        $_SESSION['display'] = 2;
    else if($type == "Albums/EPs")
        $_SESSION['display'] = 3;
    else if($type == "Post Something")
        $_SESSION['display'] = 4;
    else if($type == "Account")
        $_SESSION['display'] = 5;   
    else if($type == "Settings")
        $_SESSION['display'] = 6; 
    else if($type == "Sell Siliqas")
        $_SESSION['display'] = 7;
    // echo $_SESSION['display'];
    header("Location: ../../frontend/artist.php");
    
?>