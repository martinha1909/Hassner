<?php
    header('Content-Type: application/json');
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../constants/GraphOption.php';

    date_default_timezone_set("America/Edmonton");

    $conn = connect();
    $json_data = array();
    $db_current_date_time = date('Y-m-d H:i:s');
    // //Contains distinct dates that will be appearing on the graph, in ascending order
    $days_ago = "";

    if($_SESSION['graph_options'] == GraphOption::ONE_DAY || $_SESSION['graph_options'] == GraphOption::NONE)
    {
        $days_ago = date("Y-m-d H:i:s", strtotime("-5 day"));

        //No need to specify the log interval here since event scheduler in the db is scheduled to log 
        //every 15 minutes by default
        $res = getJSONDataWithinInterval($conn, $_SESSION['selected_artist'], $days_ago, $db_current_date_time);

        while($row = $res->fetch_assoc())
        {
            array_push($json_data, $row);
        }
    }
    // else if($_SESSION['graph_options'] == GraphOption::FIVE_DAY)
    // {
    //     $days_ago = date('Y-m-d', strtotime('-5 days', strtotime($date_parser[0])));

    //     $result = getDatesWithinInterval5D($conn, $_SESSION['selected_artist'], $days_ago, $current_day);

    //     while($row = $result->fetch_assoc())
    //     {
    //         array_push($graph_dates, $row['date_recorded']);
    //     }

    //     for($i = 0; $i < sizeof($graph_dates); $i++)
    //     {
    //         $result = getData5D($conn, $_SESSION['selected_artist'], $graph_dates[$i]);

    //         while($row = $result->fetch_assoc())
    //         {
    //             array_push($json_data, $row);
    //         }
    //     }
    // }

    closeCon($conn);

    print json_encode($json_data);
?>