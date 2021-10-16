<?php
    include '../../backend/constants/StatusCodes.php';
    include '../../backend/constants/Currency.php';
    include '../../backend/constants/LoggingModes.php';
    include '../../backend/constants/MenuOption.php';

    function hassnerInit()
    {   
        $_SESSION['dependencies'] = "FRONTEND";
        $_SESSION['coins'] = 0;
        $_SESSION['display'] = MenuOption::None;
        $_SESSION['sort_type'] = 0;
        $_SESSION['cad'] = 0;
        $_SESSION['edit'] = 0;
        $_SESSION['currency'] = 0;
        $_SESSION['btn_show'] = 0;
        $_SESSION['saved'] = 0;
        $_SESSION['buy_sell'] = 0;
        $_SESSION['buy_asked_price'] = 0;
        $_SESSION['buy_market_price'] = 0;
        $_SESSION['siliqas_or_fiat'] = 0;
        $_SESSION['share_distribute'] = 0;
        $_SESSION['buy_options'] = 0;
        $_SESSION['trade_history_from'] = 0;
        $_SESSION['trade_history_to'] = 0;
        $_SESSION['trade_history_type'] = 0;
        //conversion rate from CAD to Siliqas, 1 CAD = 0.95 Sililqas (brute force for now)
        $_SESSION['conversion_rate'] = -0.05;
        $_SESSION['current_date'] = getCurrentDate('America/Edmonton');
    }

    function getStatusMessage($err_msg, $suc_msg)
    {
        if($_SESSION['status'] == StatusCodes::ErrGeneric)
        {
            echo '<p class="error-msg">'.$err_msg.'</p>';
        }
        else if($_SESSION['status'] == StatusCodes::Success)
        {
            echo '<p class="suc-msg">'.$suc_msg.'</p>';
        }

        $_SESSION['status'] = 0;
        $_SESSION['logging_mode'] = LogModes::NONE;
    }

    function getAccount($username)
    {
        $conn = connect();
        $result = searchAccount($conn, $username);
        $account = $result->fetch_assoc();
         
        return $account;
    }
    
    function getUserBalance($user_username)
    {
        $conn = connect();
        $result = searchAccount($conn, $user_username);
        $balance = $result->fetch_assoc();     
        return $balance['balance'];   
    }

    function convertToSiliqas($amount, $conversion_rate, $currency_type)
    {
        $ret = $amount;
        $ret = $amount * (1 + $conversion_rate);
        if($currency_type == Currency::USD)
            $ret *= 1.25;
        else if($currency_type == Currency::EUR)
            $ret *= 1.47;

        return $ret;
    }

    function siliqasToFiat($amount, $conversion_rate, $currency_type)
    {
        $ret = $amount;
        $ret = $amount / (1 + $conversion_rate);
        if($currency_type == Currency::USD)
            $ret /= 1.25;
        else if($currency_type == Currency::EUR)
            $ret /= 1.47;

        return $ret;
    }

    function returnToMainPage()
    {
        if($_SESSION['account_type'] == AccountType::User)
        {
            header("Location: ../../frontend/listener/listener.php");
        }
        else if($_SESSION['account_type'] ==  AccountType::Artist)
        {
            header("Location: ../../frontend/artist/Artist.php");
        }
    }

    function hasEnoughSiliqas($amount_spending, $balance)
    {
        if($balance >= $amount_spending)
            return true;
        
        return false;
    }

    function calculateArtistAvailableShares($artist_name)
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
        if($indicator == "Ascending")
        {
            for($i=1; $i<sizeof($guide_arr); $i++)
            {
                $key = $guide_arr[$i];
                $key2 = $arr1[$i];
                $key3 = $arr2[$i];
                $key4 = $arr3[$i];
                $j = $i-1;
                while($j >= 0 && $guide_arr[$j] < $key)
                {
                    $guide_arr[($j+1)] = $guide_arr[$j];
                    $arr1[($j+1)] = $arr1[$j];
                    $arr2[($j+1)] = $arr2[$j];
                    $arr3[($j+1)] = $arr3[$j];
                    $j = $j-1;
                }
                $guide_arr[($j+1)] = $key;
                $arr1[($j+1)] = $key2;
                $arr2[($j+1)] = $key3;
                $arr3[($j+1)] = $key4;
            }                    
        }
        else
        {
            for($i=1; $i<sizeof($guide_arr); $i++)
            {
                $key = $guide_arr[$i];
                $key2 = $arr1[$i];
                $key3 = $arr2[$i];
                $key4 = $arr3[$i];
                $j = $i-1;
                while($j >= 0 && $guide_arr[$j] > $key)
                {
                    $guide_arr[($j+1)] = $guide_arr[$j];
                    $arr1[($j+1)] = $arr1[$j];
                    $arr2[($j+1)] = $arr2[$j];
                    $arr3[($j+1)] = $arr3[$j];
                    $j = $j-1;
                }
                $guide_arr[($j+1)] = $key;
                $arr1[($j+1)] = $key2;
                $arr2[($j+1)] = $key3;
                $arr3[($j+1)] = $key4;
            }                  
        }
    }

    function singleSort(&$arr, $indicator)
    {
        if($indicator == "Descending")
        {
            for ($i = 1; $i < sizeof($arr); $i++)
            {
                $key = $arr[$i];
                $j = $i-1;
            
                // Move elements of arr[0..i-1],
                // that are    greater than key, to
                // one position ahead of their
                // current position
                while ($j >= 0 && $arr[$j] > $key)
                {
                    $arr[$j + 1] = $arr[$j];
                    $j = $j - 1;
                }
                
                $arr[$j + 1] = $key;
            }
        }
        else
        {
            for ($i = 1; $i < sizeof($arr); $i++)
            {
                $key = $arr[$i];
                $j = $i-1;
            
                // Move elements of arr[0..i-1],
                // that are    greater than key, to
                // one position ahead of their
                // current position
                while ($j >= 0 && $arr[$j] < $key)
                {
                    $arr[$j + 1] = $arr[$j];
                    $j = $j - 1;
                }
                
                $arr[$j + 1] = $key;
            }
        }
    }
?>