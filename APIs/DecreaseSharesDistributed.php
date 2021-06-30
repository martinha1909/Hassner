<?php
    session_start();
    include 'logic.php';
    include 'connection.php';
    $conn = connect();
    if($_SESSION['artist_distributed']['Share_Distributed'] > 0)
    {
        decreaseArtistDistributedShare($conn, $_SESSION['username']);
    }
    header("Location: ../frontend/artist.php");

?>