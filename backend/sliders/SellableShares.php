<?php
    header('Content-Type: application/json');
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $conn = connect();
    $sellable_shares = 0;
    $total_shares_owned = 0;
    $total_shares_selling = 0;

    $res = searchSharesInArtistShareHolders($conn, $_SESSION['username'], $_SESSION['selected_artist']);
    if($res->num_rows > 0)
    {
        $row = $res->fetch_assoc();
        $total_shares_owned = $row['shares_owned'];
    }

    if($total_shares_owned > 0)
    {
        $res_2 = searchSharesSelling($conn, $_SESSION['username'], $_SESSION['selected_artist']);
        if($res_2 -> num_rows > 0)
        {
            while($row = $res_2->fetch_assoc())
            {
                $total_shares_selling += $row['no_of_share'];
            }
        }

        $sellable_shares = $total_shares_owned - $total_shares_selling;
    }

    print json_encode(500);
?>