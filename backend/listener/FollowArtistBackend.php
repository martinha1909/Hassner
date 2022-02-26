<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $conn = connect();
    //selected artist
    $followed_artist = key($_POST['follow']);

    followArtist($conn, $_SESSION['username'], $followed_artist);
    
    $_SESSION['dependencies'] = "FRONTEND";

    header("Location: ../../frontend/listener/EthosPage.php")
?>