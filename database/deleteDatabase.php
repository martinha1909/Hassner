<?php
    include '../backend/control/Queries.php';
    include '../backend/control/connection.php';

    $conn = connect();
    deleteDatabase($conn);
    
    echo '<h3 style="color: green;">Database deleted successfully</h3>';

    closeCon($conn);
?>