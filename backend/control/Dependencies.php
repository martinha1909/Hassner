<?php
    session_start();
    if($_SESSION['dependencies'] == "FRONTEND")
    {
        include '../../backend/control/logic.php';
        include '../../backend/control/connection.php';
        include '../../backend/shared/Helper.php';
        include '../../backend/shared/TimeUtil.php';
    }
    else if($_SESSION['dependencies'] == "BACKEND")
    {
        include '../control/logic.php';
        include '../control/connection.php';
        include '../shared/Helper.php';
        include '../shared/TimeUtil.php';
    }
?>