<?php
    $_SESSION['dependencies'] = "FRONTEND";

    include '../../backend/control/Dependencies.php';

    if($_SESSION['account_type'] == AccountType::User)
    {
        $msg = "User ".$_SESSION['username']." just logged in";
        hx_info(HX::LOGIN, $msg);

        header("Location: ../../frontend/listener/Listener.php");
        die;
    }
    else if($_SESSION['account_type'] == AccountType::Artist)
    {
        $msg = "Artist ".$_SESSION['username']." just logged in";
        hx_info(HX::LOGIN, $msg);

        header("Location: ../../frontend/artist/Artist.php");
        die;
    }
    else if($_SESSION['account_type'] == AccountType::Admin)
    {
        $msg = "Admin ".$_SESSION['username']." just logged in";
        hx_info(HX::LOGIN, $msg);

        header("Location: ../../frontend/admin/Admin.php");
        die;
    }
?>