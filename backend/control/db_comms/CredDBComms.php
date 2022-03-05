<?php
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
?>