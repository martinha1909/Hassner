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
                                         Monthly_shareholder, Income, Market_cap)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssiiddisssssssssssdidd', $username, $password, $type, $id, $num_of_shares, 
                                                           $balance, $rate, $share_distributed, $email, 
                                                           $billing_address, $full_name, $city, $state, $zip, 
                                                           $card_number, $transit_no, $inst_no, $account_no, 
                                                           $swift, $price_per_share, $monthly_shareholder, 
                                                           $income, $market_cap);
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
            $sql = "UPDATE account SET Shares = '$buyer_new_share_amount' WHERE username = '$buyer'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }   
            else
            {
                $status = StatusCodes::ErrGeneric;
                return $status;
            }

            $sql = "UPDATE account SET Shares = Shares + '$amount' WHERE username = '$artist'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }   
            else
            {
                $status = StatusCodes::ErrGeneric;
                return $status;
            }

            $sql = "UPDATE account SET balance = '$buyer_new_balance' WHERE username = '$buyer'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }   
            else
            {
                $status = StatusCodes::ErrGeneric;
                return $status;
            }

            $sql = "UPDATE account SET balance = '$artist_new_balance' WHERE username = '$artist'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }   
            else
            {
                $status = StatusCodes::ErrGeneric;
                return $status;
            }

            $sql = "UPDATE account SET price_per_share = '$new_pps' WHERE username = '$artist'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }   
            else
            {
                $status = StatusCodes::ErrGeneric;
                return $status;
            }

            $sql = "INSERT INTO buy_history (user_username, seller_username, artist_username, no_of_share_bought, price_per_share_when_bought, date_purchased, time_purchased)
                    VALUES(?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssidss', $buyer, $artist, $artist, $buyer_new_share_amount, $inital_pps, $date_purchased, $time_purchased);
            if($stmt->execute() == TRUE)
            {
                $status = "SUCCESS";
            }
            else
            {
                $status = StatusCodes::ErrGeneric;
                return $status;
            }

            return $status;
        }

        function purchaseAskedPriceShare($conn, $buyer, $seller, $artist, $buyer_new_balance, $seller_new_balance, $initial_pps, $new_pps, $buyer_new_share_amount, $seller_new_share_amount, $shares_owned, $amount, $price, $sell_order_id, $date_purchased, $time_purchased)
        {
            $status = 0;
            $sql = "UPDATE account SET Shares = '$buyer_new_share_amount' WHERE username = '$buyer'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }   
            else
            {
                $status = StatusCodes::ErrGeneric;
                return $status;
            }

            $sql = "UPDATE account SET Shares = '$seller_new_share_amount' WHERE username = '$seller'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }   
            else
            {
                $status = StatusCodes::ErrGeneric;
                return $status;
            }

            $sql = "UPDATE account SET balance = '$buyer_new_balance' WHERE username = '$buyer'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }   
            else
            {
                $status = StatusCodes::ErrGeneric;
                return $status;
            }

            $sql = "UPDATE account SET balance = '$seller_new_balance' WHERE username = '$seller'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }   
            else
            {
                $status = StatusCodes::ErrGeneric;
                return $status;
            }

            $sql = "UPDATE account SET price_per_share = '$new_pps' WHERE username = '$artist'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }   
            else
            {
                $status = StatusCodes::ErrGeneric;
                return $status;
            }

            $sql = "INSERT INTO buy_history (user_username, seller_username, artist_username, no_of_share_bought, price_per_share_when_bought, date_purchased, time_purchased)
                    VALUES(?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssidss', $buyer, $seller, $artist, $amount, $initial_pps, $date_purchased, $time_purchased);
            if($stmt->execute() == TRUE)
            {
                $status = "SUCCESS";
            }
            else
            {
                $status = StatusCodes::ErrGeneric;
                return $status;
            }

            $res = searchSpecificInvestment($conn, $seller, $artist);
            $temp = $amount;
            while($row = $res->fetch_assoc())
            {
                $uname = $row['user_username'];
                $sname = $row['seller_username'];
                $aname = $row['artist_username'];
                $pps = $row['price_per_share_when_bought'];
                $d = $row['date_purchased'];
                $t = $row['time_purchased'];

                //Since there could be many rows of buy_history with the same user_username and artist_username,
                //we want to recursively check all of the tuples until amount is 0
                if($row['no_of_share_bought'] > $temp)
                {
                    $sql = "UPDATE buy_history SET no_of_share_bought = no_of_share_bought - '$temp' WHERE user_username = '$uname' AND artist_username = '$aname' AND seller_username = '$sname' AND date_purchased = '$d' AND time_purchased = '$t'";
                    if($conn->query($sql) == TRUE)
                    {
                        $status = "SUCCESS";
                    }   
                    else
                    {
                        $status = "ERROR";
                        return $status;
                    }
                    break;
                }
                else if($row['no_of_share_bought'] == $temp)
                {
                    $sql = "UPDATE buy_history SET no_of_share_bought = 0 WHERE user_username = '$uname' AND artist_username = '$aname' AND seller_username = '$sname' AND date_purchased = '$d' AND time_purchased = '$t'";
                    if($conn->query($sql) == TRUE)
                    {
                        $status = "SUCCESS";
                    }   
                    else
                    {
                        $status = "ERROR";
                        return $status;
                    }
                    break;
                }
                else
                {
                    $sql = "UPDATE buy_history SET no_of_share_bought = 0 WHERE user_username = '$uname' AND artist_username = '$aname' AND seller_username = '$sname' AND date_purchased = '$d' AND time_purchased = '$t'";
                    if($conn->query($sql) == TRUE)
                    {
                        $status = "SUCCESS";
                    }   
                    else
                    {
                        $status = "ERROR";
                        return $status;
                    }
                    
                    $temp -= $row['no_of_share_bought'];
                }
            }

            $sql = "UPDATE sell_order SET no_of_share = no_of_share - '$amount' WHERE id = '$sell_order_id'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }   
            else
            {
                $status = StatusCodes::ErrGeneric;
                return $status;
            }

            return $status;
        }

        function buyBackShares($conn, $artist_username, $seller_username, $buyer_new_balance, $seller_new_balance, $seller_new_share_amount, $new_share_distributed, $new_artist_shares_bought, $new_pps, $amount_bought, $selling_price)
        {
            $sql = "UPDATE account SET balance = '$buyer_new_balance' WHERE username = '$artist_username'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }   
            else
            {
                $status = StatusCodes::ErrGeneric;
                return $status;
            }

            $sql = "UPDATE account SET balance = '$seller_new_balance' WHERE username = '$artist_username'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }   
            else
            {
                $status = StatusCodes::ErrGeneric;
                return $status;
            }

            $sql = "UPDATE account SET Shares = '$seller_new_share_amount' WHERE username = '$seller_username'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }   
            else
            {
                $status = StatusCodes::ErrGeneric;
                return $status;
            }

            $sql = "UPDATE account SET Share_Distributed = '$new_share_distributed' WHERE username = '$artist_username'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }   
            else
            {
                $status = StatusCodes::ErrGeneric;
                return $status;
            }

            $sql = "UPDATE account SET Shares = '$new_artist_shares_bought' WHERE username = '$artist_username'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }   
            else
            {
                $status = StatusCodes::ErrGeneric;
                return $status;
            }

            $sql = "UPDATE account SET price_per_share = '$new_pps' WHERE username = '$artist_username'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }   
            else
            {
                $status = StatusCodes::ErrGeneric;
                return $status;
            }

            $sql = "UPDATE buy_history SET no_of_share_bought = no_of_share_bought - '$amount_bought' WHERE user_username = '$seller_username' AND artist_username = '$artist_username'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }   
            else
            {
                $status = StatusCodes::ErrGeneric;
                return $status;
            }

            $sql = "UPDATE sell_order SET no_of_share = no_of_share - '$amount_bought' WHERE user_username = '$seller_username' AND artist_username = '$artist_username' AND selling_price = '$selling_price'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }   
            else
            {
                $status = StatusCodes::ErrGeneric;
                return $status;
            }
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
                $status = "SUCCESS";
            }
            else
            {
                $status = "ERROR";
            }

            $sql = "UPDATE sell_order SET date_posted = '$new_date' WHERE user_username = '$user_username' AND artist_username = '$artist_username' AND selling_price = '$asked_price'";
            if($conn->query($sql) == TRUE)
            {
                $status = "SUCCESS";
            }
            else
            {
                $status = "ERROR";
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
                $status = "SUCCESS";
            }
            else
            {
                $status = "ERROR";
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