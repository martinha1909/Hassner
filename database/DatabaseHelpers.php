<?php
    include '../backend/constants/AccountTypes.php';

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
            deleteCampaigns($conn, $username);
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

        $sql = "DROP TABLE account";
        $conn->query($sql);
    }
?>