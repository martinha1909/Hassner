<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../shared/include/MarketplaceHelpers.php';
    include '../constants/StatusCodes.php';

    $conn = connect();
    //selected artist
    $artist_tag = $_POST['artist_tag'];

    $_SESSION['selected_artist'] = getArtistUsernameFromTag($artist_tag);

    if($_SESSION['selected_artist'] != StatusCodes::ErrGeneric)
    {
        $_SESSION['artist_found'] = true;
    }
    else
    {
        $_SESSION['artist_found'] = false;
    }

    echo $_SESSION['selected_artist'];
    
    $_SESSION['dependencies'] = "FRONTEND";

    header("Location: ../../frontend/listener/ArtistUserShareInfo.php");
?>