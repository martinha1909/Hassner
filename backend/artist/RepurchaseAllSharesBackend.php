<?php
    //include this before dependencies since class has to be loaded before session_start() in Dependencies.php
    $_SESSION['dependencies'] = "BACKEND";
    include '../object/SellOrder.php';
    include '../control/Dependencies.php';

    $connPDO = connectPDO();
    $conn = connect();
    $sell_orders = $_SESSION['repurchase_sell_orders'];
    $date_purchased = dayAndTimeSplitter((getCurrentDate("America/Edmonton")));

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
        $new_pps = $sell_orders[$i]->getSellingPrice();
        $amount_bought = $sell_orders[$i]->getNoOfShare();

        $_SESSION['status'] = buyBackShares($connPDO,
                                            $_SESSION['username'],
                                            $sell_orders[$i]->getUser(),
                                            $artist_new_balance,
                                            $seller_new_balance,
                                            $seller_new_share_amount,
                                            $artist_new_share_amount,
                                            $initial_pps,
                                            $new_pps,
                                            $amount_bought,
                                            $sell_order_id,
                                            $sell_orders[$i]->getSellingPrice(),
                                            $date_purchased[0],
                                            $date_purchased[1]);
    }

    $_SESSION['dependencies'] = "FRONTEND";
    returnToMainPage();
?>