<?php
    include '../../backend/constants/StatusCodes.php';
    include '../../backend/constants/Currency.php';
    include '../../backend/constants/LoggingModes.php';
    include '../../backend/constants/MenuOption.php';
    include '../../backend/constants/BalanceOption.php';
    include '../../backend/constants/EthosOption.php';
    include '../../backend/constants/GraphOption.php';
    include '../../backend/constants/Timezone.php';

    function hassnerInit()
    {
        date_default_timezone_set(Timezone::MST);
        //Set these to true to trigger logging, default to false as this makes log files very noisy
        $_SESSION['debug'] = false;
        $_SESSION['error'] = false;
        $_SESSION['info'] = false;
        //Set this to false to enable functionalities that are not available during testing phase
        $_SESSION['testing_phase'] = true;
        $_SESSION['dependencies'] = "FRONTEND";
        $_SESSION['display'] = MenuOption::None;
        $_SESSION['usd'] = 0;
        $_SESSION['edit'] = 0;
        $_SESSION['currency'] = 0;
        $_SESSION['saved'] = 0;
        $_SESSION['fiat_options'] = BalanceOption::NONE;
        $_SESSION['trade_history_from'] = 0;
        $_SESSION['trade_history_to'] = 0;
        $_SESSION['trade_history_type'] = 0;
        $_SESSION['artist_investor_amount_invested_sort'] = 0;
        $_SESSION['is_logged_in'] = false;
    }

    function displaySearchBar()
    {
        echo '
            <form id="search_artist" class="form-inline" action="../../backend/listener/SearchArtistSwitcher.php" method="post">
                <input id="submit_search_form" type="search" class="search-field" placeholder="Search for Artist(s)" name="artist_search"/>
                <input type="submit" class="div-hidden"/>
            </form>
        ';
    }

    function getStatusMessage($err_msg, $suc_msg)
    {
        if ($_SESSION['status'] == StatusCodes::ErrGeneric) {
            echo '<p class="error-msg">' . $err_msg . '</p>';
        } else if ($_SESSION['status'] == StatusCodes::Success) {
            echo '<p class="suc-msg">' . $suc_msg . '</p>';
        }

        $_SESSION['status'] = 0;
        $_SESSION['logging_mode'] = LogModes::NONE;
    }

    function isTestingPhase($username, $pwd)
    {
        $ret = false;
        $current_date = date('Y-m-d');
        $account_type = getAccountType($username);
        if($current_date >= "2022-03-01" || 
           $username == "martin" || 
           $username == "kai" || 
           $username == "daniel" ||
           $username == "riley" ||
           $username == "vitor" ||
           $account_type == AccountType::Artist)
        {
            $ret = true;
        }

        return $ret;
    }

    function showJSStatusMsg()
    {
        echo '
            <div class="div-hidden" id="js_status_msg">  
                <p id="js_msg"></p>
            </div>
        ';
    }

    function getAccount($username)
    {
        $conn = connect();
        $result = searchAccount($conn, $username);
        $msg = "searchAccount returned ".$result->num_rows." rows";
        hx_debug(HX::HELPER, $msg);

        $account = $result->fetch_assoc();
         
        return $account;
    }

    /**
    * Retrieves an account type based on a given username  
    *
    * @param  	username      username of any user type
    *
    * @return 	ret	          a string represented the account type of the user
    */
    function getAccountType($username)
    {
        $ret = "unable to determine account type";
        $conn = connect();

        $result = getAccountTypeFromUsername($conn, $username);
        hx_debug(HX::QUERY, "getAccountTypeFromUsername returned ".$result->num_rows." entries");
        if($result->num_rows > 0)
        {
            $account_info = $result->fetch_assoc();
            hx_debug(HX::QUERY, "account_info data: ".json_encode($account_info));
            $ret = $account_info['account_type'];
        }

        return $ret;
    }

    /**
    * Gets the amount of shares a user has invested in an artist  
    *
    * @param  	user_username      targetted user to receive amount of shares from
    *
    * @param  	artist_username    targetted artist that the user has invested in
    *
    * @return 	ret	               number of shares that the user has invested in the artist
    */
    function getShareInvestedInArtist($user_username, $artist_username)
    {
        $ret = 0;
        $conn = connect();

        $res = searchSharesInArtistShareHolders($conn, $user_username, $artist_username);
        hx_debug(HX::QUERY, "searchSharesInArtistShareHolders returned ".$res->num_rows." entries");
        if($res->num_rows > 0)
        {
            $shares_owned = $res->fetch_assoc();
            hx_debug(HX::QUERY, "shares_owned data: ".json_encode($shares_owned));
            $ret = $shares_owned['shares_owned'];
        }

        closeCon($conn);
        return $ret;
    }

    /**
    * Retrieves the total number of share distributed of an artist
    *
    * @param  	artist_username      username of an artist
    *
    * @return 	ret	                 the number of share distributed of an artist
    */
    function getArtistShareDistributed($artist_username): int
    {
        $ret = 0;
        $conn = connect();

        $res = searchNumberOfShareDistributed($conn, $artist_username);
        hx_debug(HX::QUERY, "searchNumberOfShareDistributed returned ".$res->num_rows." entries");
        if($res->num_rows > 0)
        {
            $share_distributed = $res->fetch_assoc();
            hx_debug(HX::QUERY, "share_distributed data: ".json_encode($share_distributed));
            $ret = $share_distributed['Share_Distributed'];
        }

        closeCon($conn);
        return $ret;
    }
    
    /**
    * Retrieves account balance of a specified user
    *
    * @param  	user_username	    username to retrieve account balance from
    *
    * @return 	ret	                account balance of the given user
    */
    function getUserBalance($user_username): float
    {
        $ret = 0;
        $conn = connect();

        $result = searchAccount($conn, $user_username);
        $msg = "searchAccount returned ".$result->num_rows." rows";
        hx_debug(HX::HELPER, $msg);

        $balance = $result->fetch_assoc();     
        hx_debug(HX::QUERY, "balance data: ".json_encode($balance));

        $ret = $balance['balance'];   

        return $ret;
    }

    function convertToSiliqas($amount, $conversion_rate, $currency_type)
    {
        $ret = $amount;
        $ret = $amount * (1 + $conversion_rate);
        if ($currency_type == Currency::USD)
            $ret *= 1.25;
        else if ($currency_type == Currency::EUR)
            $ret *= 1.47;

        return $ret;
    }

    function siliqasToFiat($amount, $conversion_rate, $currency_type)
    {
        $ret = $amount;
        $ret = $amount / (1 + $conversion_rate);
        if ($currency_type == Currency::USD)
            $ret /= 1.25;
        else if ($currency_type == Currency::EUR)
            $ret /= 1.47;

        return $ret;
    }

    function returnToMainPage()
    {
        if ($_SESSION['account_type'] == AccountType::User) {
            header("Location: ../../frontend/listener/Listener.php");
        } else if ($_SESSION['account_type'] ==  AccountType::Artist) {
            header("Location: ../../frontend/artist/Artist.php");
        }
    }

    function hasEnoughBalance($amount_spending, $balance)
    {
        if ($balance >= $amount_spending)
            return true;

        return false;
    }

    /**
    * Retrieves the number of available shares for purchase of a given artist
    *
    * @param  	artist_name	       artist username to retrieve amount of available shares from
    *
    *
    * @return 	ret	               amount of available shares the given artist
    */
    function calculateArtistAvailableShares($artist_name): int
    {
        $ret = 0;

        $conn = connect();

        $res = searchAccount($conn, $artist_name);
        $account_info = $res->fetch_assoc();

        $ret = $account_info['Share_Distributed'] - $account_info['Shares'];

        return $ret;
    }

    //performing insertionSort to targeted arrays with $indicator being either ascending or descending
    //guide_arr is the leading array to sort with indixes correspond to other array indices
    function insertionSort(&$guide_arr, &$arr1, &$arr2, &$arr3, $indicator)
    {
        $i;
        $key;
        $key2;
        $j;
        if ($indicator == "Ascending") {
            for ($i = 1; $i < sizeof($guide_arr); $i++) {
                $key = $guide_arr[$i];
                $key2 = $arr1[$i];
                $key3 = $arr2[$i];
                $key4 = $arr3[$i];
                $j = $i - 1;
                while ($j >= 0 && $guide_arr[$j] < $key) {
                    $guide_arr[($j + 1)] = $guide_arr[$j];
                    $arr1[($j + 1)] = $arr1[$j];
                    $arr2[($j + 1)] = $arr2[$j];
                    $arr3[($j + 1)] = $arr3[$j];
                    $j = $j - 1;
                }
                $guide_arr[($j + 1)] = $key;
                $arr1[($j + 1)] = $key2;
                $arr2[($j + 1)] = $key3;
                $arr3[($j + 1)] = $key4;
            }
        } else {
            for ($i = 1; $i < sizeof($guide_arr); $i++) {
                $key = $guide_arr[$i];
                $key2 = $arr1[$i];
                $key3 = $arr2[$i];
                $key4 = $arr3[$i];
                $j = $i - 1;
                while ($j >= 0 && $guide_arr[$j] > $key) {
                    $guide_arr[($j + 1)] = $guide_arr[$j];
                    $arr1[($j + 1)] = $arr1[$j];
                    $arr2[($j + 1)] = $arr2[$j];
                    $arr3[($j + 1)] = $arr3[$j];
                    $j = $j - 1;
                }
                $guide_arr[($j + 1)] = $key;
                $arr1[($j + 1)] = $key2;
                $arr2[($j + 1)] = $key3;
                $arr3[($j + 1)] = $key4;
            }
        }
    }

    function singleSort(&$arr, $indicator)
    {
        if ($indicator == "Descending") {
            for ($i = 1; $i < sizeof($arr); $i++) {
                $key = $arr[$i];
                $j = $i - 1;

                // Move elements of arr[0..i-1],
                // that are    greater than key, to
                // one position ahead of their
                // current position
                while ($j >= 0 && $arr[$j] > $key) {
                    $arr[$j + 1] = $arr[$j];
                    $j = $j - 1;
                }

                $arr[$j + 1] = $key;
            }
        } else {
            for ($i = 1; $i < sizeof($arr); $i++) {
                $key = $arr[$i];
                $j = $i - 1;

                // Move elements of arr[0..i-1],
                // that are    greater than key, to
                // one position ahead of their
                  // current position
              while ($j >= 0 && $arr[$j] < $key) {
                    $arr[$j + 1] = $arr[$j];
                    $j = $j - 1;
                }

                $arr[$j + 1] = $key;
            }
        }
    }
?>
