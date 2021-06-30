<?php
    session_start();
    include '../logic.php';
    include '../connection.php';
    $conn = connect();
    $song_name = $_POST['song_to_remove'];
    $query = searchSongByName($conn, $song_name);
    $song_id = $query->fetch_assoc();
    removeSongFromAlbum($conn, $_SESSION['album_to_remove'], $song_id['id']);
    $_SESSION['add'] = 0;
    header("Location: ../../frontend/artist.php");
?>