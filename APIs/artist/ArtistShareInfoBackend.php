<?php
    session_start();
    include '../logic.php';
    include '../connection.php';

    $conn = connect();
    //selected artist
    $_SESSION['selected_artist'] = $_POST['artist_name'];

    header("Location: ../../frontend/listener/ArtistUserShareInfo.php")
?>