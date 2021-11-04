<?php
    header('Content-Type: application/json');
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $conn = connect();
    $json_data = array();

    $sql = "SELECT artist_username, price_per_share, time_recorded, date_recorded FROM artist_stock_change WHERE artist_username = ? ORDER BY time_recorded";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $_SESSION['selected_artist']);
    $stmt->execute();
    $result = $stmt->get_result();

    while($row = $result->fetch_assoc())
    {
        $json_data[] = $row;
    }

    closeCon($conn);

    print json_encode($json_data);
?>