<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $conn = connect();
    //selected artist
    $_SESSION['selected_artist'] = $_POST['artist_name'];

    //Always set found to true in this file since user has already clicked on the artist name, which means it exists
    $_SESSION['artist_found'] = TRUE;
    
    $_SESSION['dependencies'] = "FRONTEND";

    header("Location: ../../frontend/listener/ArtistUserShareInfo.php")
?>