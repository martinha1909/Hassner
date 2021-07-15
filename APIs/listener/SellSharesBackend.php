<?php
    session_start();
    include '../logic.php';
    include '../connection.php';

    $conn = connect();
    $quantity = $_POST['purchase_quantity'];
    $asked_price = $_POST['asked_price'];
    $result = getSpecificAskedPrice($conn, $_SESSION['username'], $_SESSION['selected_artist']);
    $existed = 0;
    while($row = $result->fetch_assoc())
    {   
        if((strcmp($row['user_username'], $_SESSION['username']) == 0) && (strcmp($row['artist_username'], $_SESSION['selected_artist']) == 0) && ($row['selling_price'] == $asked_price))
        {
            $existed = 1;
            break;
        }
    }

    if($existed == 0)
    {
        postAskedPrice($conn, $_SESSION['username'], $_SESSION['selected_artist'], $quantity, $asked_price);
    }
    else
    {
        updateAskedPrice($conn, $_SESSION['username'], $_SESSION['selected_artist'], $quantity, $asked_price);
    }
    header("Location: ../../frontend/listener/ArtistUserShareInfo.php");
?>