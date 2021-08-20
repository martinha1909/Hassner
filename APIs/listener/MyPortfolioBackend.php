<?php
    function queryInvestment($listener_username)
    {
        $conn = connect();
        $result = searchUsersInvestment($conn, $listener_username);
         
        return $result;
    }

    function populateVars(&$all_shares_bought, &$all_artists, &$artist_name, &$rate, &$all_profits, &$all_rates, &$all_price_per_share, &$result)
    {
        $conn = connect();
        while($row = $result->fetch_assoc())
        {
            $artist_name = $row['artist_username'];
            $query = searchAccount($conn, $artist_name);
            $account_info = $query->fetch_assoc();
            array_push($all_shares_bought, $row['no_of_share_bought']);
            array_push($all_artists, $artist_name);
            $rate = $account_info['rate'];
            $rate = $rate * 100;
            $all_profits += $rate;
            array_push($all_rates, $rate);
            array_push($all_price_per_share, $account_info['price_per_share']);
        }
        
         
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

    //sort the columns of My Portfolio chart based on $target and $indicator of ascending or descending order
    function sortChart(&$all_artists, &$all_shares_bought, &$all_rates, &$all_price_per_share, $target, $indicator)
    {
        if($target == "Artist")
        {
            if($indicator = "Ascending")
            {
                insertionSort($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "Ascending");       
            }
            else
            {
                insertionSort($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "Descending");   
            }
        }
        else if($target == "PPS")
        {
            if($indicator = "Ascending")
            {
                insertionSort($all_price_per_share, $all_artists, $all_rates, $all_shares_bought, "Ascending");           
            }
            else
            {
                insertionSort($all_price_per_share, $all_artists, $all_rates, $all_shares_bought, "Descending");             
            }
        }
        else if($target == "Share")
        {
            if($indicator == "Ascending")
            {
                insertionSort($all_shares_bought, $all_artists, $all_rates, $all_price_per_share, "Ascending");         
            }
            else
            {
                insertionSort($all_shares_bought, $all_artists, $all_rates, $all_price_per_share, "Descending");  
            }
        }
        else if($target == "Rate")
        {
            if($indicator == "Ascending")
            {
                insertionSort($all_rates, $all_artists, $all_shares_bought, $all_price_per_share, "Ascending");  
            }
            else
            {
                insertionSort($all_rates, $all_artists, $all_shares_bought, $all_price_per_share, "Descending");
            }
        }
    }

    function printMyPortfolioChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share)
    {
        echo '<form action="../../APIs/artist/ArtistShareInfoBackend.php" method="post">';
        $id = 1;
        for($i=0; $i<sizeof($all_artists); $i++)
        {
            if($all_shares_bought[$i] != 0)
            {
                echo '<tr><th scope="row">'.$id.'</th><td><input name = "artist_name" type = "submit" id="abc" style="border:1px transparent; background-color: transparent;" role="button" aria-pressed="true" value = "'.$all_artists[$i].'"></td><td>'.$all_shares_bought[$i].'</td><td>'.$all_price_per_share[$i].'</td>';
                if($all_rates[$i] > 0)
                    echo '<td class="increase">+'.$all_rates[$i].'%</td></tr>';
                else if($all_rates[$i] == 0)
                    echo '<td>'.$all_rates[$i].'%</td></tr>';
                else
                    echo '<td class="decrease">'.$all_rates[$i].'%</td></tr>';
                $id++;
            }
        }
        echo '</form>';        
    }

    //retrieves from the database all the rows that contains all selling shares accrossed all artists of $user_username
    //If notices a row that has quantity of 0, simply just removes it from the database
    function fetchUserSellingShares($user_username, &$artist_usernames, &$profits, &$selling_prices, &$share_amounts)
    {
        $conn = connect();
        $result = searchUserSellingShares($conn, $user_username);
        while($row = $result->fetch_assoc())
        {
            if($row['no_of_share'] == 0)
            {
                removeUserArtistSellShareTuple($conn, $row['user_username'], $row['artist_username'], $row['selling_price'], $row['no_of_share']);
            }
            else
            {
                $result_2 = searchArtistCurrentPricePerShare($conn, $row['artist_username']);
                $pps = $result_2->fetch_assoc();
                $profit = (($row['selling_price'] - $pps['price_per_share'])/($pps['price_per_share']))*100;
                array_push($artist_usernames, $row['artist_username']);
                array_push($profits, round($profit, 2));
                array_push($selling_prices, $row['selling_price']);
                array_push($share_amounts, $row['no_of_share']);
            }
        }
        insertionSort($selling_prices, $artist_usernames, $profits, $share_amounts, "Descending");
    }

    //gets the total amount of share that the user holds corresponds to the $artist_username
    function getMaxShareQuantity($user_username, $artist_username)
    {
        $conn = connect();
        $result = searchSpecificInvestment($conn, $user_username, $artist_username);
        $amount = $result->fetch_assoc();
         
        return $amount['no_of_share_bought'];
    }
?>