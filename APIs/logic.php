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
        //queries in song table and searches for all tuples that matches the given songId
        //return the tuple of the song table if there is a matching tuple
?>