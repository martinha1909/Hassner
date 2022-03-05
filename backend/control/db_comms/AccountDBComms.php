<?php
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

    function searchAccountType($conn, $type)
    {
        $sql = "SELECT * FROM account WHERE account_type = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $type);
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

    function searchArtistRepurchaseShares($conn, $artist_username)
    {
        $sql = "SELECT shares_repurchase FROM account WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $artist_username);
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
?>