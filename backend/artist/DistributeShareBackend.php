<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../constants/StatusCodes.php';
    include '../constants/LoggingModes.php';

    $_SESSION['logging_mode'] = LogModes::SHARE_DIST;

    $connPDO = connectPDO();
    $shares_distributing = 0;
    $shares_distributing = $_POST['distribute_share'];
    $siliqas_raising = $_POST['siliqas_raising'];
    $msg = "data received from form: ".json_encode(array(
        "shares_distributing" => $shares_distributing,
        "siliqas_raising" => $siliqas_raising
    ));
    hx_debug(HX::SHARES_INJECT, $msg);

    //For now the first time artists distribute their share will just have this comment to keep things consistent
    $comment = "IPO";
    date_default_timezone_set(Timezone::MST);
    $current_date = date('Y-m-d H:i:s');

    if(empty($shares_distributing) || empty($siliqas_raising))
    {
        $_SESSION['status'] = StatusCodes::ErrEmpty;
        $msg = "Artist ".$_SESSION['username']." entered empty values for amount of shares and USD";
        hx_error(HX::SHARES_INJECT, $msg);
        $msg = "shares_distributing or siliqas_raising empty";
        hx_debug(HX::SHARES_INJECT, $msg);

        returnToMainPage();
    }
    else if(!is_numeric($shares_distributing) || !is_numeric($siliqas_raising))
    {
        $_SESSION['status'] = StatusCodes::ErrNum;
        $msg = "Artist ".$_SESSION['username']." entered non-numeric values for amount of shares and USD";
        hx_error(HX::SHARES_INJECT, $msg);
        $msg = "non-numeric values for shares_distributing or siliqas_raising";
        hx_debug(HX::SHARES_INJECT, $msg);

        returnToMainPage();
    }
    else
    {
        $conn = connect();
        $initial_pps = $siliqas_raising/$shares_distributing;

        $msg = "artistShareDistributionInit param: ".json_encode(array(
            "artist_username" => $_SESSION['username'],
            "share_distributing" => $shares_distributing,
            "initial_pps" => $initial_pps,
            "comment" => $comment,
            "date" => $current_date
        ));
        hx_debug(HX::SHARES_INJECT, $msg);

        artistShareDistributionInit($connPDO, 
                                    $_SESSION['username'], 
                                    $shares_distributing, 
                                    $initial_pps, 
                                    $comment, 
                                    $current_date);

        $msg = "postSellOrder param: ".json_encode(array(
            "user_username" => $_SESSION['username'],
            "artist_username" => $_SESSION['username'],
            "quantity" => $shares_distributing,
            "asked_price" => $initial_pps,
            "date_posted" => $current_date,
            "is_from_injection" => true
        ));
        hx_debug(HX::SHARES_INJECT, $msg);

        //IPO is considered a sell order as well
        postSellOrder($conn, $_SESSION['username'], $_SESSION['username'], $shares_distributing, $initial_pps, $current_date, true);

        $_SESSION['dependencies'] = "FRONTEND";

        header("Location: ../../frontend/artist/Artist.php");
    }
?>