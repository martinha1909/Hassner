<?php
    session_start();

    $type = $_POST['display_type'];

    if(in_array($type, ["Investors", "Ethos", "Balance", "Account", "Campaign"]))
    {
        $_SESSION['display'] = strtoupper($type);
        echo(json_encode(array(
            "display" => $type,
        )));
    }
    //shouldnt reach here, but if for some reason wrong data is being sent will redirect to the first tab
    else
    {
        $_SESSION['display'] = "ETHOS";
    }
?>