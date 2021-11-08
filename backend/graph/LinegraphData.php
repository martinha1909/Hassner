<?php
    header('Content-Type: application/json');
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../constants/GraphOption.php';
    include '../shared/include/MarketplaceHelpers.php';

    date_default_timezone_set("America/Edmonton");
    $graph_options = $_POST['graph_option'];

    $conn = connect();
    $json_data = array();
    $db_current_date_time = date('Y-m-d H:i:s');
    $days_ago = "";

    if($graph_options == GraphOption::ONE_DAY)
    {
        $days_ago = date("Y-m-d H:i:s", strtotime("-1 day"));

        //No need to specify the log interval here since event scheduler in the db is scheduled to log 
        //every 15 minutes by default
        $res = getJSONDataWithinInterval($conn, $_SESSION['selected_artist'], $days_ago, $db_current_date_time);

        while($row = $res->fetch_assoc())
        {
            $row['price_per_share'] = round($row['price_per_share'], 2);
            array_push($json_data, $row);
        }
    }
    else if($graph_options == GraphOption::FIVE_DAY)
    {
        $counter = 0;
        $days_ago = date("Y-m-d H:i:s", strtotime("-5 days"));

        $res = getJSONDataWithinInterval($conn, $_SESSION['selected_artist'], $days_ago, $db_current_date_time);

        while($row = $res->fetch_assoc())
        {
            //since the event scheduler logs every 15 mins, we skip every other row so the data is 30 mins apart
            if($counter % 2 == 0)
            {
                $row['price_per_share'] = round($row['price_per_share'], 2);
                array_push($json_data, $row);
            }

            $counter++;
        }
    }
    //group these conditions together since 1-month, 6-month, YTD, and 1 year graphs all have intervals of 1 day
    else
    {
        $all_pps_in_a_day = array();
        $last_fetched_day = "";
        $day_high_pps = 0;
        if($graph_options == GraphOption::ONE_MONTH)
        {
            $days_ago = date("Y-m-d H:i:s", strtotime("-1 month"));
        }
        else if($graph_options == GraphOption::SIX_MONTH)
        {
            $days_ago = date("Y-m-d H:i:s", strtotime("-6 months"));
        }
        else if($graph_options == GraphOption::YEAR_TO_DATE)
        {
            //gets first day of the current year
            $days_ago = date("Y-m-d H:i:s", strtotime(date('Y-01-01')));
        }
        else if($graph_options == GraphOption::ONE_YEAR)
        {
            $days_ago = date("Y-m-d H:i:s", strtotime("-1 year"));
        }
        else if($graph_options == GraphOption::FIVE_YEAR)
        {
            $days_ago = date("Y-m-d H:i:s", strtotime("-5 years"));
        }
        
        $res = getJSONDataWithinInterval($conn, $_SESSION['selected_artist'], $days_ago, $db_current_date_time);
        //fetch data from the first row, since we know that it will always be a new date
        $prev_row = $res->fetch_assoc();
        array_push($all_pps_in_a_day, $prev_row['price_per_share']);
        //Grab the date and don't care about the time in 1-month graph
        $last_fetched_day = (explode(" ", $prev_row['date_recorded']))[0];
        while($row = $res->fetch_assoc())
        {
            $json_date_split = explode(" ", $row['date_recorded']);
            $date = $json_date_split[0];
            $time = $json_date_split[1];
            //If we have hit a new date, we grab the date and details from the previous row, which contains
            //the date that we have been storing
            if($date != $last_fetched_day)
            {
                //gets the highest price per share from that day
                $day_high_pps = getMaxPPSByDay($all_pps_in_a_day);

                $prev_row['price_per_share'] = round($day_high_pps, 2);
                //don't care about the time in 1-month graph
                $prev_row['date_recorded'] = (explode(" ", $prev_row['date_recorded']))[0];

                array_push($json_data, $prev_row);

                //reset the array and make the first index to be the current row's price_per_share
                $all_pps_in_a_day = array($row['price_per_share']);
                $day_high_pps = 0;
                //we have seen a new date, so update last_fetched_day
                $last_fetched_day = $date;
            }
            else
            {
                array_push($all_pps_in_a_day, $row['price_per_share']);
            }

            $prev_row = $row;
        }

        //This section here considers the last element of the query, since the above loop only push to json_data
        //values in prev_row
        array_push($all_pps_in_a_day, $prev_row['price_per_share']);
        $day_high_pps = getMaxPPSByDay($all_pps_in_a_day);

        $prev_row['price_per_share'] = round($day_high_pps, 2);
        //don't care about the time in 1-month graph
        $prev_row['date_recorded'] = (explode(" ", $prev_row['date_recorded']))[0];

        array_push($json_data, $prev_row);
    }

    closeCon($conn);

    print json_encode($json_data);
?>