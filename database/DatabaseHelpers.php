<?php
    include '../backend/constants/AccountTypes.php';

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
            deleteArtistAccountData($conn, $username);
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
?>