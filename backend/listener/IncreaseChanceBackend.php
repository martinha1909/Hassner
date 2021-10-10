<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $conn = connect();
    //selected artist
    $_SESSION['selected_artist'] = key($_POST['artist_name']);

     
    
    $_SESSION['dependencies'] = "FRONTEND";

    header("Location: ../../frontend/listener/ArtistUserShareInfo.php")
?>