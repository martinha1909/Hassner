<?php
    $campaign_option = $_POST['campaign_options'];

    if($campaign_option == "Experience")
    {
        $_SESSION['campaign_option'] = "EXPERIENCE";
    }
    else if($campaign_option == "Object")
    {
        $_SESSION['campaign_option'] = "OBJECT";
    }

    header("Location: ../../frontend/artist/CreateCampaign.php");
?>