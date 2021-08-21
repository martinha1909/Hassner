<?php
    $_SESSION['dependencies'] = 1;
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
        //If the user adjusts the asked price to the same as another asked price that already existed in the database
        //just increase the amount of that tuple in the database
        $res = searchUserSellingShares($conn, $_SESSION['username']);
        $counter = 0;
        while($row = $res->fetch_assoc())
        {
            if($row['selling_price'] == $new_asked_price)
            {
                $new_quantity += $row['no_of_share'];
                $counter = 1;
                break;
            }
        }
        if($counter == 0)
        {
            echo "efonwef";
            updateExistedSellingShare($conn, $_SESSION['username'], $artist_name, $new_quantity, $new_asked_price, $old_asked_price, $old_quantity);
        }
    }

    $_SESSION['artist_share_remove'] = 0;
    $_SESSION['share_price_remove'] = 0;
    $_SESSION['dependencies'] = 0;

     
    
    // header("Location: ../../frontend/listener/listener.php");
?>