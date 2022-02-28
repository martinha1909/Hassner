<?php

    function searchShareholdersByArtist($conn, $artist_username)
    {
        $sql = "SELECT artist_username, user_username FROM artist_shareholders WHERE artist_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $artist_username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function deleteInjectionHistory($conn, $username)
    {
        $sql = "DELETE FROM inject_history WHERE artist_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
    }

    function deleteShareTables($conn, $account_type, $username)
    {
        if($account_type == AccountType::Artist)
        {
            $sql = "DELETE FROM sell_order WHERE artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $stmt->execute();

            $sql = "DELETE FROM buy_history WHERE artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $stmt->execute();
        }
        else if($account_type == AccountType::User)
        {
            $sql = "DELETE FROM sell_order WHERE user_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $stmt->execute();

            $sql = "DELETE FROM buy_order WHERE user_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $stmt->execute();

            $sql = "DELETE FROM buy_history WHERE user_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $stmt->execute();
        }
    }

    function deleteCampaigns($conn, $username)
    {
        $sql = "DELETE FROM campaign WHERE artist_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
    }

    function deleteArtistShareholders($conn, $username)
    {
        $res = searchShareholdersByArtist($conn, $username);
        if($res->num_rows > 0)
        {
            while($row = $res->fetch_assoc())
            {
                $sql = "DELETE FROM artist_shareholders WHERE artist_username = ? AND user_username = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ss', $username, $row['user_username']);
                $stmt->execute();
            }
        }
    }

    function deleteArtistAccountData($conn, $username)
    {
        $sql = "DELETE FROM artist_account_data WHERE artist_username = ?";
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

            $query = "UPDATE account SET shares_repurchase = 0 WHERE username='$username'";
            $conn->query($query);

            deleteShareTables($conn, $account_type, $username);
            deleteInjectionHistory($conn, $username);
            deleteCampaigns($conn, $username);
            deleteArtistShareholders($conn, $username);
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
            deleteCampaigns($conn, $username);
            deleteArtistShareholders($conn, $username);
            deleteArtistAccountData($conn, $username);

            $sql = "DELETE FROM account WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $stmt->execute();
        }

        $sql = "DROP TABLE buy_history";
        $conn->query($sql);

        $sql = "DROP TABLE sell_order";
        $conn->query($sql);

        $sql = "DROP TABLE buy_order";
        $conn->query($sql);

        $sql = "DROP TABLE inject_history";
        $conn->query($sql);

        $sql = "DROP TABLE campaign";
        $conn->query($sql);

        $sql = "DROP TABLE artist_shareholders";
        $conn->query($sql);

        $sql = "DROP TABLE artist_account_data";
        $conn->query($sql);

        $sql = "DROP TABLE account";
        $conn->query($sql);
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

    function populateUserBalance($conn, $balance)
    {
        $ret = 0;
        $res = searchAccountType($conn, "user");
        while($row = $res->fetch_assoc())
        {
            $ret = updateUserBalance($conn, $row['username'], $balance);
            if($ret == StatusCodes::ErrGeneric)
            {
                break;
            }
        }

        return $ret;
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

    function searchTicker($conn, $ticker)
    {
        $sql = "SELECT ticker FROM artist_account_data WHERE ticker = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $ticker);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    function signup($connPDO, $username, $password, $type, $email, $ticker)
        {
            $password = password_hash($password, PASSWORD_BCRYPT);
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
            $price_per_share = 1;
            
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
?>