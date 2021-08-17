<?php
    include '../APIs/logic.php';
    include '../APIs/connection.php';

    $conn = connect();
    deleteDatabase($conn);
    
    echo '<h3 style="color: green;">Database deleted successfully</h3>';
?>