<?php
    session_start();
    if($_SESSION['dependencies'] == "FRONTEND")
    {
        include '../../backendend/control/logic.php';
        include '../../backendend/control/connection.php';
        include '../../backendend/shared/Helper.php';
        include '../../backendend/shared/TimeUtil.php';
    }
    else if($_SESSION['dependencies'] == "BACKEND")
    {
        include '../control/logic.php';
        include '../control/connection.php';
        include '../shared/Helper.php';
        include '../shared/TimeUtil.php';
    }
?>