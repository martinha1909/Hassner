<?php
    include '../backend/control/logic.php';
    include '../backend/control/connection.php';

    $conn = connect();
    cleanDatabase($conn);
    
    echo '<h3 style="color: green;">Database cleaned successfully</h3>';

    closeCon($conn);
?>