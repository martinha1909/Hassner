<?php
    session_start();
    include '../logic.php';
    include '../connection.php';

    $conn = connect();
    //selected artist
    $_SESSION['selected_artist'] = $_POST['artist_name'];

    $search_1 = searchSpecificInvestment($conn, $_SESSION['username'], $_SESSION['selected_artist']);
    //number of share that current user has bought from selected artist
    $_SESSION['shares_owned'] = $search_1->fetch_assoc();
    
    $search_2 = searchArtistCurrentPricePerShare($conn, $_SESSION['selected_artist']);
    //current price per share of selected artist
    $_SESSION['current_pps'] = $search_2->fetch_assoc(); 

    $search_3 = searchInitialPriceWhenBought($conn, $_SESSION['username'], $_SESSION['selected_artist']);
    //price per share when this user bought with the selected artist
    $_SESSION['bought_pps'] = $search_3->fetch_assoc();

    //displaying profit in siliqas
    $_SESSION['profit'] = $_SESSION['bought_pps']['price_per_share_when_bought'] - $_SESSION['current_pps']['price_per_share'];
    //displaying profit in %
    $_SESSION['profit_rate'] = ($_SESSION['profit']/$_SESSION['current_pps']['price_per_share']) * 100;

    $search_4 = searchArtistTotalSharesBought($conn, $_SESSION['selected_artist']);
    //total number of shares bought accross all users with the selected artist
    $total_share_bought = $search_4->fetch_assoc();
    $search_5 = searchNumberOfShareDistributed($conn, $_SESSION['selected_artist']);
    //Number of share distributed by the selected artist
    $share_distributed = $search_5->fetch_assoc();
    //shares available for purchase of the selected artist
    $_SESSION['available_shares'] = $share_distributed['Share_Distributed'] - $total_share_bought['Shares'];

    $search_6 = searchAccount($conn, $_SESSION['username']);
    $_SESSION['user_balance'] = $search_6->fetch_assoc();

    header("Location: ../../frontend/listener/ArtistUserShareInfo.php")
?>