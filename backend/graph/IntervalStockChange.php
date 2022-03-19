<?php
    header('Content-Type: application/json');
    include '../control/Dependencies.php';
    $_SESSION['dependencies'] = "BACKEND";
    include '../shared/include/MarketplaceHelpers.php';
    include '../constants/GraphOption.php';

    date_default_timezone_set("America/Edmonton");
    $graph_options = $_POST['graph_option'];

    $conn = connect();
    $json_data = 0;
    $current_stock_price = getArtistPricePerShare($_SESSION['selected_artist']);
    //Start of day yesterday
    $db_current_date_time = date('Y-m-d H:i:s');
    $days_ago = "";

    if($graph_options == GraphOption::ONE_DAY)
    {
        $days_ago = date("Y-m-d H:i:s", strtotime("-1 day"));
    }
    else if($graph_options == GraphOption::FIVE_DAY)
    {
        $days_ago = date("Y-m-d H:i:s", strtotime("-5 days"));
    }
    //group these conditions together since 1-month, 6-month, YTD, and 1 year graphs all have intervals of 1 day
    else
    {
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
    }

    $interval_max_val = getMaxValWithinInterval($conn, $_SESSION['selected_artist'], $days_ago, $db_current_date_time);
    $json_data = round((($current_stock_price - $interval_max_val)/$interval_max_val) * 100, 2);

    closeCon($conn);
    $_SESSION['dependencies'] = "FRONTEND";
    print json_encode($json_data);
?>