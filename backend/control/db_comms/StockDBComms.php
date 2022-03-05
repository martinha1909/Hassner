<?php
    function getJSONDataWithinInterval($conn, $artist_username, $date_from, $date_to)
    {
        $sql = "SELECT artist_username, price_per_share, date_recorded FROM artist_stock_change WHERE artist_username = ? AND date_recorded >=? AND date_recorded <= ? ORDER BY date_recorded";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $artist_username, $date_from, $date_to);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchSharesInArtistShareHolders($conn, $user_username, $artist_username)
    {
        $sql = "SELECT shares_owned FROM artist_shareholders WHERE user_username = ? AND artist_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $user_username, $artist_username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchUserInvestedArtists($conn, $user_username)
    {
        $sql = "SELECT shares_owned, artist_username FROM artist_shareholders WHERE user_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $user_username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchArtistTotalSharesBought($conn, $artist_username)
    {
        $sql = "SELECT shares_owned, user_username, artist_username FROM artist_shareholders WHERE artist_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $artist_username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function getArtistShareHolders($conn, $artist_username)
    {
        $sql = "SELECT user_username FROM artist_shareholders WHERE artist_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $artist_username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function getArtistShareHoldersInfo($conn, $artist_username)
    {
        $sql = "SELECT * FROM artist_shareholders WHERE artist_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $artist_username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function artistShareDistributionInit($connPDO, $artist_username, $share_distributing, $initial_pps, $comment, $date)
    {
        $status = 0;
        $injection_id = 0;

        try {
            $connPDO->beginTransaction();

            $stmt = $connPDO->prepare("UPDATE account SET Share_Distributed = ? WHERE username = ?");
            $stmt->bindValue(1, $share_distributing);
            $stmt->bindValue(2, $artist_username);
            $stmt->execute(array($share_distributing, $artist_username));

            $stmt = $connPDO->prepare("UPDATE account SET price_per_share = ? WHERE username = ?");
            $stmt->bindValue(1, $initial_pps);
            $stmt->bindValue(2, $artist_username);
            $stmt->execute(array($initial_pps, $artist_username));
        
            $stmt = $connPDO->prepare("INSERT INTO inject_history (artist_username, amount, comment, date_injected)
                                        VALUES(?, ?, ?, ?)");
            $stmt->bindValue(1, $artist_username);
            $stmt->bindValue(2, $share_distributing);
            $stmt->bindValue(3, $comment);
            $stmt->bindValue(4, $date);
            $stmt->execute(array($artist_username, $share_distributing, $comment, $date));
            
            $connPDO->commit();
            $status = StatusCodes::Success;

            $msg = "Artist ".$artist_username." went IPO by distributing ".$share_distributing. "shares!";
            hx_info(HX::SHARES_INJECT, $msg);
        } catch (PDOException $e) {
            $connPDO->rollBack();
            $msg = "db error occured with message: " . $e->getMessage();
            hx_error(HX::DB, $msg);

            $status = StatusCodes::ErrGeneric;
        }

        return $status;
    }

    function purchaseAskedPriceShare($conn, $buyer, $seller, $buyer_account_type, $seller_account_type, $artist, $buyer_new_balance, $seller_new_balance, $initial_pps, $new_pps, $buyer_new_share_amount, $seller_new_share_amount, $amount, $price, $order_id, $date_purchased, $indicator, $buy_mode)
    {
        $status = 0;

        try {
            $conn->beginTransaction();

            //p2p trading
            if($buyer_account_type == AccountType::User && $seller_account_type == AccountType::User)
            {
                $stmt = $conn->prepare("UPDATE account SET Shares = '$buyer_new_share_amount' WHERE username = ?");
                $stmt->bindValue(1, $buyer);
                $stmt->execute(array($buyer));

                $stmt = $conn->prepare("UPDATE account SET Shares = '$seller_new_share_amount' WHERE username = ?");
                $stmt->bindValue(1, $seller);
                $stmt->execute(array($seller));
            }
            //Buys from artist, there are 2 different scenarios:
            //from share injection
            //from the sell order created by the artist after buying back his shares
            else if($buyer_account_type == AccountType::User && $seller_account_type == AccountType::Artist)
            {
                $stmt = $conn->prepare("UPDATE account SET Shares = '$buyer_new_share_amount' WHERE username = ?");
                $stmt->bindValue(1, $buyer);
                $stmt->execute(array($buyer));

                if($buy_mode == ShareInteraction::BUY_FROM_INJECTION)
                {
                    //Increase the total number of shares bought of that artist accross all users
                    $stmt = $conn->prepare("UPDATE account SET Shares = Shares + ? WHERE username = ?");
                    $stmt->bindValue(1, $amount);
                    $stmt->bindValue(2, $seller);
                    $stmt->execute(array($amount, $seller));
                }
                else if($buy_mode == ShareInteraction::BUY)
                {
                    //reduce the shares_repurchase of the artist after the sell order from their share repurchase has been sold
                    $stmt = $conn->prepare("UPDATE account SET shares_repurchase = shares_repurchase - ? WHERE username = ?");
                    $stmt->bindValue(1, $amount);
                    $stmt->bindValue(2, $seller);
                    $stmt->execute(array($amount, $seller));

                    $stmt = $conn->prepare("UPDATE artist_shareholders SET shares_owned = shares_owned - ? WHERE user_username = ?");
                    $stmt->bindValue(1, $amount);
                    $stmt->bindValue(2, $seller);
                    $stmt->execute(array($amount, $seller));

                    //Increase the total number of shares bought of that artist accross all users since the artist no longer holds shares of himself
                    $stmt = $conn->prepare("UPDATE account SET Shares = Shares + ? WHERE username = ?");
                    $stmt->bindValue(1, $amount);
                    $stmt->bindValue(2, $seller);
                    $stmt->execute(array($amount, $seller));
                }
            }

            //We want to update the selling price of sell orders that are from injection to the current purchasing price
            if($buy_mode != ShareInteraction::BUY_FROM_INJECTION)
            {
                $search_conn = 0;
                if($_SESSION['dependencies'] == "TEST")
                {
                    $search_conn = connectTest();
                }
                else
                {
                    $search_conn = connect();
                }
                $res_from_injection = searchSellOrdersIDFromInjection($search_conn, $artist);
                while($row = $res_from_injection->fetch_assoc())
                {
                    $stmt = $conn->prepare("UPDATE sell_order SET selling_price = ? WHERE id = ?");
                    $stmt->bindValue(1, $new_pps);
                    $stmt->bindValue(2, $row['id']);
                    $stmt->execute(array($new_pps, $row['id']));
                }
            }

            $stmt = $conn->prepare("UPDATE account SET balance = '$buyer_new_balance' WHERE username = ?");
            $stmt->bindValue(1, $buyer);
            $stmt->execute(array($buyer));

            $stmt = $conn->prepare("UPDATE account SET balance = '$seller_new_balance' WHERE username = ?");
            $stmt->bindValue(1, $seller);
            $stmt->execute(array($seller));

            $stmt = $conn->prepare("UPDATE account SET price_per_share = '$new_pps' WHERE username = ?");
            $stmt->bindValue(1, $artist);
            $stmt->execute(array($artist));

            $stmt = $conn->prepare("INSERT INTO buy_history (user_username, seller_username, artist_username, no_of_share_bought, price_per_share_when_bought, date_purchased)
                                    VALUES(?, ?, ?, ?, ?, ?)");
            $stmt->bindValue(1, $buyer);
            $stmt->bindValue(2, $seller);
            $stmt->bindValue(3, $artist);
            $stmt->bindValue(4, $amount);
            $stmt->bindValue(5, $new_pps);
            $stmt->bindValue(6, $date_purchased);
            $stmt->execute(array($buyer, $seller, $artist, $amount, $new_pps, $date_purchased));

            $search_conn = 0;
            if($_SESSION['dependencies'] == "TEST")
            {
                $search_conn = connectTest();
            }
            else
            {
                $search_conn = connect();
            }
            $res_buyer = searchSharesInArtistShareHolders($search_conn, $buyer, $artist);
            $res_seller = searchSharesInArtistShareHolders($search_conn, $seller, $artist);
            //if the buyer has not invested in the artist, add a row
            if($res_buyer->num_rows == 0)
            {
                $stmt = $conn->prepare("INSERT INTO artist_shareholders (user_username, artist_username, shares_owned)
                                        VALUES(?, ?, ?)");
                $stmt->bindValue(1, $buyer);
                $stmt->bindValue(2, $artist);
                $stmt->bindValue(3, $amount);
                $stmt->execute(array($buyer, $artist, $amount));
            }
            //otherwise just update the new shares owned of the user towards the artist
            else
            {
                $current_share_amount_buyer = $res_buyer->fetch_assoc();
                $new_share_amount_buyer = $current_share_amount_buyer['shares_owned'] + $amount;
                $stmt = $conn->prepare("UPDATE artist_shareholders SET shares_owned = '$new_share_amount_buyer' WHERE user_username = ? AND artist_username = ?");
                $stmt->bindValue(1, $buyer);
                $stmt->bindValue(2, $artist);
                $stmt->execute(array($buyer, $artist));
            }

            //Decrease the number of shares the seller is currently holding of the artist
            $current_share_amount_seller = $res_seller->fetch_assoc();
            if($seller != $artist)
            {
                $new_share_amount_seller = $current_share_amount_seller['shares_owned'] - $amount;
                $stmt = $conn->prepare("UPDATE artist_shareholders SET shares_owned = '$new_share_amount_seller' WHERE user_username = ? AND artist_username = ?");
                $stmt->bindValue(1, $seller);
                $stmt->bindValue(2, $artist);
                $stmt->execute(array($seller, $artist));
            }
            
            if($indicator == "AUTO_PURCHASE")
            {
                $stmt = $conn->prepare("UPDATE sell_order SET no_of_share = no_of_share - ? WHERE id = ?");
                $stmt->bindValue(1, $amount);
                $stmt->bindValue(2, $order_id);
                $stmt->execute(array($amount, $order_id));

                hx_debug(HX::SELL_ORDER, "Sell order ".$order_id." update no_of_share to ".$amount);
            }
            else if($indicator == "AUTO_SELL")
            {
                $stmt = $conn->prepare("UPDATE buy_order SET quantity = quantity - ? WHERE id = ?");
                $stmt->bindValue(1, $amount);
                $stmt->bindValue(2, $order_id);
                $stmt->execute(array($amount, $order_id));

                hx_debug(HX::BUY_ORDER, "Buy order ".$order_id." update quantity to ".$amount);
            }

            $conn->commit();
            $status = StatusCodes::Success;
            hx_info(HX::BUY_SHARES, "buyer ".$buyer." purchased ".$amount." shares from ".$seller." for $".$price);
        } catch (PDOException $e) {
            $conn->rollBack();
            hx_error(HX::DB, "Failed: " . $e->getMessage());
            echo "Failed: " . $e->getMessage()."\n";

            $status = StatusCodes::ErrGeneric;
        }

        updateMarketPriceOrderToPPS($new_pps, $artist);

        recalcCampaignParticipants($buyer, $seller, $buyer_account_type, $seller_account_type, $artist);
        addToSellHistory($seller, $buyer, $artist, $amount, $price, $date_purchased);

        return $status;
    }

    function buyBackShares($conn, $artist_username, $seller_username, $buyer_new_balance, $seller_new_balance, $seller_new_share_amount, $buyer_new_share_amount, $initial_pps, $amount_bought, $sell_order_id, $date_purchased)
    {
        $status = 0;

        try {
            $conn->beginTransaction();

            $stmt = $conn->prepare("UPDATE account SET balance = '$buyer_new_balance' WHERE username = ?");
            $stmt->bindValue(1, $artist_username);
            $stmt->execute(array($artist_username));

            $stmt = $conn->prepare("UPDATE account SET balance = '$seller_new_balance' WHERE username = ?");
            $stmt->bindValue(1, $seller_username);
            $stmt->execute(array($seller_username));
            
            $stmt = $conn->prepare("UPDATE account SET Shares = '$seller_new_share_amount' WHERE username = ?");
            $stmt->bindValue(1, $seller_username);
            $stmt->execute(array($seller_username));

            $stmt = $conn->prepare("UPDATE account SET Shares = '$buyer_new_share_amount' WHERE username = ?");
            $stmt->bindValue(1, $artist_username);
            $stmt->execute(array($artist_username));
            
            $stmt = $conn->prepare("UPDATE account SET shares_repurchase = shares_repurchase + ? WHERE username = ?");
            $stmt->bindValue(1, $amount_bought);
            $stmt->bindValue(2, $artist_username);
            $stmt->execute(array($amount_bought, $artist_username));

            $stmt = $conn->prepare("INSERT INTO buy_history (user_username, seller_username, artist_username, no_of_share_bought, price_per_share_when_bought, date_purchased)
                                    VALUES(?, ?, ?, ?, ?, ?)");
            $stmt->bindValue(1, $artist_username);
            $stmt->bindValue(2, $seller_username);
            $stmt->bindValue(3, $artist_username);
            $stmt->bindValue(4, $amount_bought);
            $stmt->bindValue(5, $initial_pps);
            $stmt->bindValue(6, $date_purchased);
            $stmt->execute(array($artist_username, $seller_username, $artist_username, $amount_bought, $initial_pps, $date_purchased));

            $search_conn = connect();
            $res_buyer = searchSharesInArtistShareHolders($search_conn, $artist_username, $artist_username);
            $res_seller = searchSharesInArtistShareHolders($search_conn, $seller_username, $artist_username);
            //if this is the first time the artist has bought back shares, add a row
            if($res_buyer->num_rows == 0)
            {
                $stmt = $conn->prepare("INSERT INTO artist_shareholders (user_username, artist_username, shares_owned)
                                        VALUES(?, ?, ?)");
                $stmt->bindValue(1, $artist_username);
                $stmt->bindValue(2, $artist_username);
                $stmt->bindValue(3, $amount_bought);
                $stmt->execute(array($artist_username, $artist_username, $amount_bought));
            }
            //otherwise just update the amount of shares that were bought back
            else
            {
                $current_share_amount = $res_buyer->fetch_assoc();
                $new_share_amount = $current_share_amount['shares_owned'] + $amount_bought;
                $stmt = $conn->prepare("UPDATE artist_shareholders SET shares_owned = '$new_share_amount' WHERE user_username = ? AND artist_username = ?");
                $stmt->bindValue(1, $artist_username);
                $stmt->bindValue(2, $artist_username);
                $stmt->execute(array($artist_username, $artist_username));
            }

            $current_share_amount_seller = $res_seller->fetch_assoc();
            $new_share_amount_seller = $current_share_amount_seller['shares_owned'] - $amount_bought;
            $stmt = $conn->prepare("UPDATE artist_shareholders SET shares_owned = '$new_share_amount_seller' WHERE user_username = ? AND artist_username = ?");
            $stmt->bindValue(1, $seller_username);
            $stmt->bindValue(2, $artist_username);
            $stmt->execute(array($seller_username, $artist_username));
            
            $stmt = $conn->prepare("UPDATE sell_order SET no_of_share = no_of_share - ? WHERE id = ?");
            $stmt->bindValue(1, $amount_bought);
            $stmt->bindValue(2, $sell_order_id);
            $stmt->execute(array($amount_bought, $sell_order_id));

            $conn->commit();
            $status = StatusCodes::Success;

            $msg = "Artist ".$artist_username." just bought back ".$amount_bought." shares from user ".$seller_username;
            hx_info(HX::BUY_SHARES, $msg);
        } catch (PDOException $e) {
            $conn->rollBack();
            $msg = "db error occured, reverting operation with error message: ".$e->getMessage();
            hx_error(HX::DB, $msg);

            $status = StatusCodes::ErrGeneric;
        }

        recalcCampaignParticipants($artist_username, $seller_username, AccountType::Artist, AccountType::User, $artist_username);

        return $status;
    }
?>