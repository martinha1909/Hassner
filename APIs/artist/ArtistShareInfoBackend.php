<?php
    $_SESSION['dependencies'] = 1;
    include '../control/Dependencies.php';

    $conn = connect();
    //selected artist
    $_SESSION['selected_artist'] = $_POST['artist_name'];

     
    
    $_SESSION['dependencies'] = 0;

    header("Location: ../../frontend/listener/ArtistUserShareInfo.php")
?>