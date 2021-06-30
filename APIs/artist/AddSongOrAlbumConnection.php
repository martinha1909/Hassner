<?php
    session_start();
    $_SESSION['add'] = $_POST['add'];
    if($_SESSION['add'] == "+Add song")
        $_SESSION['add'] = 1;
    else if($_SESSION['add'] == "+Add album")
        $_SESSION['add'] = 2;
    else if($_SESSION['add'] == "-Remove song")
        $_SESSION['add'] = 3;
    else if($_SESSION['add'] == "-Remove album")
        $_SESSION['add'] = 4;
    header("Location: ../../frontend/artist.php");
?>