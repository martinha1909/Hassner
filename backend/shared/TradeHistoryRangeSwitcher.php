<?php
    header('Content-Type: application/json');

    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../constants/AccountTypes.php';
    include '../constants/StatusCodes.php';
    include '../constants/TradeHistoryType.php';

    $_SESSION['trade_history_from'] = $_POST['trade_history_from'];
    $_SESSION['trade_history_to'] = $_POST['trade_history_to'];
    $_SESSION['trade_history_type'] = $_POST['trade_history_type'];

    //By default it's "buy shares" option
    if(empty($_SESSION['trade_history_type']))
    {
        $_SESSION['trade_history_type'] = TradeHistoryType::SHARE_BOUGHT;
    }

    if(empty($_SESSION['trade_history_from']) || empty($_SESSION['trade_history_to']))
    {
        $_SESSION['trade_history_from'] = 0;
        $_SESSION['trade_history_to'] = 0;

        echo(json_encode(array(
            "status" => StatusCodes::ErrEmpty,
            "msg" => "To date or From date is empty",
            "trade_history_from" => $_SESSION['trade_history_from'],
            "trade_history_to" => $_SESSION['trade_history_to'],
            "trade_history_type" => $_SESSION['trade_history_type']
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
                "trade_history_from" => $_SESSION['trade_history_from'],
                "trade_history_to" => $_SESSION['trade_history_to'],
                "trade_history_type" => $_SESSION['trade_history_type']
            )));
        }
        else
        {
            echo(json_encode(array(
                "status" => StatusCodes::Success,
                "msg" => "",
                "trade_history_from" => $_SESSION['trade_history_from'],
                "trade_history_to" => $_SESSION['trade_history_to'],
                "trade_history_type" => $_SESSION['trade_history_type']
            )));
        }
    }

    // $_SESSION['dependencies'] = "FRONTEND";
    // if($_SESSION['account_type'] == AccountType::User)
    // {
    //     header("Location: ../../frontend/listener/ArtistUserShareInfo.php");
    // }
    // else if($_SESSION['account_type'] == AccountType::Artist)
    // {
    //     header("Location: ../../frontend/artist/Artist.php");
    // }
?>