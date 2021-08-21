<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $conn = connect();

    $_SESSION['current_date'] = getCurrentDate('America/Edmonton');
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
        //if they are not changing the asked price, adjust the quantity of the same tuple in the database
        if($old_asked_price == $new_asked_price)
        {
            adjustExistedAskedPriceQuantity($conn, $_SESSION['username'], $artist_name, $new_asked_price, $new_quantity);
        }
        else
        {
            //If the user adjusts the asked price to the same as another asked price that already existed in the database
            //just increase the amount of that tuple in the database
            $res = searchUserSellingShares($conn, $_SESSION['username']);
            $counter = 0;
            while($row = $res->fetch_assoc())
            {
                if($row['selling_price'] == $new_asked_price)
                {
                    $new_quantity += $row['no_of_share'];
                    adjustExistedAskedPriceQuantity($conn, $_SESSION['username'], $artist_name, $new_asked_price, $new_quantity);
                    removeUserArtistSellShareTuple($conn, $_SESSION['username'], $artist_name, $old_asked_price, $old_quantity);
                    $counter = 1;
                    break;
                }
            }
            if($counter == 0)
            {
                insertUserArtistSellShareTuple($conn, $_SESSION['username'], $artist_name, $new_quantity, $new_asked_price);
                removeUserArtistSellShareTuple($conn, $_SESSION['username'], $artist_name, $old_asked_price, $old_quantity);
            }
        }
    }

    $_SESSION['artist_share_remove'] = 0;
    $_SESSION['share_price_remove'] = 0;
    $_SESSION['dependencies'] = "FRONTEND";

     
    
    header("Location: ../../frontend/listener/listener.php");
?>