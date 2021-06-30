<?php
    session_start();
    include 'logic.php';
    include 'connection.php';
    $conn = connect();
    $_SESSION['notify'] = 0;
    $result5 = getUserBalance($conn, $_SESSION['username']);
    $balance = $result5->fetch_assoc();
    $share = $_POST['share'];
    if($share > $_SESSION['shares_available'])
    {
        $_SESSION['notify'] = 2;
    }
    else
    {
        $result = searchArtistUserShares($conn, $_SESSION['username'], $_SESSION['artist']);

        if($result->num_rows > 0)
        {
            $row = $result->fetch_assoc();
            $result2 = searchAccount($conn, $_SESSION['artist']);
            $row2 = $result2->fetch_assoc();
            $artist_total_shares = $row2['Shares'] + $share;
            $result3 = searchArtistPricePerShare($conn, $_SESSION['artist']);
            $row3 = $result3->fetch_assoc();
            $new_balance = $balance['balance'] - $row3['price_per_share'];
            $total_shares_bought = $share + $row['no_of_share_bought'];
            $_SESSION['notify'] = increaseSharesBought($conn, $_SESSION['username'], $_SESSION['artist'], $total_shares_bought, $new_balance);
            addSharesToArtist($conn, $_SESSION['artist'], $artist_total_shares);
            increaseArtistPricePerShare($conn, $_SESSION['artist']);
            increaseArtistRate($conn, $_SESSION['artist']);
        }
        else
        {
            $result2 = searchAccount($conn, $_SESSION['artist']);
            if($result2->num_rows > 0)
            {
                $row2 = $result2->fetch_assoc();
                $result3 = searchArtistPricePerShare($conn, $_SESSION['artist']);
                $row3 = $result3->fetch_assoc();
                $new_balance = $balance['balance'] - $row3['price_per_share'];
                $_SESSION['notify'] = addSharesBought($conn, $_SESSION['username'], $_SESSION['artist'], $share, $new_balance);
                $artist_total_shares = $share + $row2['Shares'];
                addSharesToArtist($conn, $_SESSION['artist'], $artist_total_shares);
                increaseArtistPricePerShare($conn, $_SESSION['artist']);
                increaseArtistRate($conn, $_SESSION['artist']);
            }
            else
                $_SESSION['notify'] = 2;
            
        }
    }
    // echo $_SESSION['notify'];
    header("Location: ../frontend/SongPageUser.php");
?>

 