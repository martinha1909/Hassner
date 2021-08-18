<?php
    $_SESSION['dependencies'] = 1;
    include '../control/Dependencies.php';

    $conn = connect();

    $additional_shares = $_POST['share_distributing'];

    $res = searchNumberOfShareDistributed($conn, $_SESSION['username']);
    $share_distributed = $res->fetch_assoc();

    $new_shares_distributed = $share_distributed['Share_Distributed'] + $additional_shares;

    $res_2 = searchArtistCurrentPricePerShare($conn, $_SESSION['username']);
    $current_pps = $res_2->fetch_assoc();
    $new_pps = $current_pps['price_per_share'] * ($additional_shares/$new_shares_distributed);

    $res_3 = getArtistIinitialDeposit($conn, $_SESSION['username']);
    $deposit = $res_3->fetch_assoc();
    $new_lower_bound = $deposit['deposit']/$new_shares_distributed;

    updateShareDistributed($conn, $_SESSION['username'], $new_shares_distributed, $new_pps, $new_lower_bound);

    $_SESSION['edit'] = 0;
    $_SESSION['dependencies'] = 0;
    
    closeCon($conn);

    header("Location: ../../frontend/artist/PersonalPage.php");
?>