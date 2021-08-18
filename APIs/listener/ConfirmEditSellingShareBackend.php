<?php
    $_SESSION['dependencies'] = 1;
    include '../control/Dependencies.php';

    $conn = connect();

    $new_quantity = $_POST['new_quantity'];
    $new_asked_price = $_POST['new_asked_price'];
    $artist_name = key($_POST['artist_name']);
    $old_asked_price = key($_POST['old_asked_price']);
    $old_quantity = key($_POST['old_quantity']);
    if($new_quantity == 0)
    {
        removeUserArtistSellShareTuple($conn, $_SESSION['username'], $artist_name, $old_asked_price, $old_quantity);
    }
    else
    {
        updateExistedSellingShare($conn, $_SESSION['username'], $artist_name, $new_quantity, $new_asked_price);
    }

    $_SESSION['artist_share_remove'] = 0;
    $_SESSION['share_price_remove'] = 0;
    $_SESSION['dependencies'] = 0;

    closeCon($conn);
    
    header("Location: ../../frontend/listener/listener.php");
?>