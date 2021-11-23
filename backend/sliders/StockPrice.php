<?php
    header('Content-Type: application/json');
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $conn = connect();
    $max_limit = 0;

    $res = searchArtistCurrentPricePerShare($conn, $_SESSION['selected_artist']);
    if($res->num_rows > 0)
    {
        $row = $res->fetch_assoc();
        $max_limit = $row['price_per_share'];
    }

    closeCon($conn);

    print json_encode($max_limit);
?>