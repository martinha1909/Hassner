<?php
    session_start();
    $campaign_option = $_POST['campaign_options'];

    if($campaign_option == "Experience")
    {
        $_SESSION['campaign_option'] = "EXPERIENCE";
        array_push($_SESSION['campaign_data'], "experience");
    }
    else if($campaign_option == "Object")
    {
        $_SESSION['campaign_option'] = "OBJECT";
    }

    //Reset the array here everytime the artist wanna switch to a different type of campaign
    unset($_SESSION['campaign_data']);
    $_SESSION['campaign_data'] = array();

    header("Location: ../../frontend/artist/CreateCampaign.php");
?>