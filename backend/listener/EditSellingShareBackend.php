<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $conn = connect();
    $artist_name = $_POST['remove_artist_name'];
    $asked_price = $_POST['remove_share_price'];
    $quantity = $_POST['remove_share_quantity'];

    removeUserArtistSellShareTuple($conn, $_SESSION['username'], $artist_name, $asked_price, $quantity);

    $_SESSION['dependencies'] == "FRONTEND";
    header("Location: ../../frontend/listener/listener.php");
?>