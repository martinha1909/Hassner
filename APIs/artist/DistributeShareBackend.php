<?php
    $_SESSION['dependencies'] = 1;
    include '../control/Dependencies.php';

    $conn = connect();
    $_SESSION['shares_distributing'] = $_POST['distribute_share'];
    $_SESSION['deposit'] = $_POST['deposit'];

    if(!is_numeric($_SESSION['shares_distributing']) || !is_numeric($_SESSION['deposit']))
    {
        $_SESSION['notify'] = 1;
        header("Location: ../../frontend/artist/PersonalPage.php");
    }
    else
    {
        $_SESSION['currency'] = $_POST['currency'];
        $_SESSION['lower_bound'] = $_SESSION['deposit']/$_SESSION['shares_distributing'];
        echo $_SESSION['lower_bound'];
        if($_SESSION['lower_bound'] < 0.5)
        {
            $_SESSION['notify'] = 2;
            header("Location: ../../frontend/artist/PersonalPage.php");
        }

        $_SESSION['initial_pps'] = $_SESSION['lower_bound'];

        $_SESSION['dependencies'] = 0;

        // header("Location: ../../frontend/artist/Checkout.php");
    }
?>