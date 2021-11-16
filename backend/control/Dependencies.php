<?php
    session_start();
    
    // Chance these are missing
    if(!array_key_exists('dependencies', $_SESSION))
    {
        $_SESSION['dependencies'] = "FRONTEND";
    }
    if(!array_key_exists('status', $_SESSION))
    {
        $_SESSION['status'] = "";
    }
    if($_SESSION['dependencies'] == "FRONTEND")
    {
        include '../../backend/logging/logger.php';
        include '../../backend/control/Queries.php';
        include '../../backend/control/connection.php';
        include '../../backend/shared/include/Helper.php';
        include '../../backend/shared/include/TimeUtil.php';
        include '../../backend/constants/ErrorLogPath.php';
        include '../../backend/constants/ErrorLogType.php';
    }
    else if($_SESSION['dependencies'] == "BACKEND")
    {
        include '../logging/logger.php';
        include '../control/Queries.php';
        include '../control/connection.php';
        include '../shared/include/Helper.php';
        include '../shared/include/TimeUtil.php';
        include '../constants/ErrorLogPath.php';
        include '../constants/ErrorLogType.php';
    }
?>