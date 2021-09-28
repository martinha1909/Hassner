<?php
    include '../constants/Campaign.php';
    include '../constants/CampaignTypes.php';

    session_start();
    $experience_option = $_POST['experience_options'];
    if($experience_option == "tickets")
    {
        array_push($_SESSION['campaign_data'], $experience_option);
    }
    else if($experience_option == "backstage")
    {
        array_push($_SESSION['campaign_data'], $experience_option);
    }
    else if($experience_option == "other")
    {
        $other_option = $_POST['custom'];
        array_push($_SESSION['campaign_data'], $other_option);
    }

    header("Location: ../../frontend/artist/CreateCampaign.php");
?>