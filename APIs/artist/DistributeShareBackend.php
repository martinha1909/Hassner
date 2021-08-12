<?php
    $_SESSION['dependencies'] = 1;
    include '../control/Dependencies.php';

    $conn = connect();
    $shares_distributing = $_POST['distribute_share'];
    $deposit = $_POST['deposit'];

    if(!is_numeric($shares_distributing) || !is_numeric($deposit))
    {
        header("Location: ../../frontend/artist/PersonalPage.php");
    }
    else
    {
        //following information is not doing anything for now 
        //but will be used once linking to bank account is completed
        $name_on_card = $_POST['name_on_card'];
        $card_number = $_POST['card_number'];
        $cvv = $_POST['cvv'];

        $lower_bound = $deposit/$shares_distributing;
        $initial_pps = $lower_bound;

        artistShareDistributionInit($conn, $_SESSION['username'], $shares_distributing, $lower_bound, $initial_pps);

        $_SESSION['dependencies'] = 0;

        header("Location: ../../frontend/artist/PersonalPage.php");
    }
?>