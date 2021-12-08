<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../constants/AccountTypes.php';
    include '../constants/LoggingModes.php';
    include '../shared/include/MarketplaceHelpers.php';

    $_SESSION['logging_mode'] = LogModes::BUY_SHARE;

    $conn = connect();
    $connPDO = connectPDO();
    $amount_bought = $_POST['purchase_quantity'];
    if($_SESSION['buy_asked_price'] == 1)
    {
        $asked_price = key($_POST['asked_price']);
        $msg = "Fetch buying data: ".json_encode(array(
            "amount_bought" => $amount_bought,
            "asked_price" => $asked_price
        ));
        hx_debug(HX::BUY_SHARES, $msg);
    }
    $current_date = date('Y-m-d H:i:s');

    //not enough siliqas
    if($_SESSION['user_balance'] < ($amount_bought * $_SESSION['purchase_price']))
    {
        $msg = "total price has to pay: ".($amount_bought * $_SESSION['purchase_price'])."\nUser balance: ".$_SESSION['user_balance'];
        hx_debug(HX::BUY_SHARES, $msg);

        $msg = "Not enough balance for ".$_SESSION['username']. " (".$_SESSION['user_balance']."<".($amount_bought * $_SESSION['purchase_price']);
        hx_error(HX::BUY_SHARES, $msg);

        //disabling both options, forbids user from buying anything unless they purchase more siliqas
        $_SESSION['buy_market_price'] = 0;
        $_SESSION['buy_asked_price'] = 0;
        $_SESSION['buy_sell'] = 0;
        $_SESSION['buy_options'] = 0;
        $_SESSION['status'] = "SILIQAS_ERR";
        if($_SESSION['account_type'] == AccountType::User)
        {
            header("Location: ../../frontend/listener/ArtistUserShareInfo.php");
        }
        else if($_SESSION['account_type'] == AccountType::Artist)
        {
            header("Location: ../../frontend/artist/Artist.php");
        }
    }
    else
    {
        //if the user chooses a seller from bid price section
        if($_SESSION['buy_asked_price'] == 1)
        {
            $res = searchSellOrderByID($conn, $_SESSION['seller_toggle']);
            $msg = "searchSellOrderByID returned ".$res->num_rows." entries";
            hx_debug(HX::QUERY, $msg);

            $sell_order_info = $res->fetch_assoc();
            $msg = "sell_order_info: ".json_encode($sell_order_info);
            hx_debug(HX::QUERY, $msg);

            $result = searchAccount($conn, $sell_order_info['user_username']);
            $msg = "searchAccount returned ".$result->num_rows." entries";
            hx_debug(HX::QUERY, $msg);

            $account_info = $result->fetch_assoc();
            $msg = "account_info: ".json_encode($account_info);
            hx_debug(HX::QUERY, $msg);

            //if the user buys from the bid price, the siliqas will go to the other user since they are the seller
            $seller_new_balance = $account_info['balance'] + ($amount_bought * $_SESSION['purchase_price']);
            //subtracts siliqas from the user
            $buyer_new_balance = $_SESSION['user_balance'] - ($amount_bought * $_SESSION['purchase_price']);

            $seller_new_share_amount = $account_info['Shares'] - $amount_bought;
            $buyer_new_share_amount = $_SESSION['shares_owned'] + $amount_bought;

            //In the case of buying in asked price, the new market price will become the last purchased price
            $new_pps = $_SESSION['purchase_price'];

            if($_SESSION['account_type'] == AccountType::Artist)
            {
                $res_1 = searchArtistSharesBought($conn, $_SESSION['username']);
                $artist_account_info = $res_1->fetch_assoc();
                //we are subtracting here because we will add this amount to share_repurchase column
                $buyer_new_share_amount = $artist_account_info['Shares'] - $amount_bought;

                $new_pps = $_SESSION['purchase_price'];

                $msg = "buyBackShares param: ".json_encode(array(
                    "buyer" => $_SESSION['username'],
                    "seller" => $sell_order_info['user_username'],
                    "buyer_new_balance:" => $buyer_new_balance,
                    "seller_new_balance: " => $seller_new_balance,
                    "seller_new_share_amount" => $seller_new_share_amount,
                    "buyer_new_share_amount" => $buyer_new_share_amount,
                    "initial_pps" => $_SESSION['current_pps']['price_per_share'],
                    "new_pps" => $new_pps,
                    "amount_bought" => $amount_bought,
                    "sell_order_id" => $_SESSION['seller_toggle'],
                    "selling_price" => $sell_order_info['selling_price'],
                    "date_purchased" => $current_date
                ));
                hx_debug(HX::BUY_SHARES, $msg);

                $_SESSION['status'] = buyBackShares($connPDO, 
                                                    $_SESSION['username'], 
                                                    $sell_order_info['user_username'], 
                                                    $buyer_new_balance, 
                                                    $seller_new_balance, 
                                                    $seller_new_share_amount, 
                                                    $buyer_new_share_amount,
                                                    $_SESSION['current_pps']['price_per_share'],
                                                    $new_pps, 
                                                    $amount_bought,
                                                    $_SESSION['seller_toggle'],
                                                    $sell_order_info['selling_price'],
                                                    $current_date);

                refreshSellOrderTable();
                refreshBuyOrderTable();
            }
            $_SESSION['buy_market_price'] = 0;
            $_SESSION['buy_asked_price'] = 0;
            $_SESSION['buy_sell'] = 0;
            $_SESSION['buy_options'] = 0;
            $_SESSION['dependencies'] = "FRONTEND";
             
            if($_SESSION['account_type'] == AccountType::User)
            {
                header("Location: ../../frontend/listener/ArtistUserShareInfo.php");
            }
            else if($_SESSION['account_type'] == AccountType::Artist)
            {
                returnToMainPage();
            }
        }
    }
?>