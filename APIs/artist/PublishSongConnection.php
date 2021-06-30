<?php
    session_start();
    include '../connection.php';
    include '../logic.php';    

    $conn = connect();
    $song_id = key($_POST['song_id']);
    $artist = $_SESSION['username'];
    publishSong($conn, $song_id);

    header("Location: ../../frontend/artist.php");
    closeCon($conn); 


?>