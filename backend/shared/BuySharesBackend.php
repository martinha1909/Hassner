<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../constants/AccountTypes.php';
    include '../constants/LoggingModes.php';
    include 'include/MarketplaceHelpers.php';

    $_SESSION['logging_mode'] = LogModes::BUY_SHARE;

    $conn = connect();
    $connPDO = connectPDO();
    $amount_bought = $_POST['purchase_quantity'];
    if($_SESSION['buy_asked_price'] == 1)
    {
        $asked_price = key($_POST['asked_price']);
    }
    $current_date_time = getCurrentDate("America/Edmonton");
    $date_parser = dayAndTimeSplitter($current_date_time);

    //not enough siliqas
    if($_SESSION['user_balance'] < ($amount_bought * $_SESSION['purchase_price']))
    {
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
            $sell_order_info = $res->fetch_assoc();
            $result = searchAccount($conn, $sell_order_info['user_username']);
            $account_info = $result->fetch_assoc();

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
                                                    $date_parser[0],
                                                    $date_parser[1]);

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