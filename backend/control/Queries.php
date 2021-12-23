<?php
        include '../../backend/constants/StatusCodes.php';
        include '../../backend/constants/AccountTypes.php';
        include '../../backend/constants/GraphOption.php';
        
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
            if($stmt->execute() == FALSE)
            {
                $msg = "Error occured: ".implode(":", $stmt->errorInfo());
                hx_error(HX::QUERY, "searchAccount failed to query");
            }
            $result = $stmt->get_result();
            return $result;
        }

        function getAccountTypeFromUsername($conn, $username)
        {
            $sql = "SELECT account_type FROM account WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $username);
            if($stmt->execute() == FALSE)
            {
                $msg = "Error occured: ".implode(":", $stmt->errorInfo());
                hx_error(HX::QUERY, "searchAccount failed to query");
            }
            $result = $stmt->get_result();
            return $result;
        }


        function searchArtist($conn, $artist_username)
        {
            $sql = "SELECT username FROM account WHERE username = ? AND account_type = 'artist'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchUserBalance($conn, $usernmae)
        {
            $sql = "SELECT balance FROM account WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $usernmae);
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

        function searchTicker($conn, $ticker)
        {
            try
            {
                $sql = "SELECT ticker FROM artist_account_data WHERE ticker = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $ticker);
                $stmt->execute();
                $result = $stmt->get_result();
                return $result;
            }
            catch(PDOException $e)
            {
                echo 'SQL error: ' + $e;
            }
            
        }

        function getAllTickers($conn)
        {
            $sql = "SELECT ticker FROM artist_account_data";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }

        function searchArtistTicker($conn, $artist_username)
        {
            $sql = "SELECT ticker FROM artist_account_data WHERE artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }

        function searchArtistByTicker($conn, $artist_ticker)
        {
            $sql = "SELECT artist_username FROM artist_account_data WHERE ticker = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_ticker);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }

        function getArtistPPSChange($conn, $artist_username, $option)
        {
            $result = 0;

            if($option == GraphOption::ONE_DAY)
            {
                $sql = "SELECT artist_username, price_per_share, time_recorded, date_recorded FROM artist_stock_change WHERE artist_username = ? ORDER BY time_recorded";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $artist_username);
                $stmt->execute();
                $result = $stmt->get_result();
            }
            else
            {
                $sql = "SELECT artist_username, price_per_share, time_recorded, date_recorded FROM artist_stock_change WHERE artist_username = ? ORDER BY date_recorded";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $artist_username);
                $stmt->execute();
                $result = $stmt->get_result();
            }

            return $result;
        }

        function getJSONDataWithinInterval($conn, $artist_username, $date_from, $date_to)
        {
            $sql = "SELECT artist_username, price_per_share, date_recorded FROM artist_stock_change WHERE artist_username = ? AND date_recorded >=? AND date_recorded <= ? ORDER BY date_recorded";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss', $artist_username, $date_from, $date_to);
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

        function searchArtistBuyBackShares($conn, $artist_username)
        {
            $sql = "SELECT no_of_share_bought, price_per_share_when_bought, date_purchased FROM buy_history WHERE user_username = ? AND artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $artist_username, $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchSharesBoughtFromArtist($conn, $artist_username)
        {
            $sql = "SELECT no_of_share_bought, price_per_share_when_bought, date_purchased FROM buy_history WHERE artist_username = ? ORDER BY date_purchased DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
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
            $resullt = 0;

            $sql = "SELECT * FROM sell_order WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            if($stmt->execute() == TRUE)
            {
                $result = $stmt->get_result();
            }
            else
            {
                $msg = "db error occured: ".$conn->mysqli_error($conn);
                hx_error(HX::DB, $msg);
            }

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
            $sql = "SELECT shares_owned FROM artist_shareholders WHERE artist_username = ?";
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
            $sql = "SELECT id, artist_username, offering, date_posted, date_expires, type, minimum_ethos, eligible_participants, winner FROM campaign WHERE artist_username = ?";
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
            $sql = "SELECT id, artist_username, offering, date_posted, date_expires, type, minimum_ethos, eligible_participants, winner FROM campaign WHERE type = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $campaign_type);
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

        function getInjectionHistory($conn, $artist_username)
        {
            $sql = "SELECT amount, comment, date_injected FROM inject_history WHERE artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchSellOrderByArtist($conn, $artist_username)
        {
            $result = 0;

            $sql = "SELECT id, user_username, artist_username, selling_price, no_of_share, is_from_injection, date_posted FROM sell_order WHERE artist_username = ? ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            if($stmt->execute() == true)
            {
                $result = $stmt->get_result();
            }
            else
            {
                hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
            }

            return $result;
        }

        function searchSellOrdersIDFromInjection($conn, $artist_username)
        {
            $result = 0;

            $sql = "SELECT id FROM sell_order WHERE artist_username = ? AND is_from_injection = 1 ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            if($stmt->execute() == true)
            {
                $result = $stmt->get_result();
            }
            else
            {
                hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
            }

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

        function searchFollowingArtist($conn, $user_username)
        {
            $sql = "SELECT artist_username FROM artist_followers WHERE user_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $user_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchSpecificFollow($conn, $user_username, $artist_username)
        {
            $sql = "SELECT artist_username, user_username FROM artist_followers WHERE user_username = ? AND artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $user_username, $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function signup($connPDO, $username, $password, $type, $email, $ticker)
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
            if($type == AccountType::Artist)
                $price_per_share = 1;
            else
                $price_per_share = 0;
            
            try 
            {
                $connPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $connPDO->beginTransaction();

                $sql = "INSERT INTO account (username, password, account_type, Shares, balance, rate, 
                                         Share_Distributed, email, billing_address, Full_name, City, State, ZIP, 
                                         Card_number, Transit_no, Inst_no, Account_no, Swift, price_per_share, 
                                         Monthly_shareholder, Income, Market_cap, shares_repurchase)
                    VALUES(:username, :password, :account_type, :Shares, :balance, :rate, :Shares_Distributed, :email, :billing_address, :Full_name, :City
                    , :State, :ZIP, :Card_number, :Transit_no, :Inst_no, :Account_no, :Swift, :price_per_share, :Monthly_shareholder, :Income, :Market_cap, :shares_repurchase)";


                $stmt = $connPDO->prepare($sql);
                $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                $stmt->bindParam(':account_type', $type, PDO::PARAM_STR);
                $stmt->bindParam(':Shares', $num_of_shares, PDO::PARAM_INT);
                $stmt->bindParam(':balance', $balance);
                $stmt->bindParam(':rate', $rate);
                $stmt->bindParam(':Shares_Distributed', $share_distributed, PDO::PARAM_INT);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':billing_address', $username, PDO::PARAM_STR);
                $stmt->bindParam(':Full_name', $full_name, PDO::PARAM_STR);
                $stmt->bindParam(':City', $city, PDO::PARAM_STR);
                $stmt->bindParam(':State', $state, PDO::PARAM_STR);
                $stmt->bindParam(':ZIP', $zip, PDO::PARAM_STR);
                $stmt->bindParam(':Card_number', $card_number, PDO::PARAM_STR);
                $stmt->bindParam(':Transit_no', $transit_no, PDO::PARAM_STR);
                $stmt->bindParam(':Inst_no', $inst_no, PDO::PARAM_STR);
                $stmt->bindParam(':Account_no', $account_no, PDO::PARAM_STR);
                $stmt->bindParam(':Swift', $swift, PDO::PARAM_STR);
                $stmt->bindParam(':price_per_share', $price_per_share);
                $stmt->bindParam(':Monthly_shareholder', $monthly_shareholder, PDO::PARAM_INT);
                $stmt->bindParam(':Income', $income);
                $stmt->bindParam(':Market_cap', $market_cap);
                $stmt->bindParam(':shares_repurchase', $share_repurchase, PDO::PARAM_INT);
                $stmt->execute();

                if($type == AccountType::Artist)
                {
                    $sql2 = "INSERT INTO artist_account_data (artist_username, ticker) VALUES (:artist_username, :ticker)";
                    $stmt2 = $connPDO->prepare($sql2);
                    $stmt2->bindParam(':artist_username', $username, PDO::PARAM_STR);
                    $stmt2->bindParam(':ticker', $ticker, PDO::PARAM_STR);
                    $stmt2->execute();
                }
                $connPDO->commit();
                $status = StatusCodes::Success;
            } 
            catch (Exception $e) 
            {
                $connPDO->rollBack();
                echo "SQL query failed: " . $e->getMessage();
                $status = StatusCodes::ErrGeneric;
            }

            return $status;
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

        function updateArtistMarketCap($conn, $artist_username, $market_cap)
        {
            try
            {
                $conn->beginTransaction();

                $stmt = $conn->prepare("UPDATE account SET Market_cap = ? WHERE username = ?");
                $stmt->bindValue(1, $market_cap);
                $stmt->bindValue(2, $artist_username);
                $stmt->execute(array($market_cap, $artist_username));

                $conn->commit();
            }
            catch (PDOException $e) 
            {
                $conn->rollBack();
                echo "Failed: " . $e->getMessage();
            }
        }

        function updateArtistPPS($conn, $artist_username, $new_pps)
        {
            $sql = "UPDATE account SET price_per_share = '$new_pps' WHERE username='$artist_username'";
            $conn->query($sql);
        }

        function updateShareDistributed($conn, $artist_username, $new_share_distributed, $added_shares, $comment, $date)
        {
            $sql = "UPDATE account SET Share_Distributed = '$new_share_distributed' WHERE username='$artist_username'";
            if($conn->query($sql) == true)
            {
                hx_info(HX::SHARES_INJECT, "artist ".$artist_username." just injected ".$added_shares." with comment: ".$comment);
                hx_debug(HX::SHARES_INJECT, "addToInjectionHistory params: ".json_encode(array(
                    "artist_username" => $artist_username, 
                    "share_distributing" => $added_shares, 
                    "comment" => $comment, 
                    "date" => $date
                )));
                addToInjectionHistory($conn, $artist_username, $added_shares, $comment, $date);
            }
            else
            {
                hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
            }

        }

        function saveUserPaymentInfo($conn, $username, $full_name, $email, $address, $city, $state, $zip, $card_name, $card_number)
        {
            $sql = "UPDATE account SET Full_name = '$full_name', email='$email', billing_address='$address', City = '$city', State='$state', ZIP = '$zip', Card_number='$card_number' WHERE username='$username'";
            if($conn->query($sql) == TRUE)
            {
                $msg = "user ".$_SESSION['username']." successfully stored payment info in db";
                hx_info(HX::CURRENCY, $msg);
            }
            else
            {
                $msg = "db error occured: ".$conn->mysqli_error($conn);
                hx_error(HX::DB, $msg);
            }
        }

        function saveUserAccountInfo($conn, $username, $transit_no, $inst_no, $account_no, $swift)
        {
            $sql = "UPDATE account SET Transit_no = '$transit_no', Inst_no = '$inst_no', Account_no = '$account_no', Swift = '$swift' WHERE username='$username'";
            if($conn->query($sql) == TRUE)
            {
                $msg = "user ".$_SESSION['username']." successfully stored banking info in db";
                hx_info(HX::CURRENCY, $msg);
            }
            else
            {
                $msg = "db error occured: ".$conn->mysqli_error($conn);
                hx_error(HX::DB, $msg);
            }
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
        function deposit($conn, $username, $usd)
        {
            $coins = round($usd, 2);
            $status = 0;
            $sql = "UPDATE account SET balance = balance + $coins WHERE username = '$username'";
            if ($conn->query($sql) === TRUE) 
            {
                $status = StatusCodes::Success;
            } 
            else 
            {
                $msg = "db error occured: ".$conn->mysqli_error($conn);
                hx_error(HX::DB, $msg);
                $status = StatusCodes::ErrGeneric;
            }  
            return $status;
        }

        function withdraw($conn, $username, $coins)
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
                $msg = "db error occured: ".$conn->mysqli_error($conn);
                hx_error(HX::DB, $msg);
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

        function purchaseAskedPriceShare($conn, $buyer, $seller, $buyer_account_type, $seller_account_type, $artist, $buyer_new_balance, $seller_new_balance, $initial_pps, $new_pps, $buyer_new_share_amount, $seller_new_share_amount, $shares_owned, $amount, $price, $order_id, $date_purchased, $indicator, $buy_mode)
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
                    $search_conn = connect();
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
                $stmt->bindValue(5, $initial_pps);
                $stmt->bindValue(6, $date_purchased);
                $stmt->execute(array($buyer, $seller, $artist, $amount, $initial_pps, $date_purchased));

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
                hx_info(HX::BUY_SHARES, "buyer ".$buyer." purchased ".$amount." shares from ".$seller." for $".$price);
            } catch (PDOException $e) {
                $conn->rollBack();
                hx_error(HX::DB, "Failed: " . $e->getMessage());

                $status = StatusCodes::ErrGeneric;
            }

            return $status;
        }

        function buyBackShares($conn, $artist_username, $seller_username, $buyer_new_balance, $seller_new_balance, $seller_new_share_amount, $buyer_new_share_amount, $initial_pps, $new_pps, $amount_bought, $sell_order_id, $selling_price, $date_purchased)
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
                
                $stmt = $conn->prepare("UPDATE account SET price_per_share = '$new_pps' WHERE username = ?");
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

                $msg = "Artist ".$artist_username." just bought back ".$amount_bought." shares from user ".$seller_username;
                hx_info(HX::BUY_SHARES, $msg);
            } catch (PDOException $e) {
                $conn->rollBack();
                $msg = "db error occured, reverting operation with error message: ".$e->getMessage();
                hx_error(HX::DB, $msg);

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

        function adjustExistedAskedPriceQuantity($conn, $user_username, $artist_username, $asked_price, $new_quantity, $new_date)
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
            return $status;
        }

        function updateBuyOrderQuantity($conn, $buy_order_id, $new_quantity)
        {
            $sql = "UPDATE buy_order SET quantity = '$new_quantity' WHERE id = '$buy_order_id'";
            if($conn->query($sql) == true)
            {
                hx_info(HX::BUY_ORDER, "Updated quantity to ".$new_quantity." for buy order id ".$buy_order_id);
            }
            else
            {
                hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
            }
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

        function addToInjectionHistory($conn, $artist_username, $share_distributing, $comment, $date)
        {
            $status = 0;
            $injection_id = 0;

            $sql = "INSERT INTO inject_history (artist_username, amount, comment, date_injected)
                    VALUES(?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('siss', $artist_username, $share_distributing, $comment, $date);
            if($stmt->execute() == TRUE)
            {
                $status = StatusCodes::Success;
            }
            else
            {
                $status = StatusCodes::ErrGeneric;
                hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
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

        function postSellOrder($conn, $user_username, $artist_username, $quantity, $asked_price, $date_posted, $is_from_injection)
        {
            $status = 0;

            $sql = "INSERT INTO sell_order (user_username, artist_username, selling_price, no_of_share, is_from_injection, date_posted)
                    VALUES(?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssdiis', $user_username, $artist_username, $asked_price, $quantity, $is_from_injection, $date_posted);
            if($stmt->execute() == TRUE)
            {
                $status = StatusCodes::Success;
                $msg = "a sell order to sell shares for artist ".$artist_username." is posted";
                hx_info(HX::SELL_SHARES, $msg);
            }
            else
            {
                $status = StatusCodes::ErrGeneric;
                $msg = "db error occured: ".$conn->mysqli_error($conn);
                hx_error(HX::DB, $msg);
            }
            return $status;
        }

        function postBuyOrder($conn, $user_username, $artist_username, $quantity, $request_price, $date_posted)
        {
            $status = 0;
            $sql = "INSERT INTO buy_order (user_username, artist_username, quantity, siliqas_requested, date_posted)
                    VALUES(?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssids', $user_username, $artist_username, $quantity, $request_price, $date_posted);
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

        function postCampaign($conn, $artist_username, $offering, $release_date, $expiration_date, $type, $minimum_ethos)
        {
            $status = 0;
            $eligible_participant = 0;
            $winner = NULL;

            $sql = "INSERT INTO campaign (artist_username, offering, date_posted, date_expires, type, minimum_ethos, eligible_participants, winner)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssssdis', $artist_username, $offering, $release_date, $expiration_date, $type, $minimum_ethos, $eligible_participant, $winner);
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

        function followArtist($conn, $user_username, $artist_username)
        {
            $sql = "INSERT INTO artist_followers (artist_username, user_username)
                    VALUES(?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $artist_username, $user_username);
            $stmt->execute();
        }

        function unFollowArtist($conn, $user_username, $followed_artist)
        {
            $sql = "DELETE FROM artist_followers WHERE artist_username = ? AND user_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $followed_artist, $user_username);
            $stmt->execute();
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