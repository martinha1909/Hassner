<?php
    session_start();
    include 'connection.php';
    include 'logic.php';    
    $conn = connect();
    $_SESSION['artist'] = key($_POST['artist_name']);
    $rate_query = searchArtistRate($conn, $_SESSION['artist']);
    $rate = $rate_query->fetch_assoc();
    $_SESSION['rate'] = $rate['rate'];
    $artist_username = $_SESSION['artist'];
    $user_username = $_SESSION['username'];
    $_SESSION['current_no_of_shares'] = 0;
    $_SESSION['per_share_price'] = 0;

    $result = searchArtistUserShares($conn, $user_username, $artist_username);
    if($result->num_rows > 0)
    {
        $row = $result->fetch_assoc();
        $_SESSION['current_no_of_shares'] += $row['no_of_share_bought'];
    }

    $result = searchArtistPricePerShare($conn, $artist_username);
    if($result->num_rows > 0)
    {
        $row = $result->fetch_assoc();
        $_SESSION['per_share_price'] = $row['price_per_share'];
    }

     
    header("Location: ../frontend/SongPageUser.php");

    closeCon($conn); 

    

?>