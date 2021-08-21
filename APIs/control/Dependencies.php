<?php
    if($_SESSION['index'] == 0)
    {
        session_start();
    }
    if($_SESSION['dependencies'] == 0)
    {
        include '../../APIs/control/logic.php';
        include '../../APIs/control/connection.php';
        include '../../APIs/shared/Helper.php';
        include '../../APIs/shared/TimeUtil.php';
    }
    else
    {
        include '../control/logic.php';
        include '../control/connection.php';
        include '../shared/Helper.php';
        include '../shared/TimeUtil.php';
    }
?>