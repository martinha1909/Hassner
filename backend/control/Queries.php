<?php
        include '../../backend/constants/StatusCodes.php';
        include '../../backend/constants/AccountTypes.php';
        include '../../backend/constants/GraphOption.php';
        
        //logs in with provided user info and password, then use SQL query to query database 
        //after qurerying return the result
        function login($conn, $username, $pwd, &$account_info) // done2
        {
            $ret = false;

            $sql = "SELECT * FROM account WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0)
            {
                $info = $result->fetch_assoc();
                $pwd_hash_str = $info['password'];
                if(password_verify($pwd, $pwd_hash_str))
                {
                    usleep ( rand(10,100000));
                    $ret = true;
                    $account_info = $info;
                }
            }

            return $ret;
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

        function searchSellHistoryByUserAndArtist($conn, $seller_username, $artist_username)
        {
            $sql = "SELECT id, seller_username, buyer_username, artist_username, amount_sold, price_sold, date_sold FROM sell_history WHERE seller_username = ? AND artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $seller_username, $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchAllSellOrdersZeroQuantity($conn)
        {
            $sql = "SELECT * FROM sell_order WHERE no_of_share <= 0";
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

        function searchAllBuyOrdersZeroQuantity($conn)
        {
            $sql = "SELECT * FROM buy_order WHERE quantity <= 0";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchUserBuyOrders($conn, $user_username)
        {
            $sql = "SELECT * FROM buy_order WHERE user_username = ? ORDER BY date_posted ASC";
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
            $sql = "SELECT shares_owned, user_username, artist_username FROM artist_shareholders WHERE artist_username = ?";
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
            $sql = "SELECT id, artist_username, offering, date_posted, date_expires, type, minimum_ethos, eligible_participants, winner, is_active FROM campaign WHERE artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchArtistActiveCampaignsID($conn, $artist_username)
        {
            $sql = "SELECT id FROM campaign WHERE artist_username = ? AND is_active = 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        } 

        function searchUserParticipatingCampaign($conn, $user_username)
        {
            $result = 0;

            $sql = "SELECT campaign_id FROM campaign_participant WHERE user_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $user_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchUserSpecificParticipatingCampaign($conn, $user_username, $campaign_id)
        {
            $result = 0;

            $sql = "SELECT campaign_id FROM campaign_participant WHERE user_username = ? AND campaign_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $user_username, $campaign_id);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchArtistCampaignsByExpDateNotEnough($conn, $artist_username, $user_owned_shares)
        {
            $sql = "SELECT id, artist_username, offering, date_posted, date_expires, type, minimum_ethos, eligible_participants, winner, is_active 
                    FROM campaign 
                    WHERE artist_username = ? AND minimum_ethos > ? AND is_active = 0
                    ORDER BY minimum_ethos ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $artist_username, $user_owned_shares);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchTrendingCampaign($conn)
        {
            $sql = "SELECT id, artist_username, offering, date_posted, date_expires, type, minimum_ethos, eligible_participants, winner, is_active
                    FROM campaign
                    ORDER BY eligible_participants DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchCampaignWinnerByArtist($conn, $artist_username)
        {
            $sql = "SELECT winner FROM campaign WHERE artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchUserWinningCampaigns($conn, $user_username)
        {
            $sql = "SELECT id, offering, type FROM campaign WHERE winner = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $user_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchCampaignEligibleParticipants($conn, $campaign_id)
        {
            $sql = "SELECT eligible_participants FROM campaign WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $campaign_id);
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
            $sql = "SELECT id, artist_username, offering, date_posted, date_expires, type, minimum_ethos, eligible_participants, winner, is_active FROM campaign WHERE type = ?";
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

        function getArtistShareHoldersInfoNonBuyBack($conn, $artist_username)
        {
            $sql = "SELECT * FROM artist_shareholders WHERE artist_username = ? AND user_username != ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $artist_username, $artist_username);
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

            $sql = "SELECT id, user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted FROM sell_order WHERE artist_username = ? ORDER BY date_posted ASC";
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

        function searchQuantityStopBuyOrders($conn, $user_username, $artist_username, $stop)
        {
            $result = 0;

            $sql = "SELECT id, quantity
                    FROM buy_order 
                    WHERE artist_username = ? AND user_username != ? AND siliqas_requested = -1 AND buy_stop <= ? AND buy_stop != -1
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssd', $artist_username, $user_username, $stop);
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

        function searchQuantityLimitBuyOrders($conn, $user_username, $artist_username, $limit)
        {
            $result = 0;

            $sql = "SELECT id, quantity
                    FROM buy_order 
                    WHERE artist_username = ? AND user_username != ? AND siliqas_requested = -1 AND buy_limit >= ?
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssd', $artist_username, $user_username, $limit);
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

        function searchStopOrderSharesSelling($conn, $user_username, $artist_username, $stop)
        {
            $result = 0;

            $sql = "SELECT no_of_share
                    FROM sell_order 
                    WHERE artist_username = ? AND user_username = ? AND selling_price = -1 AND sell_stop = ?
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssd', $artist_username, $user_username, $stop);
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

        function searchLimitOrderSharesSelling($conn, $user_username, $artist_username, $limit)
        {
            $result = 0;

            $sql = "SELECT no_of_share
                    FROM sell_order 
                    WHERE artist_username = ? AND user_username = ? AND selling_price = -1 AND sell_limit = ?
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssd', $artist_username, $user_username, $limit);
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

        function searchNumOfSharesNoLimitStopSellOrders($conn, $user_username, $artist_username, $market_price)
        {
            $result = 0;

            $sql = "SELECT id, no_of_share
                    FROM sell_order 
                    WHERE artist_username = ? AND user_username != ? AND selling_price = ? AND sell_limit = -1 AND sell_stop = -1
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssd', $artist_username, $user_username, $market_price);
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

        function searchQuantityNoLimitStopBuyOrders($conn, $user_username, $artist_username, $market_price)
        {
            $result = 0;

            $sql = "SELECT id, quantity
                    FROM buy_order 
                    WHERE artist_username = ? AND user_username != ? AND siliqas_requested = ? AND buy_limit = -1 AND buy_stop = -1
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssd', $artist_username, $user_username, $market_price);
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

        function searchNumOfSharesLimitSellOrders($conn, $user_username, $artist_username, $limit)
        {
            $result = 0;

            $sql = "SELECT id, no_of_share
                    FROM sell_order 
                    WHERE artist_username = ? AND user_username != ? AND (selling_price = -1 AND (sell_limit <= ?) AND sell_limit != -1)
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssd', $artist_username, $user_username, $limit);
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

        function searchNumOfSharesStopSellOrders($conn, $user_username, $artist_username, $stop)
        {
            $result = 0;

            $sql = "SELECT id, no_of_share
                    FROM sell_order 
                    WHERE artist_username = ? AND user_username != ? AND (selling_price = -1 AND sell_stop >= ?)
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssd', $artist_username, $user_username, $stop);
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

        function searchOlderSellOrders($conn, $user_username, $artist_username, $current_exe_date)
        {
            $result = 0;

            $sql = "SELECT id, user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted 
                    FROM sell_order 
                    WHERE artist_username = ? AND user_username != ? AND date_posted <= ?
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss', $artist_username, $user_username, $current_exe_date);
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

        function searchOlderBuyOrders($conn, $user_username, $artist_username, $current_exe_date)
        {
            $result = 0;

            $sql = "SELECT id, user_username, artist_username, quantity, siliqas_requested, buy_limit, buy_stop, date_posted 
                    FROM buy_order 
                    WHERE artist_username = ? AND user_username != ? AND date_posted <= ?
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss', $artist_username, $user_username, $current_exe_date);
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

        function searchMarketExeLimitStopSellOrders($conn, $artist_username, $market_price)
        {
            $result = 0;

            $sql = "SELECT id, user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted 
                    FROM sell_order 
                    WHERE artist_username = ? AND (selling_price = ? OR (selling_price = -1 AND (sell_stop >= ? OR (sell_limit <= ? AND sell_limit != -1))))
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sddd', $artist_username, $market_price, $market_price, $market_price);
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

        function searchMarketExeLimitStopBuyOrders($conn, $artist_username, $market_price)
        {
            $result = 0;

            $sql = "SELECT id, user_username, artist_username, quantity, siliqas_requested, buy_limit, buy_stop, date_posted 
                    FROM buy_order 
                    WHERE artist_username = ? AND (siliqas_requested = ? OR (siliqas_requested = -1 AND ((buy_stop <= ? AND buy_stop != -1) OR buy_limit >= ?)))
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sddd', $artist_username, $market_price, $market_price, $market_price);
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

        function searchMatchingSellOrderNoLimitStop($conn, $user_username, $artist_username, $market_price)
        {
            $result = 0;

            $sql = "SELECT id, user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted 
                    FROM sell_order 
                    WHERE artist_username = ? AND user_username != ? AND (sell_stop >= ? OR (sell_limit <= ? AND sell_limit > 0) OR selling_price = ?)
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssddd', $artist_username, $user_username, $market_price, $market_price, $market_price);
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

        function searchMatchingSellOrderLimitStop($conn, $user_username, $artist_username, $buy_limit, $buy_stop, $current_market_price, $include_market_orders)
        {
            $result = 0;
            $sql = "";

            if($include_market_orders)
            {
                $sql = "SELECT id, user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted 
                        FROM sell_order 
                        WHERE artist_username = ? AND user_username != ? AND ((selling_price = ? AND sell_limit = -1 AND sell_stop = -1) OR (sell_limit <= ? AND sell_limit != -1) OR (sell_stop >= ?))
                        ORDER BY date_posted ASC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssddd', $artist_username, $user_username, $current_market_price, $buy_limit, $buy_stop);
                if($stmt->execute() == true)
                {
                    $result = $stmt->get_result();
                }
                else
                {
                    hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
                }
            }
            else
            {
                $sql = "SELECT id, user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted 
                        FROM sell_order 
                        WHERE (artist_username = ? AND user_username != ?) AND ((selling_price = -1 AND (sell_limit < ? OR sell_limit = ?) AND sell_limit != -1) OR (selling_price = -1 AND (sell_stop > ? OR sell_stop = ?)))
                        ORDER BY date_posted ASC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssdddd', $artist_username, $user_username, $buy_limit, $buy_limit, $buy_stop, $buy_stop);
                if($stmt->execute() == true)
                {
                    $result = $stmt->get_result();
                }
                else
                {
                    hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
                }
            }

            return $result;
        }

        function searchMatchingBuyOrderLimitStop($conn, $seller_username, $artist_username, $sell_limit, $sell_stop, $current_market_price, $include_market_orders)
        {
            $result = 0;

            if($include_market_orders)
            {
                $sql = "SELECT id, user_username, artist_username, quantity, siliqas_requested, buy_limit, buy_stop, date_posted
                        FROM buy_order 
                        WHERE artist_username = ? AND user_username != ? AND ((siliqas_requested = ? AND buy_limit = -1 AND buy_stop = -1) OR (siliqas_requested = -1 AND buy_limit >= ?) OR (siliqas_requested = -1 AND buy_stop <= ? AND buy_stop != -1))
                        ORDER BY date_posted ASC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssddd', $artist_username, $seller_username, $current_market_price, $sell_limit, $sell_stop);
                if($stmt->execute() == true)
                {
                    $result = $stmt->get_result();
                }
                else
                {
                    hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
                }
            }
            else
            {
                $sql = "SELECT id, user_username, artist_username, quantity, siliqas_requested, buy_limit, buy_stop, date_posted 
                        FROM buy_order 
                        WHERE artist_username = ? AND user_username != ? AND ((siliqas_requested = -1 AND buy_limit >= ?) OR (siliqas_requested = -1 AND buy_stop <= ? AND buy_stop != -1))
                        ORDER BY date_posted ASC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssdd', $artist_username, $seller_username, $sell_limit, $sell_stop);
                if($stmt->execute() == true)
                {
                    $result = $stmt->get_result();
                }
                else
                {
                    hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
                }
            }

            return $result;
        }

        function searchMatchingSellOrderLimit($conn, $user_username, $artist_username, $limit, $market_price, $include_market_orders)
        {
            $result = 0;

            if($include_market_orders)
            {
                $sql = "SELECT id, user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted 
                        FROM sell_order 
                        WHERE artist_username = ? AND user_username != ? AND (selling_price = -1 AND sell_limit <= ? AND sell_limit != -1) OR (selling_price = ? AND sell_limit = -1 AND sell_stop = -1)
                        ORDER BY date_posted ASC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssdd', $artist_username, $user_username, $limit, $market_price);
                if($stmt->execute() == true)
                {
                    $result = $stmt->get_result();
                }
                else
                {
                    hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
                }
            }
            else
            {
                $sql = "SELECT id, user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted 
                        FROM sell_order 
                        WHERE artist_username = ? AND user_username != ? AND selling_price = -1 AND sell_limit <= ? AND sell_limit != -1
                        ORDER BY date_posted ASC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssd', $artist_username, $user_username, $limit);
                if($stmt->execute() == true)
                {
                    $result = $stmt->get_result();
                }
                else
                {
                    hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
                }
            }

            return $result;
        }

        function searchMatchingBuyOrderLimit($conn, $user_username, $artist_username, $limit, $market_price, $include_market_orders)
        {
            $result = 0;

            if($include_market_orders)
            {
                $sql = "SELECT id, user_username, artist_username, quantity, siliqas_requested, buy_limit, buy_stop, date_posted 
                        FROM buy_order 
                        WHERE artist_username = ? AND user_username != ? AND ((siliqas_requested = ? AND buy_limit = -1 AND buy_stop = -1) OR (siliqas_requested = -1 AND buy_limit >= ?))
                        ORDER BY date_posted ASC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssdd', $artist_username, $user_username, $market_price, $limit);
                if($stmt->execute() == true)
                {
                    $result = $stmt->get_result();
                }
                else
                {
                    hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
                }
            }
            else
            {
                $sql = "SELECT id, user_username, artist_username, quantity, siliqas_requested, buy_limit, buy_stop, date_posted 
                        FROM buy_order 
                        WHERE artist_username = ? AND user_username != ? AND siliqas_requested = -1 AND buy_limit >= ?
                        ORDER BY date_posted ASC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssd', $artist_username, $user_username, $limit);
                if($stmt->execute() == true)
                {
                    $result = $stmt->get_result();
                }
                else
                {
                    hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
                }
            }

            return $result;
        }

        function searchMatchingSellOrderStop($conn, $user_username, $artist_username, $stop, $market_price, $include_market_orders)
        {
            $result = 0;

            if($include_market_orders)
            {
                $sql = "SELECT id, user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted 
                        FROM sell_order 
                        WHERE artist_username = ? AND user_username != ? AND (selling_price = -1 AND sell_stop >= ?) OR (selling_price = ? AND sell_limit = -1 AND sell_stop = -1)
                        ORDER BY date_posted ASC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssdd', $artist_username, $user_username, $stop, $market_price);
                if($stmt->execute() == true)
                {
                    $result = $stmt->get_result();
                }
                else
                {
                    hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
                }
            }
            else
            {
                $sql = "SELECT id, user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted 
                        FROM sell_order 
                        WHERE artist_username = ? AND user_username != ? AND selling_price = -1 AND sell_stop >= ?
                        ORDER BY date_posted ASC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssd', $artist_username, $user_username, $stop);
                if($stmt->execute() == true)
                {
                    $result = $stmt->get_result();
                }
                else
                {
                    hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
                }
            }

            return $result;
        }

        function searchMatchingBuyOrderStop($conn, $user_username, $artist_username, $stop, $market_price, $include_market_orders)
        {
            $result = 0;

            if($include_market_orders)
            {
                $sql = "SELECT id, user_username, artist_username, quantity, siliqas_requested, buy_limit, buy_stop, date_posted 
                        FROM buy_order 
                        WHERE artist_username = ? AND user_username != ? AND ((siliqas_requested = ? AND buy_limit = -1 AND buy_stop = -1) OR (siliqas_requested = -1 AND buy_stop <= ? AND buy_stop != -1))
                        ORDER BY date_posted ASC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssdd', $artist_username, $user_username, $market_price, $stop);
                if($stmt->execute() == true)
                {
                    $result = $stmt->get_result();
                }
                else
                {
                    hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
                }
            }
            else
            {
                $sql = "SELECT id, user_username, artist_username, quantity, siliqas_requested, buy_limit, buy_stop, date_posted 
                        FROM buy_order 
                        WHERE artist_username = ? AND user_username != ? AND siliqas_requested = -1 AND buy_stop <= ? AND buy_stop != -1
                        ORDER BY date_posted ASC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssd', $artist_username, $user_username, $stop);
                if($stmt->execute() == true)
                {
                    $result = $stmt->get_result();
                }
                else
                {
                    hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
                }
            }

            return $result;
        }

        function searchMatchingBuyOrderNoLimitStop($conn, $user_username, $artist_username, $market_price)
        {
            $result = 0;

            $sql = "SELECT id, user_username, artist_username, quantity, siliqas_requested, buy_limit, buy_stop, date_posted 
                    FROM buy_order 
                    WHERE artist_username = ? AND user_username != ? AND ((buy_stop <= ? AND buy_stop > 0) OR buy_limit >= ? OR siliqas_requested = ?)
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssddd', $artist_username, $user_username, $market_price, $market_price, $market_price);
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

        function searchAllSellOrdersNoLimitStop($conn, $artist_username)
        {
            $result = 0;

            $sql = "SELECT id, user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted
                    FROM sell_order
                    WHERE artist_username = ? AND selling_price != -1 AND sell_limit = -1 AND sell_stop = -1";
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

        function searchAllBuyOrdersNoLimitStop($conn, $artist_username)
        {
            $result = 0;

            $sql = "SELECT id, user_username, siliqas_requested, quantity, buy_limit, buy_stop
                    FROM buy_order
                    WHERE artist_username = ? AND siliqas_requested != -1 AND buy_limit = -1 AND buy_stop = -1";
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

        function searchSellOrderFromRepurchase($conn, $artist_username)
        {
            $result = 0;

            $sql = "SELECT id, user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted
                    FROM sell_order
                    WHERE user_username = ? AND artist_username = ? AND is_from_injection = 0 AND sell_limit = -1 AND sell_stop = -1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $artist_username, $artist_username);
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

        function searchMaxIDSellOrdersNotFromUser($conn, $user_username, $artist_username)
        {
            $result = 0;

            $sql = "SELECT MAX(id) AS max_sell_order_id FROM sell_order WHERE artist_username = ? AND user_username != ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $artist_username, $user_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchMaxIDBuyOrdersNotFromUser($conn, $user_username, $artist_username)
        {
            $result = 0;

            $sql = "SELECT MAX(id) AS max_buy_order_id FROM buy_order WHERE artist_username = ? AND user_username != ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $artist_username, $user_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchMaxCampaignID($conn)
        {
            $ret = 0;

            $sql = "SELECT MAX(id) AS max_campaign_id FROM campaign";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            $ret = $result->fetch_assoc();

            return $ret['max_campaign_id'];
        }

        function searchCampaignByID($conn, $id)
        {
            $reult = 0;

            $sql = "SELECT * FROM campaign WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchCampaignActiveStatus($conn, $campaign_id)
        {
            $reult = 0;

            $sql = "SELECT is_active FROM campaign WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $campaign_id);
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
            $password = password_hash($password, PASSWORD_BCRYPT);
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
            $rate = 0;
            $num_of_shares = 0;
            $status = 0;
            $share_distributed = 0;
            $monthly_shareholder = 0;
            $income = 0;
            $market_cap = 0;
            $share_repurchase = 0;
            if($type == AccountType::Artist)
            {
                $price_per_share = 1;
                $balance = 0;
            }
            else
            {
                $price_per_share = 0;
                $balance = 100;
            }
            
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
                $stmt->bindParam(':billing_address', $billing_address, PDO::PARAM_STR);
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

        function decreaseCampaignEligibleParticipant($connPDO, $campaign_id, $reduce_number)
        {
            $status = StatusCodes::NONE;

            try
            {
                $connPDO->beginTransaction();

                $stmt = $connPDO->prepare("UPDATE campaign SET eligible_participants = eligible_participants - ? WHERE id = ?");
                $stmt->bindValue(1, $reduce_number);
                $stmt->bindValue(2, $campaign_id);
                $stmt->execute(array($reduce_number, $campaign_id));

                $connPDO->commit();
                $status = StatusCodes::Success;
            }
            catch (PDOException $e) 
            {
                $connPDO->rollBack();
                hx_error(HX::DB, "DB error occured: " . $e->getMessage());
                $status = StatusCodes::ErrGeneric;
            }

            return $status;
        }

        function increaseCampaignEligibleParticipant($connPDO, $campaign_id, $increase_number)
        {
            $status = StatusCodes::NONE;

            try
            {
                $connPDO->beginTransaction();

                $stmt = $connPDO->prepare("UPDATE campaign SET eligible_participants = eligible_participants + ? WHERE id = ?");
                $stmt->bindValue(1, $increase_number);
                $stmt->bindValue(2, $campaign_id);
                $stmt->execute(array($increase_number, $campaign_id));

                $connPDO->commit();
                $status = StatusCodes::Success;
            }
            catch (PDOException $e) 
            {
                $connPDO->rollBack();
                hx_error(HX::DB, "DB error occured: " . $e->getMessage());
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

        function updateCampaignActiveStatus($conn, $campaign_id, $is_active)
        {
            $sql = "UPDATE campaign SET is_active = '$is_active' WHERE id='$campaign_id'";
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
            $new_pwd = password_hash($new_pwd, PASSWORD_BCRYPT);
            $sql = "UPDATE account SET password = ? WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $new_pwd, $user_username);
            $stmt->execute();
        }

        function redirectToListener()
        {
            header("Location: ../../frontend/listener/Listener.php");
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
                $stmt->bindValue(5, $new_pps);
                $stmt->bindValue(6, $date_purchased);
                $stmt->execute(array($buyer, $seller, $artist, $amount, $new_pps, $date_purchased));

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

        function updateSellOrderPPS($new_pps, $sell_order_id)
        {
            $connPDO = connectPDO();
            $status = StatusCodes::NONE;

            try {
                $connPDO->beginTransaction();

                $stmt = $connPDO->prepare("UPDATE sell_order SET selling_price = ? WHERE id = ?");
                $stmt->bindValue(1, $new_pps);
                $stmt->bindValue(2, $sell_order_id);
                $stmt->execute(array($new_pps, $sell_order_id));

                $connPDO->commit();
                $status = StatusCodes::Success;
                hx_info(HX::SELL_ORDER, "sell order id ".$sell_order_id." updated selling price to ".$new_pps);
            } catch (PDOException $e) {
                $connPDO->rollBack();
                hx_error(HX::DB, "Failed: " . $e->getMessage());

                $status = StatusCodes::ErrGeneric;
            }

            return $status;
        }

        function updateBuyOrderPPS($new_pps, $buy_order_id)
        {
            $connPDO = connectPDO();
            $status = StatusCodes::NONE;

            try {
                $connPDO->beginTransaction();

                $stmt = $connPDO->prepare("UPDATE buy_order SET siliqas_requested = ? WHERE id = ?");
                $stmt->bindValue(1, $new_pps);
                $stmt->bindValue(2, $buy_order_id);
                $stmt->execute(array($new_pps, $buy_order_id));

                $connPDO->commit();
                $status = StatusCodes::Success;
                hx_info(HX::SELL_ORDER, "buy order id ".$buy_order_id." updated requesting price to ".$new_pps);
            } catch (PDOException $e) {
                $connPDO->rollBack();
                hx_error(HX::DB, "Failed: " . $e->getMessage());

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
            $status = StatusCodes::NONE;

            $sql = "UPDATE buy_order SET quantity = '$new_quantity' WHERE id = '$buy_order_id'";
            if($conn->query($sql) == true)
            {
                hx_info(HX::BUY_ORDER, "Updated quantity to ".$new_quantity." for buy order id ".$buy_order_id);
                $status = StatusCodes::Success;
            }
            else
            {
                hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
                $status = StatusCodes::ErrServer;
            }

            return $status;
        }

        function updateSellOrderNoOfShare($connPDO, $sell_order_id, $new_no_of_share)
        {
            try
            {
                $connPDO->beginTransaction();

                $stmt = $connPDO->prepare("UPDATE sell_order SET no_of_share = ? WHERE id = ?");
                $stmt->bindValue(1, $new_no_of_share);
                $stmt->bindValue(2, $sell_order_id);
                $stmt->execute(array($new_no_of_share, $sell_order_id));

                $connPDO->commit();
                hx_info(HX::SELL_ORDER, "sell order (id: ".$sell_order_id.") has updated no_of_share to ".$new_no_of_share."\n".
                                        "--------------------------------");
            }
            catch (PDOException $e)
            {
                $connPDO->rollBack();
                hx_error(HX::DB, "DB error occured: ".$e->getMessage());
                hx_error(HX::SELL_ORDER, "Failed to update sell order (id:".$sell_order_id.") to new quantity ".$new_no_of_share);
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

        function addToSellHistory($seller_username, $buyer_username, $artist_username, $amount_sold, $price_sold, $date_sold)
        {
            $connPDO = connectPDO();
            try
            {
                $connPDO->beginTransaction();

                $stmt = $connPDO->prepare("INSERT INTO sell_history (seller_username, buyer_username, artist_username, amount_sold, price_sold, date_sold)
                                           VALUES(?, ?, ?, ?, ?, ?)");
                $stmt->bindValue(1, $seller_username);
                $stmt->bindValue(2, $buyer_username);
                $stmt->bindValue(3, $artist_username);
                $stmt->bindValue(4, $amount_sold);
                $stmt->bindValue(5, $price_sold);
                $stmt->bindValue(6, $date_sold);
                $stmt->execute(array($seller_username, $buyer_username, $artist_username, $amount_sold, $price_sold, $date_sold));

                $connPDO->commit();
            }
            catch (PDOException $e) 
            {
                $connPDO->rollBack();
                hx_error(HX::DB, "Failed: " . $e->getMessage());
                echo "Failed: " . $e->getMessage()."\n";
            }
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

        function postSellOrder($connPDO, $user_username, $artist_username, $quantity, $asked_price, $sell_limit, $sell_stop, $date_posted, $is_from_injection)
        {
            if($is_from_injection)
            {
                $is_from_injection = 1;
            }
            else
            {
                $is_from_injection = 0;
            }
            $status = StatusCodes::NONE;

            try {
                $connPDO->beginTransaction();

                $stmt = $connPDO->prepare("INSERT INTO sell_order (user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted)
                                           VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bindValue(1, $user_username);
                $stmt->bindValue(2, $artist_username);
                $stmt->bindValue(3, $asked_price);
                $stmt->bindValue(4, $quantity);
                $stmt->bindValue(5, $sell_limit);
                $stmt->bindValue(6, $sell_stop);
                $stmt->bindValue(7, $is_from_injection);
                $stmt->bindValue(8, $date_posted);
                $stmt->execute(array($user_username, $artist_username, $asked_price, $quantity, $sell_limit, $sell_stop, $is_from_injection, $date_posted));
                
                $connPDO->commit();
                $status = StatusCodes::Success;
                hx_info(HX::BUY_SHARES, "Sell order posted by user ".$user_username);
            } catch (PDOException $e) {
                $connPDO->rollBack();
                hx_error(HX::DB, "Failed: " . $e->getMessage());

                $status = StatusCodes::ErrGeneric;
            }
            return $status;
        }

        function postBuyOrder($connPDO, $user_username, $artist_username, $quantity, $request_price, $buy_limit, $buy_stop, $date_posted)
        {
            $status = StatusCodes::NONE;

            try {
                $connPDO->beginTransaction();

                $stmt = $connPDO->prepare("INSERT INTO buy_order (user_username, artist_username, quantity, siliqas_requested, buy_limit, buy_stop, date_posted)
                                        VALUES(?, ?, ?, ?, ?, ?, ?)");
                $stmt->bindValue(1, $user_username);
                $stmt->bindValue(2, $artist_username);
                $stmt->bindValue(3, $quantity);
                $stmt->bindValue(4, $request_price);
                $stmt->bindValue(5, $buy_limit);
                $stmt->bindValue(6, $buy_stop);
                $stmt->bindValue(7, $date_posted);
                $stmt->execute(array($user_username, $artist_username, $quantity, $request_price, $buy_limit, $buy_stop, $date_posted));
                
                $connPDO->commit();
                $status = StatusCodes::Success;
                hx_info(HX::BUY_SHARES, "Buy order posted by user ".$user_username);
            } catch (PDOException $e) {
                $connPDO->rollBack();
                hx_error(HX::DB, "Failed: " . $e->getMessage());

                $status = StatusCodes::ErrGeneric;
            }
            return $status;
        }

        function postCampaign($conn, $artist_username, $offering, $release_date, $expiration_date, $type, $minimum_ethos)
        {
            $status = 0;
            $eligible_participant = 0;
            $winner = NULL;
            $is_active = 1;

            $sql = "INSERT INTO campaign (artist_username, offering, date_posted, date_expires, type, minimum_ethos, eligible_participants, winner, is_active)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssssdisi', $artist_username, $offering, $release_date, $expiration_date, $type, $minimum_ethos, $eligible_participant, $winner, $is_active);
            if($stmt->execute() == TRUE)
            {
                $status = StatusCodes::Success;
            }
            else
            {
                $status = StatusCodes::ErrServer;
            }

            return $status;
        }

        function addToCampaignParticipant($conn, $user_username, $campaign_id)
        {
            $status = StatusCodes::NONE;

            $sql = "INSERT INTO campaign_participant (user_username, campaign_id)
                    VALUES(?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $user_username, $campaign_id);
            if($stmt->execute() == true)
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

            hx_info(HX::SELL_ORDER, "sell order (id: ".$order_id.") has been removed\n".
                                    "--------------------------------");
        }

        function removeBuyOrder($conn, $buy_order_id)
        {
            $sql = "DELETE FROM buy_order WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $buy_order_id);
            if($stmt->execute() == true)
            {
                hx_info(HX::BUY_ORDER, "Buy order (id: ".$buy_order_id.") has been removed");
            }
            else
            {
                hx_info(HX::BUY_ORDER, "Failed to remove buy order (id: ".$buy_order_id.")");
            }
        }

        function removeUserArtistShareZeroTuples($conn, $user_username, $artist_username, $price_per_share_when_bought, $date_purchased, $time_purchased)
        {
            $sql = "DELETE FROM buy_history WHERE user_username = ? AND artist_username = ? AND price_per_share_when_bought = ? AND date_purchased = ? AND time_purchased = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssdss', $user_username, $artist_username, $price_per_share_when_bought, $date_purchased, $time_purchased);
            $stmt->execute();
        }

        function removeCampaignParticipant($conn, $user_username, $campaign_id)
        {
            $status = 0;

            $sql = "DELETE FROM campaign_participant WHERE user_username = ? AND campaign_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $user_username, $campaign_id);
            if($stmt->execute() == true)
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
?>