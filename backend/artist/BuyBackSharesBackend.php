<?php
    header('Content-Type: application/json');
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../constants/StatusCodes.php';
    include '../shared/include/MarketplaceHelpers.php';

    $conn = connect();
    $connPDO = connectPDO();
    $json_status = StatusCodes::NONE;
    $current_date = date('Y-m-d H:i:s');
    $buy_back_price = $_POST['buy_back_price'];
    $buy_back_quantity = $_POST['buy_back_quantity'];
    $sell_order_id = $_POST['order_id'];

    $current_market_price = getArtistPricePerShare($_SESSION['username']);

    //Checks to see if price is outdated
    if($buy_back_price != $current_market_price)
    {
        $json_status = StatusCodes::PRICE_OUTDATED;
    }
    else
    {
        $res_sell_order = searchSellOrderByID($conn, $sell_order_id);
        $sell_order_info = $res_sell_order->fetch_assoc();

        $res_seller = searchAccount($conn, $sell_order_info['user_username']);
        $seller_account_info = $res_seller->fetch_assoc();

        $res_artist = searchAccount($conn, $_SESSION['username']);
        $artist_account_info = $res_artist->fetch_assoc();

        $buyer_new_balance = $artist_account_info['balance'] - ($buy_back_price * $buy_back_quantity);
        $seller_new_balance = $seller_account_info['balance'] + ($buy_back_price * $buy_back_quantity);

        //We want to subtract here since we will add to share_repurchase column
        $buyer_new_share_amount = $artist_account_info['Shares'] - $buy_back_quantity;
        $seller_new_share_amount = $seller_account_info['Shares'] - $buy_back_quantity;

        hx_debug(HX::BUY_SHARES,  "Buy back shares details: \n".
                                  'Buyer is: '.$_SESSION['username']."\n".
                                  'Seller is: '.$seller_account_info['username']."\n".
                                  'Buy back quantity is: '.$buy_back_quantity."\n".
                                  'Buy back price is: '.$buy_back_price."\n".
                                  'Buyer old balance: '.$artist_account_info['balance']."\n".
                                  'Buyer new balance: '.$buyer_new_balance."\n".
                                  'Seller old balance: '.$seller_account_info['balance']."\n".
                                  'Seller new balance: '.$seller_new_balance."\n".
                                  'Buyer old share amount: '.$artist_account_info['Shares']."\n".
                                  'Buyer new share amount: '.$buyer_new_share_amount."\n".
                                  'Seller old share amount: '.$seller_account_info['Shares']."\n".
                                  'Seller new share amount: '.$seller_new_share_amount."\n");

        $json_status = buyBackShares($connPDO,
                                     $_SESSION['username'],
                                     $seller_account_info['username'],
                                     $buyer_new_balance,
                                     $seller_new_balance,
                                     $seller_new_share_amount,
                                     $buyer_new_share_amount,
                                     $current_market_price,
                                     $buy_back_quantity,
                                     $sell_order_id,
                                     $current_date);

        refreshSellOrderTable();
    }

    echo json_encode($json_status);

    //buyBackShare
?>