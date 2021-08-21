<?php
    include '../APIs/control/logic.php';
    include '../APIs/control/connection.php';

    $conn = connect();
    cleanDatabase($conn);
    
    echo '<h3 style="color: green;">Database cleaned successfully</h3>';

    closeCon($conn);
?>