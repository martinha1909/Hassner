<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $_SESSION['logging_mode'] = "SHARE_DIST";

    $conn = connect();
    $_SESSION['shares_distributing'] = 0;
    $_SESSION['shares_distributing'] = $_POST['distribute_share'];
    $_SESSION['deposit'] = $_POST['deposit'];

    if(empty($_SESSION['shares_distributing']) || empty($_SESSION['deposit']))
    {
        $_SESSION['status'] = "EMPTY_ERR";
        header("Location: ../../frontend/artist/PersonalPage.php");
    }
    else if(!is_numeric($_SESSION['shares_distributing']) || !is_numeric($_SESSION['deposit']))
    {
        $_SESSION['status'] = "NUM_ERR";
        header("Location: ../../frontend/artist/PersonalPage.php");
    }
    else
    {
        $_SESSION['currency'] = $_POST['currency'];
        if($_SESSION['currency'] == "Currency")
        {
            $_SESSION['status'] = "CURRENCY_ERR";
            header("Location: ../../frontend/artist/PersonalPage.php");
        }
        else
        {
            $_SESSION['deposit'] = convertToSiliqas($_SESSION['deposit'], $_SESSION['conversion_rate'], $_SESSION['currency']);
            echo $_SESSION['deposit'];
            $_SESSION['lower_bound'] = $_SESSION['deposit']/$_SESSION['shares_distributing'];
            if($_SESSION['lower_bound'] < 0.5)
            {
                header("Location: ../../frontend/artist/PersonalPage.php");
            }

            $_SESSION['initial_pps'] = $_SESSION['lower_bound'];

            $_SESSION['dependencies'] = "FRONTEND";

            header("Location: ../../frontend/artist/Checkout.php");
        }
    }
?>