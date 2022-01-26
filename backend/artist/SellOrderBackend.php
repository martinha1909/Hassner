<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../constants/LoggingModes.php';
    include '../shared/include/MarketplaceHelpers.php';
    include '../shared/include/StockTradeHelpers.php';

    $conn = connect();

    date_default_timezone_set(Timezone::MST);
    $current_date = date('Y-m-d H:i:s');

    if(empty($_POST['asked_price']))
    {
        echo(json_encode(array(
            "status" => StatusCodes::ErrEmpty,
            "msg" => "Amount cannot be empty"
        )));
    }
    else
    {
        $quantity = $_POST['purchase_quantity'];
        $asked_price = $_POST['asked_price'];

        $msg = "autoSell param: ".json_encode(array(
            "user_username" => $_SESSION['username'],
            "artist_username" => $_SESSION['username'],
            "asked_price" => $asked_price,
            "quantity:" => $quantity,
            "current_date: " => $current_date,
            "is_from_injection" => false
        ));
        hx_debug(HX::SELL_SHARES, $msg);
        $new_quantity = autoSell($_SESSION['username'], $_SESSION['username'], $asked_price, $quantity, $current_date, false);

        $msg = "postSellOrder param: ".json_encode(array(
            "user_username" => $_SESSION['username'],
            "artist_username" => $_SESSION['username'],
            "quantity" => $new_quantity,
            "asked_price:" => $asked_price,
            "sell_limit" => -1,
            "sell_stop" => -1,
            "date_posted: " => $current_date,
            "is_from_injection" => false
        ));
        hx_debug(HX::SELL_SHARES, $msg);
        $_SESSION['status'] = postSellOrder($conn, 
                                            $_SESSION['username'], 
                                            $_SESSION['username'], 
                                            $new_quantity, 
                                            $asked_price,
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
    }
?>