<?php
    session_start();
    $_SESSION['add'] = 6;
    $_SESSION['album_to_remove'] = key($_POST['album_name']);
    echo $_SESSION['album_to_remove'];
    header("Location: ../../frontend/artist.php");
?>