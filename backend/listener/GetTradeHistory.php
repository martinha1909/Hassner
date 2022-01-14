<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php'; 
    include '../shared/include/MarketplaceHelpers.php';
    include '../object/TradeHistory.php';
    include '../object/TradeHistoryList.php';
    include '../object/Node.php';

    $_SESSION['trade_history_from'] = $_POST['trade_history_from'];
    $_SESSION['trade_history_to'] = $_POST['trade_history_to'];

    if(empty($_SESSION['trade_history_from']) || empty($_SESSION['trade_history_to']))
    {
        $_SESSION['trade_history_from'] = 0;
        $_SESSION['trade_history_to'] = 0;

        echo(json_encode(array(
            "status" => StatusCodes::ErrEmpty,
            "msg" => "To date or From date is empty",
            "trade_history" => array(
                "size" => 0,
                "date" => "",
                "price_high" => "",
                "price_low" => "",
                "volume" => "",
                "value" => "",
                "trade" => ""
            )
        )));
    }
    else
    {
        $date = explode("-", $_SESSION['trade_history_from']);
        //reformat to match the expectation of isInTheFuture, which is of form DD-MM-YYYY
        $from_date = array($date[2], $date[1], $date[0]);
        //We don't care about time 
        $time = "00:00:00";
        $from_time = explode(":", $time);
        $to_date = explode("-", $_SESSION['trade_history_to']);
        $time = "00:00";
        $to_time = explode(":", $time);
        if(!isInTheFuture($to_date, $from_date, $to_time, $from_time))
        {
            echo(json_encode(array(
                "status" => StatusCodes::TIME_ERR,
                "msg" => "To date has to be later than from date",
                "trade_history" => array(
                    "size" => 0,
                    "date" => "",
                    "price_high" => "",
                    "price_low" => "",
                    "volume" => "",
                    "value" => "",
                    "trade" => ""
                )
            )));
        }
        else
        {
            $conn = connect();
            $res = searchSharesBoughtFromArtist($conn, $_SESSION['selected_artist']);
            $trade_history_list = populateTradeHistory($conn, $res);

            closeCon($conn);

            echo json_encode(array(
                "status" => StatusCodes::Success,
                "msg" => "",
                "trade_history" => $trade_history_list->toDictionary()
            ));
            // if($_SESSION['trade_history_type'] == TradeHistoryType::SHARE_BOUGHT)
            // {
            //     $conn = connect();
            //     $res = searchSharesBoughtFromArtist($conn, $_SESSION['username']);
            //     $trade_history_list = populateTradeHistory($conn, $res);

            //     closeCon($conn);
            
                // echo json_encode(array(
                //     "status" => StatusCodes::Success,
                //     "msg" => "",
                //     "trade_history_type" => $_SESSION['trade_history_type'],
                //     "trade_history" => $trade_history_list->toDictionary()
                // ));
            // }
            // else if($_SESSION['trade_history_type'] == TradeHistoryType::SHARE_REPURCHASE)
            // {
            //     $conn = connect();
            //     $res = searchArtistBuyBackShares($conn, $_SESSION['username']);
            //     $trade_history_list = populateTradeHistory($conn, $res);
            //     echo json_encode(array(
            //         "status" => StatusCodes::Success,
            //         "msg" => "",
            //         "trade_history_type" => $_SESSION['trade_history_type'],
            //         "trade_history" => $trade_history_list->toDictionary()
            //     ));
            // }
        }
    }
?>