<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../constants/LoggingModes.php';
    include '../constants/ShareInteraction.php';
    include '../shared/include/MarketplaceHelpers.php';
    include '../shared/include/StockTradeHelpers.php';
    include '../shared/include/CampaignHelpers.php';

    $connPDO = connectPDO();
    $current_date = date('Y-m-d H:i:s');

    $quantity = $_POST['purchase_quantity'];
    $current_market_price = getArtistPricePerShare($_SESSION['username']);

    $new_quantity = autoSellNoLimitStop($_SESSION['username'], 
                                        $_SESSION['username'], 
                                        $quantity, 
                                        $current_market_price,
                                        $current_market_price,
                                        false);

    $msg = "postSellOrder param: ".json_encode(array(
        "user_username" => $_SESSION['username'],
        "artist_username" => $_SESSION['username'],
        "quantity" => $new_quantity,
        "asked_price:" => $current_market_price,
        "sell_limit" => -1,
        "sell_stop" => -1,
        "date_posted: " => $current_date,
        "is_from_injection" => false
    ));
    hx_debug(HX::SELL_SHARES, $msg);
    $_SESSION['status'] = postSellOrder($connPDO, 
                                        $_SESSION['username'], 
                                        $_SESSION['username'], 
                                        $new_quantity, 
                                        $current_market_price,
                                        -1,
                                        -1,
                                        $current_date,
                                        false);

    refreshSellOrderTable();
    refreshBuyOrderTable();

    echo(json_encode(array(
        "status" => StatusCodes::Success,
        "msg" => "Sell order posted successfully"
    )));

    $_SESSION['dependencies'] = "FRONTEND";
?>