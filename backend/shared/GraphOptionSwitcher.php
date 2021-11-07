<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../constants/AccountTypes.php';

    $_SESSION['graph_options'] = $_POST['graph_options'];

    $_SESSION['dependencies'] = "FRONTEND";
    if($_SESSION['account_type'] == AccountType::User)
    {
        header("Location: ../../frontend/listener/ArtistUserShareInfo.php");
    }
    else if($_SESSION['account_type'] == AccountType::Artist)
    {
        returnToMainPage();
    }
?>