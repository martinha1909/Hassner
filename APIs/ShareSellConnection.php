<?php
    session_start();
    include 'connection.php';
    include 'logic.php';

    $conn = connect();
    $selling_share = $_POST['share'];
    $profit = ($_SESSION['per_share_price'] + $_SESSION['profit']) * $selling_share;
    $artist = $_SESSION['artist'];
    $result = searchArtistUserShares($conn, $_SESSION['username'], $artist);
    if($result->num_rows > 0)
    {
        $no_of_shares_bought = $result->fetch_assoc();
        if($no_of_shares_bought['no_of_share_bought'] >= $selling_share)
            $_SESSION['notify'] = sellShares($conn, $_SESSION['username'], $artist, $selling_share, $profit);
        else
            $_SESSION['notify'] = 2;
        header("Location: ../frontend/SongPageUser.php");
    }
    else
    {
        $_SESSION['notify'] = 2;
        header("Location: ../frontend/SongPageUser.php");
    }
    // $_SESSION['notify'] = sellShares($conn, $_SESSION['username'], $_SESSION[''])

?>