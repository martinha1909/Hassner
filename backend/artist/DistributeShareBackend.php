<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../constants/StatusCodes.php';
    include '../constants/LoggingModes.php';

    $_SESSION['logging_mode'] = LogModes::SHARE_DIST;

    $conn = connect();
    $shares_distributing = 0;
    $shares_distributing = $_POST['distribute_share'];
    $siliqas_raising = $_POST['siliqas_raising'];

    //For now the first time artists distribute their share will just have this comment to keep things consistent
    $comment = "First IPO";
    $current_date = getCurrentDate("America/Edmonton");
    $date_parser = dayAndTimeSplitter($current_date);

    if(empty($shares_distributing) || empty($siliqas_raising))
    {
        $_SESSION['status'] = StatusCodes::ErrEmpty;
        header("Location: ../../frontend/artist/PersonalPage.php");
    }
    else if(!is_numeric($shares_distributing) || !is_numeric($siliqas_raising))
    {
        $_SESSION['status'] = StatusCodes::ErrNum;
        header("Location: ../../frontend/artist/PersonalPage.php");
    }
    else
    {
        $initial_pps = $siliqas_raising/$shares_distributing;

        artistShareDistributionInit($conn, 
                                    $_SESSION['username'], 
                                    $shares_distributing, 
                                    $initial_pps, 
                                    $comment, 
                                    $date_parser[0],
                                    $date_parser[1]);

        $_SESSION['display'] = 0;
        $_SESSION['dependencies'] = "FRONTEND";

        header("Location: ../../frontend/artist/Artist.php");
    }
?>