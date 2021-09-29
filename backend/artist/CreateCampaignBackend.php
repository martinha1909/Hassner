<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $conn = connect();

    $offer = $_POST['offer'];
    //Only to be used and populated if artist selects the "Other" option
    $other_offer = "";
    //First index contains date
    //Second index contains time
    $expiration_date = datePickerParser($_POST['campaign_duration']);
    //First index contains date
    //Second index contains time
    $release_date = dayAndTimeSplitter(getCurrentDate("America/Edmonton"));
    $type = $_POST['raffle_or_benchmark'];
    $minimum_ethos = $_POST['minimum_ethos'];

    if($offer == "other")
    {
        $other_offer = $_POST['other_offering'];
        postCampaign($conn, 
                     $_SESSION['username'], 
                     $other_offer, 
                     $release_date[0], 
                     $release_date[1], 
                     $expiration_date[0], 
                     $expiration_date[1],
                     $type,
                     $minimum_ethos);
    }
    else
    {
        postCampaign($conn, 
                     $_SESSION['username'], 
                     $offer, 
                     $release_date[0], 
                     $release_date[1], 
                     $expiration_date[0], 
                     $expiration_date[1],
                     $type,
                     $minimum_ethos);
    }

    $_SESSION['dependencies'] = "FRONTEND";
    returnToMainPage();
?>