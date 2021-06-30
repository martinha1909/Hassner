<?php
    session_start();
    include '../logic.php';
    include '../connection.php';
    $conn = connect();
    if($_SESSION['add_share'] == 0)
    {
        $_SESSION['add_share'] = 1;
        header("Location: ../../frontend/artist.php");
    }
    else
    {
        $added_share = $_POST['share_added'];
        increaseArtistDistributedShare($conn, $_SESSION['username'], $added_share);
        $_SESSION['add_share'] = 0;
        header("Location: ../../frontend/artist.php");
    }
    

?>