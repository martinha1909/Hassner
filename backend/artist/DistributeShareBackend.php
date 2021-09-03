<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $_SESSION['logging_mode'] = "SHARE_DIST";

    $conn = connect();
    $shares_distributing = 0;
    $shares_distributing = $_POST['distribute_share'];
    $siliqas_raising = $_POST['siliqas_raising'];

    if(empty($shares_distributing) || empty($siliqas_raising))
    {
        $_SESSION['status'] = "EMPTY_ERR";
        header("Location: ../../frontend/artist/PersonalPage.php");
    }
    else if(!is_numeric($shares_distributing) || !is_numeric($siliqas_raising))
    {
        $_SESSION['status'] = "NUM_ERR";
        header("Location: ../../frontend/artist/PersonalPage.php");
    }
    else
    {
        $initial_pps = $siliqas_raising/$shares_distributing;

        artistShareDistributionInit($conn, $_SESSION['username'], $shares_distributing, $initial_pps);

        $_SESSION['display'] = 0;
        $_SESSION['dependencies'] = "FRONTEND";

        header("Location: ../../frontend/artist/Artist.php");
    }
?>