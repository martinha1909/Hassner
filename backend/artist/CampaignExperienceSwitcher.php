<?php
    session_start();
    $experience_option = $_POST['experience_options'];
    if($experience_option == "tickets")
    {

    }
    else if($experience_option == "backstage")
    {
        
    }
    else if($experience_option == "other")
    {
        $other_option = $_POST['custom'];
        echo $other_option;
    }
?>