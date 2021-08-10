<?php
    session_start();
    if($_SESSION['account_type'] == "user")
    {
        include '../../APIs/logic.php';
        include '../../APIs/connection.php';
    }
    else if($_SESSION['account_type'] == "artist")
    {

    }

?>