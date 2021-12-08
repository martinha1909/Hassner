<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../constants/LoggingModes.php';
    include '../shared/include/MarketplaceHelpers.php';

    $_SESSION['logging_mode'] = LogModes::SELL_SHARE;

    $conn = connect();

    date_default_timezone_set(Timezone::MST);
    $current_date = date('Y-m-d H:i:s');

    if(empty($_POST['asked_price']))
    {
        $_SESSION['status'] = "EMPTY_ERR";
        if($_SESSION['account_type'] == AccountType::User)
        {
            header("Location: ../../frontend/listener/ArtistUserShareInfo.php");
        }
        else if($_SESSION['account_type'] == AccountType::Artist)
        {
            returnToMainPage();
        }
    }
    else
    {
        $quantity = $_POST['purchase_quantity'];
        $asked_price = $_POST['asked_price'];

        if($_SESSION['account_type'] == AccountType::Artist)
        {
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
                "date_posted: " => $current_date,
                "is_from_injection" => false
            ));
            hx_debug(HX::SELL_SHARES, $msg);
            $_SESSION['status'] = postSellOrder($conn, 
                                                $_SESSION['username'], 
                                                $_SESSION['username'], 
                                                $new_quantity, 
                                                $asked_price,
                                                $current_date,
                                                false);

            refreshSellOrderTable();
            refreshBuyOrderTable();
        }
        $_SESSION['dependencies'] = "FRONTEND";
        returnToMainPage();
    }
?>