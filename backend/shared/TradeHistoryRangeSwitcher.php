<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../constants/AccountTypes.php';

    $_SESSION['trade_history_from'] = $_POST['trade_history_from'];
    $_SESSION['trade_history_to'] = $_POST['trade_history_to'];

    if(empty($_SESSION['trade_history_from']) || empty($_SESSION['trade_history_to']))
    {
        $_SESSION['trade_history_from'] = 0;
        $_SESSION['trade_history_to'] = 0;
    }

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