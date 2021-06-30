<?php
    session_start();
    include '../logic.php';
    include '../connection.php';
    $conn = connect();
    $album_name = $_POST['album_name'];
    $result = searchAlbumArtist($conn, $_SESSION['username'], $album_name);
    if($result->num_rows <= 0)
    {
        $_SESSION['notify'] = 2;
        header("Location: ../../frontend/artist.php");
    }
    else
    {
        $_SESSION['notify'] = deleteAlbum($conn, $album_name, $_SESSION['username']);
        header ("Location: ../../frontend/artist.php");
    }
?>