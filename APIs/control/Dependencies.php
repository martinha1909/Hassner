<?php
    session_start();
    if($_SESSION['dependencies'] == 0)
    {
        include '../../APIs/logic.php';
        include '../../APIs/connection.php';
        include '../../APIs/helper.php';
        include '../../APIs/shared/TimeUtil.php';
    }
    else
    {
        include '../logic.php';
        include '../connection.php';
        include '../helper.php';
        include '../shared/TimeUtil.php';
    }
?>