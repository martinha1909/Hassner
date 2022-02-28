<?php
    $_SESSION['dependencies'] = "TEST";
    include 'backend/control/connection.php';
    include 'backend/control/Queries.php';

    $conn = connect();

    $res = searchAccount($conn, "martin");
    echo json_encode($res);
?>