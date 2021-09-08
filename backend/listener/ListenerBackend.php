<?php
    function queryInvestment($listener_username)
    {
        $conn = connect();
        $result = searchUsersInvestment($conn, $listener_username);
         
        return $result;
    }

    function populateVars(&$all_shares_bought, &$all_artists, &$artist_name, &$rate, &$all_profits, &$all_rates, &$all_price_per_share, &$result)
    {
        $no_of_shares_bought = 0;
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

    function combineDuplicateRows(&$all_artists, &$all_shares_bought, &$all_rates, &$all_price_per_share)
    {
        $counter = 0;
        $all_artists_simplified = array();
        $all_shares_bought_simplified = array();
        $all_rates_simplified = array();
        $all_price_per_share_simplified = array();

        //First index won't be duplicate, so always add the first index
        array_push($all_artists_simplified, $all_artists[0]);
        array_push($all_shares_bought_simplified, $all_shares_bought[0]);
        array_push($all_rates_simplified, $all_rates[0]);
        array_push($all_price_per_share_simplified, $all_price_per_share[0]);

        $no_of_shares_bought = 0;

        for($i = 1; $i < sizeof($all_artists); $i++)
        {
            if($all_artists[$i] == $all_artists[$i-1])
            {
                $no_of_shares_bought += $all_shares_bought[$i];
                if($i == (sizeof($all_artists) - 1))
                {
                    array_push($all_shares_bought_simplified, $no_of_shares_bought);
                }
                $counter++;
            }
            else
            {

                array_push($all_artists_simplified, $all_artists[$i]);
                if($counter != 0)
                {
                    array_push($all_shares_bought_simplified, $no_of_shares_bought);
                    $counter = 0;
                }
                $no_of_shares_bought = $all_shares_bought[$i];
                array_push($all_rates_simplified, $all_rates[$i]);
                array_push($all_price_per_share_simplified, $all_price_per_share[$i]);
            }
        }

        $all_artists = $all_artists_simplified;
        $all_shares_bought = $all_shares_bought_simplified;
        $all_rates = $all_rates_simplified;
        $all_price_per_share = $all_price_per_share_simplified;
    }

    function indexExisted(&$arr, $index_name)
    {
        if(sizeof($arr) == 0)
        {
            return false;
        }
        for($i = 0; $i < sizeof($arr); $i++)
        {
            if($arr[$i] == $index_name)
                return true;
        }

        return false;
        // if (sizeof($arr) === 0) return false;
        // $low = 0;
        // $high = sizeof($arr) - 1;
        // while ($low <= $high) 
        // {
        //     // compute middle index
        //     $mid = floor(($low + $high) / 2);

        //     // element found at mid
        //     if($arr[$mid] == $index_name) {
        //         return true;
        //     }

        //     if (strcmp($index_name, $arr[$mid]) < 0) {
        //         // search the left side of the array
        //         $high = $mid -1;
        //     }
        //     else {
        //         // search the right side of the array
        //         $low = $mid + 1;
        //     }
        // }
          
        // // If we reach here element x doesnt exist
        // return false;
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
        echo '<form action="../../backend/artist/ArtistShareInfoBackend.php" method="post">';
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
    function fetchUserSellingShares($user_username, &$artist_usernames, &$roi, &$selling_prices, &$share_amounts, &$profits)
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
                $_roi = (($row['selling_price'] - $pps['price_per_share'])/($pps['price_per_share']))*100;
                $profit = $row['selling_price'] - $pps['price_per_share'];
                array_push($artist_usernames, $row['artist_username']);
                array_push($roi, round($_roi, 2));
                array_push($selling_prices, $row['selling_price']);
                array_push($share_amounts, $row['no_of_share']);
                array_push($profits, $profit);
            }
        }
        insertionSort($selling_prices, $artist_usernames, $roi, $share_amounts, "Descending");
        singleSort($profits, "Descending");
    }

    //gets the total amount of share that the user holds corresponds to the $artist_username
    function getMaxShareQuantity($user_username, $artist_username)
    {
        $conn = connect();
        $result = searchSpecificInvestment($conn, $user_username, $artist_username);
        $amount = $result->fetch_assoc();
         
        return $amount['no_of_share_bought'];
    }
    function query_account($account_type)
    {
        $conn = connect();
        $result = searchAccountType($conn, $account_type);
         
        return $result;
    }

    function topInvestedArtistInit(&$all_shares, &$users, &$result)
    {
        // $row = $result->fetch_assoc();
        while($row = $result->fetch_assoc())
        {
            array_push($all_shares, $row['Shares']);
            array_push($users, $row['username']);
        }
    }

    function sortArrays(&$all_shares, &$users)
    {
        $i;
        $key;
        $key2;
        $j;
        for($i=1; $i<sizeof($all_shares); $i++)
        {
            $key = $all_shares[$i];
            $key2 = $users[$i];
            $j = $i-1;
            while($j >= 0 && $all_shares[$j] < $key)
            {
                $all_shares[($j+1)] = $all_shares[$j];
                $users[($j+1)] = $users[$j];
                $j = $j-1;
            }
            $all_shares[($j+1)] = $key;
            $users[($j+1)] = $key2;
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

    function getArtistPricePerShare($artist_username)
    {
        $conn = connect();
        $result = searchAccount($conn, $artist_username);
        $price_per_share = $result->fetch_assoc();
         
        return $price_per_share['price_per_share'];
    }

    function getArtistCurrentRate($artist_username)
    {
        $conn = connect();
        $result = searchAccount($conn, $artist_username);
        $rate = $result->fetch_assoc();
        $rate['rate'] = $rate['rate'] * 100;
         
        return $rate['rate'];
    }

    function printTopInvestedArtistChart($users, $all_shares)
    {
        $id = 1;
        for($i=0; $i<sizeof($all_shares); $i++)
        {
            if($id == 6)
            {
                break;
            }
            $price_per_share = getArtistPricePerShare($users[$i]);
            $rate = getArtistCurrentRate($users[$i]);
            $high = getHighestOrLowestPPS($users[$i], "MAX");
            $low = getHighestOrLowestPPS($users[$i], "MIN");
            echo '<tr><th scope="row">'.$id.'</th>
                        <td><input name = "artist_name" type = "submit" id="abc" style="border:1px transparent; background-color: transparent;" role="button" aria-pressed="true" value = "'.$users[$i].'"></td></td>
                        <td style="color: white">'.$all_shares[$i].'</td>
                        <td style="color: white">'.$price_per_share.'</td>';
            if($rate > 0)
            {
                echo '<td class="increase">+'.$rate.'%</td>';
            }
                
            else if($rate == 0){
                echo '<td>'.$rate.'%</td>';
            }
            else
            {
                echo '<td class="decrease">'.$rate.'%</td>';
            }
        
            // Highest and lowest sell prices
            echo '<td>'.$high.'</td>
                 <td>'.$low.'</td>
                 </tr>';
            $id++;
        }        
    }

    function buyHistoryInit($sellers, $prices, $date_purchase, $time_purchase, $username)
    {
        $conn = connect();

        $res = searchUsersInvestment($conn, $username);

        while($row = $res->fetch_assoc())
        {
            $date = dateParser($row['date_purchased']);
            $time = timeParser($row['time_purchased']);

            array_push($sellers, $row['seller_username']);
            array_push($date_purchase, $date);
            array_push($time_purchase, $time);
        }
    }
?>