<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $conn = connect();

    $additional_shares = $_POST['share_distributing'];
    $comment = $_POST['inject_comment'];
    date_default_timezone_set($_SESSION['timezone']);
    $current_date = date('Y-m-d H:i:s');

    if(empty($comment))
    {
        //empty check and makes sure that it is properly instanitiated 
        $comment = "";
    }

    $res = searchNumberOfShareDistributed($conn, $_SESSION['username']);
    $share_distributed = $res->fetch_assoc();

    $new_shares_distributed = $share_distributed['Share_Distributed'] + $additional_shares;

    $res_2 = searchArtistCurrentPricePerShare($conn, $_SESSION['username']);
    $current_pps = $res_2->fetch_assoc();

    updateShareDistributed($conn, 
                           $_SESSION['username'], 
                           $new_shares_distributed, 
                           $additional_shares, 
                           $comment, 
                           $current_date);

    $_SESSION['share_distribute'] = 0;
    $_SESSION['dependencies'] = "FRONTEND";
    
    returnToMainPage();
?>