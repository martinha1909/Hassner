<?php
    $_SESSION['dependencies'] = 1;
    include '../control/Dependencies.php';

    $conn = connect();

    $addition_shares = $_POST['share_distributing'];

    $res = searchNumberOfShareDistributed($conn, $_SESSION['username']);
    $share_distributed = $res->fetch_assoc();

    $new_shares_distributed = $share_distributed['Share_Distributed'] + $addition_shares;
    updateShareDistributed($conn, $_SESSION['username'], $new_shares_distributed);

    $_SESSION['edit'] = 0;
    $_SESSION['dependencies'] = 0;
?>