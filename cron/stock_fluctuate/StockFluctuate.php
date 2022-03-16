<?php
    define("HX_INCLUDE_DIR", dirname(dirname(dirname(__FILE__))));
    define("CRON_INCLUDE_DIR", dirname(dirname(__FILE__))."\include");

    session_start();
    $_SESSION['dependencies'] = "CRON";

    include CRON_INCLUDE_DIR."/CronHelpers.php";
    include HX_INCLUDE_DIR."/backend/shared/include/StockTradeHelpers.php";
    include HX_INCLUDE_DIR."/backend/constants/AccountTypes.php";
    include HX_INCLUDE_DIR."/backend/constants/ShareInteraction.php";
    include HX_INCLUDE_DIR."/backend/constants/StatusCodes.php";
    include HX_INCLUDE_DIR."/backend/constants/HX.php";
    include HX_INCLUDE_DIR."/backend/constants/Timezone.php";
    include HX_INCLUDE_DIR."/backend/logging/logger.php";
    include HX_INCLUDE_DIR."/backend/control/connection.php";
    include HX_INCLUDE_DIR."/backend/control/Queries.php";

    $_SESSION['info'] = false;
    $_SESSION['debug'] = false;
    $_SESSION['error'] = false;

    date_default_timezone_set(Timezone::MST);

    $current_date = date('Y-m-d H:i:s');
    $connPDO = connectPDO();
    $conn = connect();
    
    echo "Date ran: ".$current_date."\n";
    $all_platform_artists = getAllArtist($conn);
    for($i = 0; $i < sizeof($all_platform_artists); $i++)
    {
        echo "Artist: ".$all_platform_artists[$i]."\n";
        $hours_ago = date("Y-m-d H:i:s", strtotime("-59 mins"));

        $num_open_buy_orders = getArtistOpenBuyOrdersWithinInterval($conn, $all_platform_artists[$i], $hours_ago, $current_date);
        echo "Found ".$num_open_buy_orders." shares requesting in the past hour\n";
        $num_open_sell_orders = getArtistOpenSellOrdersWithinInterval($conn, $all_platform_artists[$i], $hours_ago, $current_date);
        echo "Found ".$num_open_sell_orders." shares selling in the past hour\n";
        $new_artist_pps = calculatePPSBySupplyDemand($conn, 
                                                     $all_platform_artists[$i], 
                                                     $num_open_buy_orders, 
                                                     $num_open_sell_orders);

        if($new_artist_pps > 0)
        {
            updateArtistPPS($connPDO, $all_platform_artists[$i], $new_artist_pps);
            echo "Stock price updated to ".$new_artist_pps."\n";
            updateMarketPriceOrderToPPS($new_artist_pps, $all_platform_artists[$i]);
        }
        else
        {
            echo "Received unexpected value of -1, did not update stock price\n";
        }

        echo "------------------------------------\n";
    }

    echo "////////////////////////////////////////////////////////////////////////////\n";

    closeCon($conn);
?>