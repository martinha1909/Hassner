<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../constants/TradeHistoryType.php';

    $_SESSION['trade_history_type'] = $_POST['trade_history_type'];

    $_SESSION['dependencies'] = "FRONTEND";
    if($_SESSION['account_type'] == AccountType::User)
    {
        header("Location: ../../frontend/listener/ArtistUserShareInfo.php");
    }
    else if($_SESSION['account_type'] == AccountType::Artist)
    {
        header("Location: ../../frontend/artist/Artist.php");
    }
?>