<?php
    include '../APIs/control/logic.php';
    include '../APIs/control/connection.php';

    $conn = connect();
    deleteDatabase($conn);
    
    echo '<h3 style="color: green;">Database deleted successfully</h3>';

    closeCon($conn);
?>