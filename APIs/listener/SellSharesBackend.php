<?php
    session_start();
    include '../logic.php';
    include '../connection.php';

    $conn = connect();
    $quantity = $_POST['purchase_quantity'];
    $asked_price = $_POST['asked_price'];
    $result = getSpecificAskedPrice($conn, $_SESSION['username'], $_SESSION['selected_artist']);
    $existed = 0;

    //queries to see if the user has already been selling this share of the same artist or not
    while($row = $result->fetch_assoc())
    {   
        if((strcmp($row['user_username'], $_SESSION['username']) == 0) && (strcmp($row['artist_username'], $_SESSION['selected_artist']) == 0) && ($row['selling_price'] == $asked_price))
        {
            $existed = 1;
            break;
        }
    }

    //If the user has not been selling the same share, post a new order to sell
    if($existed == 0)
    {
        $new_pps = $_SESSION['current_pps']['price_per_share'];
        for($i = 0; $i<$quantity; $i++)
        {
            //for now each time a share is sold its value is decreased by 5%
            $new_pps*=0.95;
        }
        postAskedPrice($conn, $_SESSION['username'], $_SESSION['selected_artist'], $quantity, $asked_price, $new_pps);
    }

    //If the user has already been selling the same share, simply just adjust the quantity to the new requested quantity
    else
    {
        $new_pps = $_SESSION['current_pps']['price_per_share'];
        for($i = 0; $i<$quantity; $i++)
        {
            //for now each time a share is sold its value is decreased by 5%
            $new_pps*=0.95;
        }
        updateAskedPrice($conn, $_SESSION['username'], $_SESSION['selected_artist'], $quantity, $asked_price, $new_pps);
    }
    header("Location: ../../frontend/listener/ArtistUserShareInfo.php");
?>