<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $conn = connect();
    //selected artist
    $unfollowed_artist = key($_POST['unfollow']);

    unFollowArtist($conn, $_SESSION['username'], $unfollowed_artist);
    
    $_SESSION['dependencies'] = "FRONTEND";

    header("Location: ../../frontend/listener/ArtistUserShareInfo.php")
?>