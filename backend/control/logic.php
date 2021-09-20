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
            $sql = "SELECT * FROM user_artist_share WHERE user_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $user_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchSpecificInvestment($conn, $user_username, $invested_artist)
        {
            $sql = "SELECT no_of_share_bought FROM user_artist_share WHERE user_username = ? AND artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $user_username, $invested_artist);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchUserSellingShares($conn, $user_username)
        {
            $sql = "SELECT * FROM user_artist_sell_share WHERE user_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $user_username);
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
            $sql = "SELECT price_per_share_when_bought FROM user_artist_share WHERE user_username = ? AND artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $user_username, $invested_artist);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchArtistTotalSharesBought($conn, $artist_username)
        {
            $sql = "SELECT no_of_share_bought FROM user_artist_share WHERE artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchArtistHighestPrice($conn, $artist_username)
        {
            $sql = "SELECT MAX(selling_price) AS maximum FROM user_artist_sell_share WHERE artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchArtistLowestPrice($conn, $artist_username)
        {
            $sql = "SELECT MIN(selling_price) AS minimum FROM user_artist_sell_share WHERE artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function getArtistShareHolders($conn, $artist_username)
        {
            $sql = "SELECT user_username FROM user_artist_share WHERE artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function getArtistShareHoldersInfo($conn, $artist_username)
        {
            $sql = "SELECT * FROM user_artist_share WHERE artist_username = ?";
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

        function getAskedPrices($conn, $artist_username)
        {
            $sql = "SELECT * FROM user_artist_sell_share WHERE artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function getSpecificAskedPrice($conn, $user_username, $artist_username)
        {
            $sql = "SELECT * FROM user_artist_sell_share WHERE artist_username = ? AND user_username = ?";
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

        function getMaxID($conn){
            $sql = "SELECT MAX(id) AS max_id FROM account";
            $result = mysqli_query($conn,$sql);
            
            return $result;
        }

        function getMaxInjectionID($conn){
            $sql = "SELECT MAX(id) AS max_id FROM inject_history";
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

        function purchaseMarketPriceShare($conn, $buyer, $artist, $buyer_new_balance, $artist_new_balance, $inital_pps, $new_pps, $buyer_new_share_amount, $shares_owned, $amount)
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

            if($shares_owned == 0)
            {
                $sql = "INSERT INTO user_artist_share (user_username, artist_username, no_of_share_bought, price_per_share_when_bought)
                    VALUES(?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssid', $buyer, $artist, $buyer_new_share_amount, $inital_pps);
                if($stmt->execute() == TRUE)
                {
                    $status = StatusCodes::Success;
                }
                else
                {
                    $status = StatusCodes::ErrGeneric;
                    return $status;
                }
            }

            $sql = "UPDATE user_artist_share SET no_of_share_bought = '$buyer_new_share_amount' WHERE user_username = '$buyer' AND artist_username = '$artist'";
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

        function purchaseAskedPriceShare($conn, $buyer, $seller, $artist, $buyer_new_balance, $seller_new_balance, $initial_pps, $new_pps, $buyer_new_share_amount, $seller_new_share_amount, $shares_owned, $amount)
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

            if($shares_owned == 0)
            {
                $sql = "INSERT INTO user_artist_share (user_username, artist_username, no_of_share_bought, price_per_share_when_bought)
                    VALUES(?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssid', $buyer, $artist, $buyer_new_share_amount, $initial_pps);
                if($stmt->execute() == TRUE)
                {
                    $status = StatusCodes::Success;
                }
                else
                {
                    $status = StatusCodes::ErrGeneric;
                    return $status;
                }
            }

            $sql = "UPDATE user_artist_share SET no_of_share_bought = no_of_share_bought + '$amount' WHERE user_username = '$buyer' AND artist_username = '$artist'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }   
            else
            {
                $status = StatusCodes::ErrGeneric;
                return $status;
            }

            $sql = "UPDATE user_artist_share SET no_of_share_bought = no_of_share_bought - '$amount' WHERE user_username = '$seller' AND artist_username = '$artist'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }   
            else
            {
                $status = StatusCodes::ErrGeneric;
                return $status;
            }

            $sql = "UPDATE user_artist_sell_share SET no_of_share = no_of_share - $amount WHERE user_username = '$seller' AND artist_username = '$artist'";
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

        function buyBackShares($conn, $artist_username, $seller_username, $buyer_new_balance, $seller_new_balance, $seller_new_share_amount, $new_share_distributed, $new_artist_shares_bought, $new_pps, $amount_bought)
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

            $sql = "UPDATE user_artist_share SET no_of_share_bought = no_of_share_bought - '$amount_bought' WHERE user_username = '$seller_username' AND artist_username = '$artist_username'";
            if($conn->query($sql) == TRUE)
            {
                $status = StatusCodes::Success;
            }   
            else
            {
                $status = StatusCodes::ErrGeneric;
                return $status;
            }

            $sql = "UPDATE user_artist_sell_share SET no_of_share = no_of_share - '$amount_bought' WHERE user_username = '$seller_username' AND artist_username = '$artist_username'";
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
            $sql = "UPDATE user_artist_sell_share SET no_of_share = '$quantity' WHERE user_username = '$user_username' AND artist_username = '$artist_username' AND selling_price = '$old_asked_price' AND no_of_share = '$old_quantity'";
            $conn->query($sql);

            $sql = "UPDATE user_artist_sell_share SET selling_price = '$asked_price' WHERE user_username = '$user_username' AND artist_username = '$artist_username' AND selling_price = '$old_asked_price' AND no_of_share = '$old_quantity'";
            $conn->query($sql);
        }

        function adjustExistedAskedPriceQuantity($conn, $user_username, $artist_username, $asked_price, $new_quantity)
        {
            $status = 0;
            $sql = "UPDATE user_artist_sell_share SET no_of_share = '$new_quantity' WHERE user_username = '$user_username' AND artist_username = '$artist_username' AND selling_price = '$asked_price'";
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

        function insertUserArtistSellShareTuple($conn, $user_username, $artist_username, $quantity, $asked_price)
        {
            $status = 0;
            $sql = "INSERT INTO user_artist_sell_share (user_username, artist_username, selling_price, no_of_share)
                    VALUES(?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssdd', $user_username, $artist_username, $asked_price, $quantity);
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

        function removeUserArtistSellShareTuple($conn, $user_username, $artist_username, $selling_price, $no_of_share)
        {
            $sql = "DELETE FROM user_artist_sell_share WHERE user_username = ? AND artist_username = ? AND selling_price = ? AND no_of_share = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssdi', $user_username, $artist_username, $selling_price, $no_of_share);
            $stmt->execute();
        }

        function deleteShareTables($conn, $account_type, $username)
        {
            if($account_type == AccountType::Artist)
            {
                $sql = "DELETE FROM user_artist_sell_share WHERE artist_username = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $username);
                $stmt->execute();

                $sql = "DELETE FROM user_artist_share WHERE artist_username = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $username);
                $stmt->execute();
            }
            else if($account_type == AccountType::User)
            {
                $sql = "DELETE FROM user_artist_sell_share WHERE user_username = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $username);
                $stmt->execute();

                $sql = "DELETE FROM user_artist_share WHERE user_username = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $username);
                $stmt->execute();
            }
        }

        function deleteInjectionHistory($conn, $username)
        {
            $sql = "DELETE FROM inject_history WHERE artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $stmt->execute();
        }

        function cleanDatabase($conn)
        {
            $sql = "SELECT * FROM account";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while($row = $result->fetch_assoc())
            {
                $username = $row['username'];
                $account_type = $row['account_type'];

                $query = "UPDATE account SET Shares = 0 WHERE username='$username'";
                $conn->query($query);

                $query = "UPDATE account SET balance = 0 WHERE username='$username'";
                $conn->query($query);

                $query = "UPDATE account SET rate = 0 WHERE username='$username'";
                $conn->query($query);

                $query = "UPDATE account SET Share_Distributed = 0 WHERE username='$username'";
                $conn->query($query);

                $query = "UPDATE account SET price_per_share = 0 WHERE username='$username'";
                $conn->query($query);

                $query = "UPDATE account SET Monthly_shareholder = 0 WHERE username='$username'";
                $conn->query($query);

                $query = "UPDATE account SET Income = 0 WHERE username='$username'";
                $conn->query($query);

                $query = "UPDATE account SET Market_cap = 0 WHERE username='$username'";
                $conn->query($query);

                deleteShareTables($conn, $account_type, $username);
                deleteInjectionHistory($conn, $username);
            }
        }

        function deleteDatabase($conn)
        {
            $sql = "SELECT * FROM account";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while($row = $result->fetch_assoc())
            {
                $username = $row['username'];
                $account_type = $row['account_type'];

                deleteShareTables($conn, $account_type, $username);
                deleteInjectionHistory($conn, $username);

                $sql = "DELETE FROM account WHERE username = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $username);
                $stmt->execute();
            }

            $sql = "DROP TABLE user_artist_share";
            $conn->query($sql);

            $sql = "DROP TABLE user_artist_sell_share";
            $conn->query($sql);

            $sql = "DROP TABLE inject_history";
            $conn->query($sql);

            $sql = "DROP TABLE account";
            $conn->query($sql);
        }
?>