<?php
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
            $sql = "SELECT Shares FROM account WHERE username = ?";
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
            // $sql = "SELECT selling_price AND user_username FROM user_artist_sell_share WHERE artist_username = ?";
            // $stmt = $conn->prepare($sql);
            // $stmt->bind_param('s', $artist_username);
            // $stmt->execute();
            // $result = $stmt->get_result();

            // $asked_prices = array();
            // while($row = $result->fetch_assoc())
            // {
            //     array_push($asked_prices, $row);
            // }

            // return $asked_prices;
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
            $notify = 0;
            $share_distributed = 0;
            if($type == 'artist')
                $price_per_share = 1;
            else
                $price_per_share = 0;
            $result = getMaxID($conn);
            $row = $result->fetch_assoc(); 
            $id = $row["max_id"] + 1;
            // $sql = "INSERT INTO account (username, password, account_type, id)
            //         VALUES('$username', '$password', '$type', '$id')";
            $sql = "INSERT INTO account (username, password, account_type, id, Shares, balance, rate, Share_Distributed, email, billing_address, Full_name, City, State, ZIP, Card_number, Transit_no, Inst_no, Account_no, Swift, price_per_share)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssiiddisssssssssssd', $username, $password, $type, $id, $num_of_shares, $balance, $rate, $share_distributed, $email, $billing_address, $full_name, $city, $state, $zip, $card_number, $transit_no, $inst_no, $account_no, $swift, $price_per_share);
            if ($stmt->execute() === TRUE) {
                $notify = 1;
            } else {
                $notify = 2;
            }
            return $notify;
        }

        function getMaxID($conn){
            $sql = "SELECT MAX(id) AS max_id FROM account";
            $result = mysqli_query($conn,$sql);
            
            return $result;
        }

        function getMaxSongID($conn){
            $sql = "SELECT MAX(id) AS max_id FROM song";
            $result = mysqli_query($conn,$sql);
            
            return $result;
        }

        function saveUserPaymentInfo($conn, $username, $full_name, $email, $address, $city, $state, $zip, $card_name, $card_number)
        {
            $sql = "UPDATE account SET Full_name = '$full_name', email='$email', billing_address='$address', City = '$city', State='$state', ZIP = '$zip', Card_number='$card_number' WHERE username='$username'";
            $conn->query($sql);
        }

        function purchaseSiliqas($conn, $username, $coins)
        {
            $coins = round($coins, 2);
            $notify = 0;
            $sql = "UPDATE account SET balance = balance + $coins WHERE username = '$username'";
            if ($conn->query($sql) === TRUE) {
                $notify = 1;
            } else {
                $notify = 2;
            }  
            return $notify;
        }
?>