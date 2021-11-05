<?php
    header('Content-Type: application/json');
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../constants/GraphOption.php';

    $conn = connect();
    $json_data = array();
    $current_date_time = getCurrentDate("America/Edmonton");
    $date_parser = dayAndTimeSplitter($current_date_time);
    $current_day = $date_parser[0];
    $current_time = $date_parser[1];

    if($_SESSION['graph_options'] == GraphOption::ONE_DAY)
    {

        $one_day_ago = date('d-m-Y', strtotime('-1 day', strtotime($date_parser[0])));
        echo $one_day_ago;
        //No need to specify the log interval here since event scheduler in the db is scheduled to log 
        //every 15 minutes by default
        $sql = "SELECT artist_username, price_per_share, time_recorded, date_recorded FROM artist_stock_change WHERE artist_username = ? ORDER BY time_recorded";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $_SESSION['selected_artist']);
        $stmt->execute();
        $result = $stmt->get_result();

        while($row = $result->fetch_assoc())
        {
            $json_data[] = $row;
        }
    }

    closeCon($conn);

    // print json_encode($json_data);
?>