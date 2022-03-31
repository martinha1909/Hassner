<?php

    define("HX_EXCLUDED_USERS", array(
                                    "JackCampbell", 
                                    "Al Lure", 
                                    "sloves", 
                                    "Calypso Inquisition", 
                                    "RandallKeaty"
                                ));
    function searchShareholdersByArtist($conn, $artist_username)
    {
        $sql = "SELECT artist_username, user_username FROM artist_shareholders WHERE artist_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $artist_username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function getUserTotalNumOfTrades($user_username): int
    {
        $conn = connect();
        $ret = 0;

        $res = searchUserTrades($conn, $user_username);
        if($res->num_rows > 0)
        {
            $ret = $res->num_rows;
        }

        return $ret;
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

    function calculateUserNetworth($user_username, $current_balance): float
    {
        $ret = $current_balance;
        $conn = connect();

        $res = searchUserInvestedArtists($conn, $user_username);
        if($res->num_rows > 0)
        {
            while($row = $res->fetch_assoc())
            {
                $artist_pps = searchArtistCurrentPricePerShare($conn, $row['artist_username'])->fetch_assoc()['price_per_share'];
                $ret += ($artist_pps * $row['shares_owned']);
            }
        }

        return $ret;
    }

    function printTopAccounts($view_full_board)
    {
        $conn = connect();
        $max = 3;
        $all_users = array();

        $res = searchAccountWithExclusions($conn, HX_EXCLUDED_USERS);
        while($row = $res->fetch_assoc())
        {
            $investor = new Investor();
            
            $investor->setBalance($row['balance']);
            $investor->setUsername($row['username']);
            $investor->setEmail($row['email']);
            $investor->setNetWorth(calculateUserNetworth($row['username'], $row['balance']));
            $investor->setNumOfTrades(getUserTotalNumOfTrades($row['username']));

            array_push($all_users, $investor);
        }

        Investor::sort($all_users, 0, sizeof($all_users) - 1, "Descending", "Net Worth");

        if($view_full_board == 1)
        {
            $max = sizeof($all_users);
        }

        for($i = 0; $i < $max; $i++)
        {
            $placement = $i + 1;
            echo $placement.". ".$all_users[$i]->getUsername().": $".$all_users[$i]->getNetWorth();
            echo '
                <b data-toggle="tooltip" 
                    title="trades: '.$all_users[$i]->getNumOfTrades().'&#013;email: '.$all_users[$i]->getEmail().'&#013;" 
                    class="tooltip-pointer text-white">ⓘ
                </b></br>
            ';
        }

        closeCon($conn);
    }
?>