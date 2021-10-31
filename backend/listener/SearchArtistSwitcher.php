<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $conn = connect();
    $_SESSION['artist_found'] = FALSE;
    $artist_name = $_POST['artist_search'];

    $result = searchArtist($conn, $artist_name);
    if($result->num_rows > 0)
    {
        $found_artist = $result->fetch_assoc();
        $_SESSION['selected_artist'] = $found_artist['username'];
        $_SESSION['artist_found'] = TRUE;
    }
    else
    {
        $_SESSION['selected_artist'] = $_POST['artist_search'];
    }

    $_SESSION['dependencies'] = "FRONTEND";
     
    header("Location: ../../frontend/listener/ArtistUserShareInfo.php");
?>