<?php
        include '../../backend/constants/StatusCodes.php';
        include '../../backend/constants/AccountTypes.php';
        
        //logs in with provided user info and password, then use SQL query to query database 
        //after qurerying return the result
        function login($conn, $username, $pwd) // done2
        {
            $sql = "SELECT * FROM account WHERE username = ? AND password = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $username, $pwd);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }

        function searchAccount($conn, $username)
        {
            $sql = "SELECT * FROM account WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }

        function searchEmail($conn, $email)
        {
            $sql = "SELECT username FROM account WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }

        function searchAccountType($conn, $type)
        {
            $sql = "SELECT * FROM account WHERE account_type = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $type);
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

        function searchUsersInvestment($conn, $user_username)
        {
            $sql = "SELECT * FROM buy_history WHERE user_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $user_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchSpecificInvestment($conn, $user_username, $invested_artist)
        {
            $sql = "SELECT * FROM buy_history WHERE user_username = ? AND artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $user_username, $invested_artist);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchAllInvestments($conn)
        {
            $sql = "SELECT * FROM buy_history";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchAllSellOrders($conn)
        {
            $sql = "SELECT * FROM sell_order";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchSellOrderByUser($conn, $user_username)
        {
            $sql = "SELECT * FROM sell_order WHERE user_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $user_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchAllBuyOrders($conn)
        {
            $sql = "SELECT * FROM buy_order";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchUserBuyOrders($conn, $user_username)
        {
            $sql = "SELECT * FROM buy_order WHERE user_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $user_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchBuyOrdersByArtist($conn, $artist_username)
        {
            $sql = "SELECT * FROM buy_order WHERE artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchSharesRequested($conn, $user_username, $artist_username)
        {
            $sql = "SELECT quantity FROM buy_order WHERE user_username = ? AND artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $user_username, $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchSharesSelling($conn, $user_username, $artist_username)
        {
            $sql = "SELECT no_of_share FROM sell_order WHERE user_username = ? AND artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $user_username, $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchSellOrderByID($conn, $id)
        {
            $sql = "SELECT * FROM sell_order WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchArtistCurrentPricePerShare($conn, $artist_username)
        {
            $sql = "SELECT price_per_share FROM account WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchArtistRepurchaseShares($conn, $artist_username)
        {
            $sql = "SELECT shares_repurchase FROM account WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchInitialPriceWhenBought($conn, $user_username, $invested_artist)
        {
            $sql = "SELECT price_per_share_when_bought FROM buy_history WHERE user_username = ? AND artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $user_username, $invested_artist);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchArtistTotalSharesBought($conn, $artist_username)
        {
            $sql = "SELECT no_of_share_bought FROM buy_history WHERE artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchArtistHighestPrice($conn, $artist_username)
        {
            $sql = "SELECT MAX(selling_price) AS maximum FROM sell_order WHERE artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchArtistLowestPrice($conn, $artist_username)
        {
            $sql = "SELECT MIN(selling_price) AS minimum FROM sell_order WHERE artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchArtistCampaigns($conn, $artist_username)
        {
            $sql = "SELECT id, artist_username, offering, date_posted, time_posted, date_expires, time_expires, type, minimum_ethos, eligible_participants, winner FROM campaign WHERE artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchCampaignMinimumEthos($conn, $campaign_id)
        {
            $sql = "SELECT minimum_ethos, artist_username FROM campaign WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $campaign_id);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchCampaignsByType($conn, $campaign_type)
        {
            $sql = "SELECT id, artist_username, offering, date_posted, time_posted, date_expires, time_expires, type, minimum_ethos, eligible_participants, winner FROM campaign WHERE type = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $campaign_type);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function getArtistShareHolders($conn, $artist_username)
        {
            $sql = "SELECT user_username FROM buy_history WHERE artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function getArtistShareHoldersInfo($conn, $artist_username)
        {
            $sql = "SELECT * FROM buy_history WHERE artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function getInjectionHistory($conn, $artist_username)
        {
            $sql = "SELECT amount, date_injected, time_injected, comment FROM inject_history WHERE artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchSellOrderByArtist($conn, $artist_username)
        {
            $sql = "SELECT * FROM sell_order WHERE artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchSellOrderByArtistAndUser($conn, $user_username, $artist_username)
        {
            $sql = "SELECT * FROM sell_order WHERE artist_username = ? AND user_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $artist_username, $user_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchNumberOfShareDistributed($conn, $artist_username)
        {
            $sql = "SELECT Share_Distributed FROM account WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchArtistSharesBought($conn, $artist_username)
        {
            $sql = "SELECT Shares FROM account WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function signup($conn, $username, $password, $type, $email) //done2
        {
            $original_share= "";
            $transit_no = "";
            $inst_no = "";
            $account_no = "";
            $swift = "";
            $billing_address = "";
            $full_name = "";
            $city = "";
            $state= "";
            $zip = "";
            $card_number="";
            $balance = 0;
            $rate = 0;
            $num_of_shares = 0;
            $status = 0;
            $share_distributed = 0;
            $monthly_shareholder = 0;
            $income = 0;
            $market_cap = 0;
            $share_repurchase = 0;
            if($type == 'artist')
                $price_per_share = 1;
            else
                $price_per_share = 0;
            $result = getMaxID($conn);
            $row = $result->fetch_assoc(); 
            $id = $row["max_id"] + 1;
            $sql = "INSERT INTO account (username, password, account_type, id, Shares, balance, rate, 
                                         Share_Distributed, email, billing_address, Full_name, City, State, ZIP, 
                                         Card_number, Transit_no, Inst_no, Account_no, Swift, price_per_share, 
                                         Monthly_shareholder, Income, Market_cap, shares_repurchase)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssiiddisssssssssssdiddi', $username, $password, $type, $id, $num_of_shares, 
                                                           $balance, $rate, $share_distributed, $email, 
                                                           $billing_address, $full_name, $city, $state, $zip, 
                                                           $card_number, $transit_no, $inst_no, $account_no, 
                                                           $swift, $price_per_share, $monthly_shareholder, 
                                                           $income, $market_cap, $share_repurchase);
            if ($stmt->execute() === TRUE) {
                $status = StatusCodes::Success;
            } else {
                $status = StatusCodes::ErrGeneric;
            }
            return $status;
        }

        function getMaxID($conn)
        {
            $sql = "SELECT MAX(id) AS max_id FROM account";
            $result = mysqli_query($conn,$sql);
            
            return $result;
        }

        function getMaxInjectionID($conn)
        {
            $sql = "SELECT MAX(id) AS max_id FROM inject_history";
            $result = mysqli_query($conn,$sql);
            
            return $result;
        }

        function getMaxSellOrderID($conn)
        {
            $sql = "SELECT MAX(id) AS max_id FROM sell_order";
            $result = mysqli_query($conn,$sql);
            
            return $result;
        }

        function getMaxBuyOrderID($conn)
        {
            $sql = "SELECT MAX(id) AS max_id FROM buy_order";
            $result = mysqli_query($conn,$sql);
            
            return $result;
        }

        function getMaxCampaignID($conn)
        {
            $sql = "SELECT MAX(id) AS max_id FROM campaign";
            $result = mysqli_query($conn,$sql);
            
            return $result;
        }

        function updateCampaignEligibleParticipants($conn, $campaign_id, $eligible_participant)
        {
            $sql = "UPDATE campaign SET eligible_participants = '$eligible_participant' WHERE id='$campaign_id'";
            $conn->query($sql);
        }

        function updateRaffleCampaignWinner($conn, $campaign_id, $winner)
        {
            $sql = "UPDATE campaign SET winner = '$winner' WHERE id='$campaign_id'";
            $conn->query($sql);
        }

        function updateCampaignExpirationDate($conn, $campaign_id, $exp_date)
        {
            $sql = "UPDATE campaign SET date_expires = '$exp_date' WHERE id='$campaign_id'";
            $conn->query($sql);

            $sql = "UPDATE campaign SET time_expires = '$exp_date' WHERE id='$campaign_id'";
            $conn->query($sql);
        }

        function artistShareDistributionInit($conn, $artist_username, $share_distributing, $initial_pps, $comment, $date, $time)
        {
            $status = 0;

            $sql = "UPDATE account SET Share_Distributed = '$share_distributing' WHERE username='$artist_username'";
            if($conn->query($sql))
            {
                $status = StatusCodes::Success;
            }
            else
            {
                return StatusCodes::ErrGeneric;
            }

            $sql = "UPDATE account SET price_per_share = '$initial_pps' WHERE username='$artist_username'";
            if($conn->query($sql))
            {
                $status = StatusCodes::Success;
            }
            else
            {
                return StatusCodes::ErrGeneric;
            }

            $status = addToInjectionHistory($conn, $artist_username, $share_distributing, $comment, $date, $time);
            if($status == "ERROR")
            {
                return "ERROR";
            }

            return $status;
        }

        function updateArtistPPS($conn, $artist_username, $new_pps)
        {
            $sql = "UPDATE account SET price_per_share = '$new_pps' WHERE username='$artist_username'";
            $conn->query($sql);
        }

        function updateShareDistributed($conn, $artist_username, $new_share_distributed, $added_shares, $comment, $date, $time)
        {
            $sql = "UPDATE account SET Share_Distributed = '$new_share_distributed' WHERE username='$artist_username'";
            $conn->query($sql);

            addToInjectionHistory($conn, $artist_username, $added_shares, $comment, $date, $time);
        }

        function saveUserPaymentInfo($conn, $username, $full_name, $email, $address, $city, $state, $zip, $card_name, $card_number)
        {
            $sql = "UPDATE account SET Full_name = '$full_name', email='$email', billing_address='$address', City = '$city', State='$state', ZIP = '$zip', Card_number='$card_number' WHERE username='$username'";
            $conn->query($sql);
        }

        function saveUserAccountInfo($conn, $username, $transit_no, $inst_no, $account_no, $swift)
        {
            $sql = "UPDATE account SET Transit_no = '$transit_no', Inst_no = '$inst_no', Account_no = '$account_no', Swift = '$swift' WHERE username='$username'";
            $conn->query($sql);
        }

        function updateUserBalance($conn, $username, $balance)
        {
            $status = 0;
            $sql = "UPDATE account SET balance = $balance WHERE username = '$username'";
            if ($conn->query($sql) === TRUE) 
            {
                $status = StatusCodes::Success;
            } 
            else 
            {
                $status = StatusCodes::ErrGeneric;
            }  
            return $status;
        }
        function purchaseSiliqas($conn, $username, $coins)
        {
            $coins = round($coins, 2);
            $status = 0;
            $sql = "UPDATE account SET balance = balance + $coins WHERE username = '$username'";
            if ($conn->query($sql) === TRUE) 
            {
                $status = StatusCodes::Success;
            } 
            else 
            {
                $status = StatusCodes::ErrGeneric;
            }  
            return $status;
        }

        function sellSiliqas($conn, $username, $coins)
        {
            $coins = round($coins, 2);
            $status = 0;
            $sql = "UPDATE account SET balance = balance - $coins WHERE username = '$username'";
            if ($conn->query($sql) === TRUE) 
            {
                $status = StatusCodes::Success;
            } 
            else 
            {
                $status = StatusCodes::ErrGeneric;
            }

            return $status;
        }

        function editEmail($conn, $user_username, $new_email)
        {
            $sql = "UPDATE account SET email = ? WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $new_email, $user_username);
            $stmt->execute();
        }

        function editPassword($conn, $user_username, $new_pwd)
        {
            $sql = "UPDATE account SET password = ? WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $new_pwd, $user_username);
            $stmt->execute();
        }

        function redirectToListener()
        {
            header("Location: ../../frontend/listener/listener.php");
        }

        function purchaseMarketPriceShare($conn, $buyer, $artist, $buyer_new_balance, $artist_new_balance, $inital_pps, $new_pps, $buyer_new_share_amount, $shares_owned, $amount, $date_purchased, $time_purchased)
        {
            $status = 0;
            try {
                $conn->beginTransaction();

                $stmt = $conn->prepare("UPDATE account SET Shares = Shares + ? WHERE username = ?");
                $stmt->bindValue(1, $amount);
                $stmt->bindValue(2, $buyer);
                $stmt->execute(array($amount, $buyer));

                $stmt = $conn->prepare("UPDATE account SET Shares = Shares + ? WHERE username = ?");
                $stmt->bindValue(1, $amount);
                $stmt->bindValue(2, $artist);
                $stmt->execute(array($amount, $artist));

                $stmt = $conn->prepare("UPDATE account SET balance = '$buyer_new_balance' WHERE username = ?");
                $stmt->bindValue(1, $buyer);
                $stmt->execute(array($buyer));

                $stmt = $conn->prepare("UPDATE account SET balance = '$artist_new_balance' WHERE username = ?");
                $stmt->bindValue(1, $artist);
                $stmt->execute(array($artist));

                $stmt = $conn->prepare("UPDATE account SET price_per_share = '$new_pps' WHERE username = ?");
                $stmt->bindValue(1, $artist);
                $stmt->execute(array($artist));

                $stmt = $conn->prepare("INSERT INTO buy_history (user_username, seller_username, artist_username, no_of_share_bought, price_per_share_when_bought, date_purchased, time_purchased)
                                        VALUES(?, ?, ?, ?, ?, ?, ?)");
                $stmt->bindValue(1, $buyer);
                $stmt->bindValue(2, $artist);
                $stmt->bindValue(3, $artist);
                $stmt->bindValue(4, $buyer_new_share_amount);
                $stmt->bindValue(5, $inital_pps);
                $stmt->bindValue(6, $date_purchased);
                $stmt->bindValue(7, $time_purchased);
                $stmt->execute(array($buyer, $artist, $artist, $buyer_new_share_amount, $inital_pps, $date_purchased, $time_purchased));

                $search_conn = connect();
                $res = searchSharesInArtistShareHolders($search_conn, $buyer, $artist);
                //if the buyer has not invested in the artist, add a row
                if($res->num_rows == 0)
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
                    $current_share_amount = $res->fetch_assoc();
                    $new_share_amount = $current_share_amount['shares_owned'] + $amount;
                    $stmt = $conn->prepare("UPDATE artist_shareholders SET shares_owned = '$new_share_amount' WHERE user_username = ? AND artist_username = ?");
                    $stmt->bindValue(1, $buyer);
                    $stmt->bindValue(2, $artist);
                    $stmt->execute(array($buyer, $artist));
                }

                $conn->commit();
                $status = StatusCodes::Success;
            } catch (PDOException $e) {
                $conn->rollBack();
                echo "Failed: " . $e->getMessage();

                $status = StatusCodes::ErrGeneric;
            }

            return $status;
        }

        function purchaseAskedPriceShare($conn, $buyer, $seller, $artist, $buyer_new_balance, $seller_new_balance, $initial_pps, $new_pps, $buyer_new_share_amount, $seller_new_share_amount, $shares_owned, $amount, $price, $order_id, $date_purchased, $time_purchased, $indicator)
        {
            $status = 0;

            try {
                $conn->beginTransaction();

                $stmt = $conn->prepare("UPDATE account SET Shares = '$buyer_new_share_amount' WHERE username = ?");
                $stmt->bindValue(1, $buyer);
                $stmt->execute(array($buyer));

                //If the user is the seller, we want to decrease the total number of shares owned by the user
                //But if the user is an artist, we want to reduce the amount of shares that was bought back,
                //the total number of shares bought of that artist is still the same
                if($_SESSION['account_type'] == AccountType::User)
                {
                    $stmt = $conn->prepare("UPDATE account SET Shares = '$seller_new_share_amount' WHERE username = ?");
                    $stmt->bindValue(1, $seller);
                    $stmt->execute(array($seller));
                }
                else if($_SESSION['account_type'] == AccountType::Artist)
                {
                    $stmt = $conn->prepare("UPDATE account SET shares_repurchase = shares_repurchase - ? WHERE username = ?");
                    $stmt->bindValue(1, $amount);
                    $stmt->bindValue(2, $seller);
                    $stmt->execute(array($amount, $seller));
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

                $stmt = $conn->prepare("INSERT INTO buy_history (user_username, seller_username, artist_username, no_of_share_bought, price_per_share_when_bought, date_purchased, time_purchased)
                                        VALUES(?, ?, ?, ?, ?, ?, ?)");
                $stmt->bindValue(1, $buyer);
                $stmt->bindValue(2, $seller);
                $stmt->bindValue(3, $artist);
                $stmt->bindValue(4, $amount);
                $stmt->bindValue(5, $initial_pps);
                $stmt->bindValue(6, $date_purchased);
                $stmt->bindValue(7, $time_purchased);
                $stmt->execute(array($buyer, $seller, $artist, $amount, $initial_pps, $date_purchased, $time_purchased));

                $search_conn = connect();
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
                $new_share_amount_seller = $current_share_amount_seller['shares_owned'] - $amount;
                $stmt = $conn->prepare("UPDATE artist_shareholders SET shares_owned = '$new_share_amount_seller' WHERE user_username = ? AND artist_username = ?");
                $stmt->bindValue(1, $seller);
                $stmt->bindValue(2, $artist);
                $stmt->execute(array($seller, $artist));
                
                if($indicator == "AUTO_PURCHASE")
                {
                    $stmt = $conn->prepare("UPDATE sell_order SET no_of_share = no_of_share - ? WHERE id = ?");
                    $stmt->bindValue(1, $amount);
                    $stmt->bindValue(2, $order_id);
                    $stmt->execute(array($amount, $order_id));
                }
                else if($indicator == "AUTO_SELL")
                {
                    $stmt = $conn->prepare("UPDATE buy_order SET quantity = quantity - ? WHERE id = ?");
                    $stmt->bindValue(1, $amount);
                    $stmt->bindValue(2, $order_id);
                    $stmt->execute(array($amount, $order_id));
                }

                $conn->commit();
                $status = StatusCodes::Success;
            } catch (PDOException $e) {
                $conn->rollBack();
                echo "Failed: " . $e->getMessage();

                $status = StatusCodes::ErrGeneric;
            }

            return $status;
        }

        function buyBackShares($conn, $artist_username, $seller_username, $buyer_new_balance, $seller_new_balance, $seller_new_share_amount, $buyer_new_share_amount, $initial_pps, $new_pps, $amount_bought, $sell_order_id, $selling_price, $date_purchased, $time_purchased)
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
                
                $stmt = $conn->prepare("UPDATE account SET price_per_share = '$new_pps' WHERE username = ?");
                $stmt->bindValue(1, $artist_username);
                $stmt->execute(array($artist_username));
                
                $stmt = $conn->prepare("UPDATE account SET shares_repurchase = shares_repurchase + ? WHERE username = ?");
                $stmt->bindValue(1, $amount_bought);
                $stmt->bindValue(2, $artist_username);
                $stmt->execute(array($amount_bought, $artist_username));
                
                $stmt = $conn->prepare("INSERT INTO buy_history (user_username, seller_username, artist_username, no_of_share_bought, price_per_share_when_bought, date_purchased, time_purchased)
                                        VALUES(?, ?, ?, ?, ?, ?, ?)");
                $stmt->bindValue(1, $artist_username);
                $stmt->bindValue(2, $seller_username);
                $stmt->bindValue(3, $artist_username);
                $stmt->bindValue(4, $amount_bought);
                $stmt->bindValue(5, $initial_pps);
                $stmt->bindValue(6, $date_purchased);
                $stmt->bindValue(7, $time_purchased);
                $stmt->execute(array($artist_username, $seller_username, $artist_username, $amount_bought, $initial_pps, $date_purchased, $time_purchased));

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
                $new_share_amount = $current_share_amount['shares_owned'] + $amount_bought;
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
            } catch (PDOException $e) {
                $conn->rollBack();
                echo "Failed: " . $e->getMessage();

                $status = StatusCodes::ErrGeneric;
            }

            return $status;
        }

        function updateExistedSellingShare($conn, $user_username, $artist_username, $quantity, $asked_price, $old_asked_price, $old_quantity)
        {
            $sql = "UPDATE sell_order SET no_of_share = '$quantity' WHERE user_username = '$user_username' AND artist_username = '$artist_username' AND selling_price = '$old_asked_price' AND no_of_share = '$old_quantity'";
            $conn->query($sql);

            $sql = "UPDATE sell_order SET selling_price = '$asked_price' WHERE user_username = '$user_username' AND artist_username = '$artist_username' AND selling_price = '$old_asked_price' AND no_of_share = '$old_quantity'";
            $conn->query($sql);
        }

        function adjustExistedAskedPriceQuantity($conn, $user_username, $artist_username, $asked_price, $new_quantity, $new_date, $new_time)
        {
            $status = 0;
            $sql = "UPDATE sell_order SET no_of_share = '$new_quantity' WHERE user_username = '$user_username' AND artist_username = '$artist_username' AND selling_price = '$asked_price'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }
            else
            {
                $status = StatusCodes::ErrGeneric;
            }

            $sql = "UPDATE sell_order SET date_posted = '$new_date' WHERE user_username = '$user_username' AND artist_username = '$artist_username' AND selling_price = '$asked_price'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }
            else
            {
                $status = StatusCodes::ErrGeneric;
            }

            $sql = "UPDATE sell_order SET time_posted = '$new_time' WHERE user_username = '$user_username' AND artist_username = '$artist_username' AND selling_price = '$asked_price'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }
            else
            {
                $status = StatusCodes::ErrGeneric;
            }
            return $status;
        }

        function updateBuyOrderQuantity($conn, $buy_order_id, $new_quantity)
        {
            $sql = "UPDATE buy_order SET quantity = '$new_quantity' WHERE id = '$buy_order_id'";
            $conn->query($sql);
        }

        function updateArtistShareholder($conn, $shareholder_username, $artist_username, $new_share_amount)
        {
            $status = 0;
            
            $sql = "UPDATE artist_shareholders SET shares_owned = '$new_share_amount' WHERE user_username = '$shareholder_username' AND artist_username = '$artist_username'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }
            else
            {
                $status = StatusCodes::ErrGeneric;
            }

            return $status;
        }

        function addToInjectionHistory($conn, $artist_username, $share_distributing, $comment, $date, $time)
        {
            $status = 0;
            $injection_id = 0;

            $res = getMaxInjectionID($conn);
            if($res->num_rows > 0)
            {
                $max_id = $res->fetch_assoc();
                $injection_id = $max_id["max_id"] + 1;
            }

            $sql = "INSERT INTO inject_history (id, artist_username, amount, date_injected, time_injected, comment)
                    VALUES(?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('isisss', $injection_id, $artist_username, $share_distributing, $date, $time, $comment);
            if($stmt->execute() == TRUE)
            {
                $status = StatusCodes::Success;
            }
            else
            {
                $status = StatusCodes::ErrGeneric;
            }
            return $status;
        }

        function addArtistShareholder($conn, $shareholder_username, $artist_username, $amount)
        {
            $sql = "INSERT INTO artist_shareholders (user_username, artist_username, shares_owned)
                    VALUES(?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssi', $shareholder_username, $artist_username, $amount);
            if($stmt->execute() == TRUE)
            {
                $status = StatusCodes::Success;
            }
            else
            {
                $status = StatusCodes::ErrGeneric;
            }
            return $status;
        }

        function postSellOrder($conn, $user_username, $artist_username, $quantity, $asked_price, $date_posted, $time_posted)
        {
            $status = 0;
            $sell_order_id = 0;

            $res = getMaxSellOrderID($conn);
            if($res->num_rows != 0)
            {
                $max_id = $res->fetch_assoc();
                $sell_order_id = $max_id['max_id'] + 1;
            }
            $sql = "INSERT INTO sell_order (id, user_username, artist_username, selling_price, no_of_share, date_posted, time_posted)
                    VALUES(?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('issddss', $sell_order_id, $user_username, $artist_username, $asked_price, $quantity, $date_posted, $time_posted);
            if($stmt->execute() == TRUE)
            {
                $status = StatusCodes::Success;
            }
            else
            {
                $status = StatusCodes::ErrGeneric;
            }
            return $status;
        }

        function postBuyOrder($conn, $user_username, $artist_username, $quantity, $request_price, $date_posted, $time_posted)
        {
            $buy_order_id = 0;

            $res = getMaxBuyOrderID($conn);
            if($res->num_rows != 0)
            {
                $max_id = $res->fetch_assoc();
                $buy_order_id = $max_id['max_id'] + 1;
            }
            $status = 0;
            $sql = "INSERT INTO buy_order (id, user_username, artist_username, quantity, siliqas_requested, date_posted, time_posted)
                    VALUES(?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('issidss', $buy_order_id, $user_username, $artist_username, $quantity, $request_price, $date_posted, $time_posted);
            if($stmt->execute() == TRUE)
            {
                $status = StatusCodes::Success;
            }
            else
            {
                $status = StatusCodes::ErrGeneric;
            }
            return $status;
        }

        function postCampaign($conn, $artist_username, $offering, $release_date, $release_time, $expiration_date, $expiration_time, $type, $minimum_ethos)
        {
            $campaign_id = 0;
            $status = 0;
            $eligible_participant = 0;
            $winner = NULL;

            $res = getMaxCampaignID($conn);
            if($res->num_rows != 0)
            {
                $max_id = $res->fetch_assoc();
                $campaign_id = $max_id['max_id'] + 1;
            }

            $sql = "INSERT INTO campaign (id, artist_username, offering, date_posted, time_posted, date_expires, time_expires, type, minimum_ethos, eligible_participants, winner)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('isssssssdis', $campaign_id, $artist_username, $offering, $release_date, $release_time, $expiration_date, $expiration_time, $type, $minimum_ethos, $eligible_participant, $winner);
            if($stmt->execute() == TRUE)
            {
                $status = "SUCCESS";
            }
            else
            {
                $status = "ERROR";
            }
            return $status;
        }

        function removeSellOrder($conn, $order_id)
        {
            $sql = "DELETE FROM sell_order WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $order_id);
            $stmt->execute();
        }

        function removeBuyOrder($conn, $buy_order_id)
        {
            $sql = "DELETE FROM buy_order WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $buy_order_id);
            $stmt->execute();
        }

        function removeUserArtistShareZeroTuples($conn, $user_username, $artist_username, $price_per_share_when_bought, $date_purchased, $time_purchased)
        {
            $sql = "DELETE FROM buy_history WHERE user_username = ? AND artist_username = ? AND price_per_share_when_bought = ? AND date_purchased = ? AND time_purchased = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssdss', $user_username, $artist_username, $price_per_share_when_bought, $date_purchased, $time_purchased);
            $stmt->execute();
        }
?>