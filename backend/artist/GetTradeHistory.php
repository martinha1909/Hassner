<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php'; 
    include '../shared/include/MarketplaceHelpers.php';
    include '../object/TradeHistory.php';
    include '../object/TradeHistoryList.php';
    include '../../backend/object/Node.php';

    $conn = connect();

    $_SESSION['trade_history_from'] = $_POST['trade_history_from'];
    $_SESSION['trade_history_to'] = $_POST['trade_history_to'];
    $_SESSION['trade_history_type'] = $_POST['trade_history_type'];

    $res = searchSharesBoughtFromArtist($conn, $_SESSION['username']);
    $trade_history_list = populateTradeHistory($conn, $res);

    echo $trade_history_list->toDictionary();

    closeCon($conn);
?>