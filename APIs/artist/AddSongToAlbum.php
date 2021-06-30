<?php
    session_start();
    $_SESSION['add'] = 5;
    $_SESSION['album_to_add'] = key($_POST['album_name']);
    header("Location: ../../frontend/artist.php");
?>