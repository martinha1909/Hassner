<?php
    //include this before dependencies since class has to be loaded before session_start() in Dependencies.php
    $_SESSION['dependencies'] = "BACKEND";
    include '../object/SellOrder.php';
    include '../control/Dependencies.php';
    include '../constants/AccountTypes.php';
    include '../shared/include/MarketplaceHelpers.php';
    include '../shared/include/CampaignHelpers.php';

    $_SESSION['logging_mode'] = LogModes::BUY_SHARE;

    $connPDO = connectPDO();
    $conn = connect();
    $sell_orders = $_SESSION['repurchase_sell_orders'];
    $date_purchased = date('Y-m-d H:i:s');;

    if(sizeof($sell_orders) > 0)
    {
        for($i = 0; $i < sizeof($sell_orders); $i++)
        {
            $sell_order_id = $sell_orders[$i]->getID();

            //seller vars and attributes
            $res_seller = searchAccount($conn, $sell_orders[$i]->getUser());
            $seller_account_info = $res_seller->fetch_assoc();

            $seller_new_balance = $seller_account_info['balance'] + ($sell_orders[$i]->getSellingPrice() * $sell_orders[$i]->getNoOfShare());
            $seller_new_share_amount = $seller_account_info['Shares'] - $sell_orders[$i]->getNoOfShare();

            //artist buying back vars and attributes
            $res_buyer = searchAccount($conn, $_SESSION['username']);
            $artist_account_info = $res_buyer->fetch_assoc();

            $artist_new_balance = $artist_account_info['balance'] - ($sell_orders[$i]->getSellingPrice() * $sell_orders[$i]->getNoOfShare());
            $artist_new_share_amount = $artist_account_info['Shares'] - $sell_orders[$i]->getNoOfShare();
            $initial_pps = $artist_account_info['price_per_share'];
            $amount_bought = $sell_orders[$i]->getNoOfShare();

            $msg = "buyBackShares param: ".json_encode(array(
                "index" => $i,
                "buyer" => $_SESSION['username'],
                "seller" => $sell_orders[$i]->getUser(),
                "buyer_new_balance:" => $artist_new_balance,
                "seller_new_balance: " => $seller_new_balance,
                "seller_new_share_amount" => $seller_new_share_amount,
                "buyer_new_share_amount" => $artist_new_share_amount,
                "initial_pps" => $initial_pps,
                "amount_bought" => $amount_bought,
                "sell_order_id" => $sell_order_id,
                "selling_price" => $sell_orders[$i]->getSellingPrice(),
                "date_purchased" => $date_purchased
            ));
            hx_debug(HX::BUY_SHARES, $msg);

            $_SESSION['status'] = buyBackShares($connPDO,
                                                $_SESSION['username'],
                                                $sell_orders[$i]->getUser(),
                                                $artist_new_balance,
                                                $seller_new_balance,
                                                $seller_new_share_amount,
                                                $artist_new_share_amount,
                                                $initial_pps,
                                                $amount_bought,
                                                $sell_order_id,
                                                $date_purchased);

            refreshSellOrderTable();
            refreshBuyOrderTable();
        }
    }

    $_SESSION['dependencies'] = "FRONTEND";
    returnToMainPage();
?>