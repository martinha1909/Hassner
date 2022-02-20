<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../shared/include/CampaignHelpers.php';
    include '../constants/LoggingModes.php';
    include '../constants/Timezone.php';

    $_SESSION['lock_count']++;

    $_SESSION['logging_mode'] = LogModes::CAMPAIGN;

    $conn = connect();

    $offer = $_POST['offer'];
    //Only to be used and populated if artist selects the "Other" option
    $other_offer = "";
    $expiration_date = $_POST['campaign_duration'];
    $type = $_POST['raffle_or_benchmark'];
    $minimum_ethos = $_POST['minimum_ethos'];
    date_default_timezone_set(Timezone::MST);
    $current_date = date('Y-m-d H:i:s');

    if(empty($offer) || empty($expiration_date) || empty($type) || empty($minimum_ethos))
    {
        $_SESSION['status'] = StatusCodes::CampaignEmpty;
        $_SESSION['dependencies'] = "FRONTEND";
        header("Location: ../../frontend/artist/CreateCampaign.php");
    }
    else
    {
        $expiration_date = campaignDatePickerParser($expiration_date);

        $exp_day = explode(" ", $expiration_date)[0];
        $release_day = (new DateTime(explode(" ", $current_date)[0]))->format('d-m-Y');
        //We don't care about the seconds in determining if the time is in the future or not
        $exp_time = substr(explode(" ", $expiration_date)[1], 0, 5);
        $release_time = explode(" ", $current_date)[1];

        if(isInTheFuture(explode("-", $exp_day), 
                        explode("-", $release_day), 
                        explode(":", $exp_time), 
                        explode(":", $release_time)))
        {
            if($_SESSION['lock_count'] == 0)
            {
                if($offer == "other")
                {
                    $other_offer = $_POST['other_offering'];
                    postCampaign($conn, 
                                $_SESSION['username'], 
                                $other_offer, 
                                $current_date, 
                                $expiration_date,
                                $type,
                                $minimum_ethos);
                }
                else
                {
                    postCampaign($conn, 
                                $_SESSION['username'], 
                                $offer, 
                                $current_date,
                                $expiration_date,
                                $type,
                                $minimum_ethos);
                }
                $_SESSION['dependencies'] = "FRONTEND";
                returnToMainPage();
            }
            else
            {
                $_SESSION['dependencies'] = "FRONTEND";
                returnToMainPage();
            }
        }
        else
        {
            $_SESSION['status'] = StatusCodes::CampaignTimeErr;
            $_SESSION['dependencies'] = "FRONTEND";
            header("Location: ../../frontend/artist/CreateCampaign.php");
        }
    }
?>