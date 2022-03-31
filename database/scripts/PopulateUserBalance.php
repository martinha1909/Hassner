<?php
    session_start();
    $_SESSION['dependencies'] = "SCRIPTS";

    include 'include/DatabaseHelpers.php';
    include '../../backend/control/Queries.php';
    include '../../backend/control/connection.php';
    include '../../backend/constants/StatusCodes.php';

    $conn = connect();
    $balance = 100;
    $ret = populateUserBalance($conn, $balance);
    
    if($ret = StatusCodes::Success)
    {
        echo '<h3 style="color: green;">All users were given '.$balance.'</h3>';
    }
    else
    {
        echo '<h3 class="error-msg">Failed</h3>';
    }

    echo '<a href="scriptsMain.php">Return to scripts main page</a>';

    closeCon($conn);
?>