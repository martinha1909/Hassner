<?php
    session_start();
    if($_SESSION['dependencies'] == "FRONTEND")
    {
        include '../../APIs/control/logic.php';
        include '../../APIs/control/connection.php';
        include '../../APIs/shared/Helper.php';
        include '../../APIs/shared/TimeUtil.php';
    }
    else if($_SESSION['dependencies'] == "BACKEND")
    {
        include '../control/logic.php';
        include '../control/connection.php';
        include '../shared/Helper.php';
        include '../shared/TimeUtil.php';
    }
?>