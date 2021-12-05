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

    //For now the first time artists distribute their share will just have this comment to keep things consistent
    $comment = "IPO";
    date_default_timezone_set(Timezone::MST);
    $current_date = date('Y-m-d H:i:s');

    if(empty($shares_distributing) || empty($siliqas_raising))
    {
        $_SESSION['status'] = StatusCodes::ErrEmpty;
        returnToMainPage();
    }
    else if(!is_numeric($shares_distributing) || !is_numeric($siliqas_raising))
    {
        $_SESSION['status'] = StatusCodes::ErrNum;
        returnToMainPage();
    }
    else
    {
        $conn = connect();
        $initial_pps = $siliqas_raising/$shares_distributing;

        artistShareDistributionInit($connPDO, 
                                    $_SESSION['username'], 
                                    $shares_distributing, 
                                    $initial_pps, 
                                    $comment, 
                                    $current_date);

        //IPO is considered a sell order as well
        postSellOrder($conn, $_SESSION['username'], $_SESSION['username'], $shares_distributing, $initial_pps, $current_date);

        $_SESSION['dependencies'] = "FRONTEND";

        header("Location: ../../frontend/artist/Artist.php");
    }
?>