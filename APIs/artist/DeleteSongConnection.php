<?php

    session_start();
    include '../connection.php';
    include '../logic.php';   
    $conn = connect();
    $song_name = $_POST['song_name'];
    $result = searchSongByName($conn, $song_name);
    if($result->num_rows <= 0)
    {
        $_SESSION['notify'] = 2;
        header("Location: ../../frontend/artist.php");
    }
    else
    {
        $song_id = $result->fetch_assoc();
        $_SESSION['notify'] = deleteSong($conn, $song_id['id']);
        header("Location: ../../frontend/artist.php");
    }
?>