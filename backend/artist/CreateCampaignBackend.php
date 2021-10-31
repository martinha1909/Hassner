<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../shared/include/CampaignHelpers.php';
    include '../constants/LoggingModes.php';

    $_SESSION['logging_mode'] = LogModes::CAMPAIGN;

    $conn = connect();

    $offer = $_POST['offer'];
    //Only to be used and populated if artist selects the "Other" option
    $other_offer = "";
    $expiration_date = $_POST['campaign_duration'];
    $type = $_POST['raffle_or_benchmark'];
    $minimum_ethos = $_POST['minimum_ethos'];

    if(empty($offer) || empty($expiration_date) || empty($type) || empty($minimum_ethos))
    {
        $_SESSION['status'] = StatusCodes::CampaignEmpty;
        $_SESSION['dependencies'] = "FRONTEND";
        header("Location: ../../frontend/artist/CreateCampaign.php");
    }
    else
    {
        //First index contains date
        //Second index contains time
        $expiration_date = datePickerParser($_POST['campaign_duration']);
        //first index contains year
        //second index contains month
        //third index contains day
        $exp_day = explode("-", $expiration_date[0]);
        $exp_time = explode(":", $expiration_date[1]);

        //First index contains date
        //Second index contains time
        $release_date = dayAndTimeSplitter(getCurrentDate("America/Edmonton"));
        //first index contains day
        //second index contains month
        //third index contains year
        $release_day = explode("-", $release_date[0]);
        $release_time = explode(":", $release_date[1]);

        if(isInTheFuture($exp_day, $release_day, $exp_time, $release_time))
        {
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
        }
        else
        {
            $_SESSION['status'] = StatusCodes::CampaignTimeErr;
            $_SESSION['dependencies'] = "FRONTEND";
            header("Location: ../../frontend/artist/CreateCampaign.php");
        }
    }
?>