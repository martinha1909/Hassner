<?php
    include '../APIs/logic.php';
    include '../APIs/connection.php';

    $conn = connect();
    $status = cleanDatabase($conn);

    if($status == 1)
    {
        echo '<h3 style="color: green;">Database cleaned successfully</h3>';
    }
    else 
    {
        echo '<h3 style="color: red;">Database failed to clean</h3>';
    }
?>