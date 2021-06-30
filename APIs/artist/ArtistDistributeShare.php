<?php
    session_start();
    include '../logic.php';
    include '../connection.php';
    $conn = connect();
    $share_distributed = $_POST['distribute_share'];
    $price_per_share = $_POST['price_per_share'];
    if(!is_numeric($share_distributed) && !is_numeric($price_per_share))
    {
        $_SESSION['notify'] = 2;
    }
    else
    {
        setArtistDistributedShare($conn, $share_distributed, $price_per_share, $_SESSION['username']);
        setOriginalValues($conn, $share_distributed, $price_per_share, $_SESSION['username']);
    }
    header("Location: ../../frontend/artist/ArtistPage.php");
?>