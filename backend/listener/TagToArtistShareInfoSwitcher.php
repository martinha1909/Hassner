<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $conn = connect();

    //selected ticker
    $artist_ticker = $_POST['artist_ticker'];

    $res = searchArtistByTicker($conn, $artist_ticker);
    $artist_username = $res->fetch_assoc();
    $_SESSION['selected_artist'] = $artist_username['artist_username'];
    
    $_SESSION['artist_found'] = TRUE;

    $_SESSION['dependencies'] = "FRONTEND";

    header("Location: ../../frontend/listener/EthosPage.php")
?>