<?php
    include 'DatabaseHelpers.php';
    include '../backend/control/connection.php';

    $conn = connect();
    cleanDatabase($conn);
    
    echo '<h3 class="suc-msg">Database cleaned successfully</h3>';

    closeCon($conn);
?>