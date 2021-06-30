<?php
    session_start();
    include '../connection.php';
    include '../logic.php';    

    $conn = connect();
    $album = key($_POST['album_name']);
    // echo $album;
    publishAlbum($conn, $album);

    header("Location: ../../frontend/artist.php");
    closeCon($conn); 


?>