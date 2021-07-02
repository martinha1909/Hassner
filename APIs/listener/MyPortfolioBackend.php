<?php
    function queryInvestment($listener_username)
    {
        $conn = connect();
        $result = searchUsersInvestment($conn, $listener_username);
        closeCon($conn);
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
        
        closeCon($conn);
    }

    function sortChart(&$all_artists, &$all_shares_bought, &$all_rates, &$all_price_per_share, $target, $indicator)
    {
        $i;
        $key;
        $key2;
        $j;
        if($target == "Artist")
        {
            if($indicator = "Ascending")
            {
                for($i=1; $i<sizeof($all_artists); $i++)
                {
                    $key = $all_artists[$i];
                    $key2 = $all_shares_bought[$i];
                    $key3 = $all_rates[$i];
                    $key4 = $all_price_per_share[$i];
                    $j = $i-1;
                    while($j >= 0 && $all_artists[$j] < $key)
                    {
                        $all_artists[($j+1)] = $all_artists[$j];
                        $all_shares_bought[($j+1)] = $all_shares_bought[$j];
                        $all_rates[($j+1)] = $all_rates[$j];
                        $all_price_per_share[($j+1)] = $all_price_per_share[$j];
                        $j = $j-1;
                    }
                    $all_artists[($j+1)] = $key;
                    $all_shares_bought[($j+1)] = $key2;
                    $all_rates[($j+1)] = $key3;
                    $all_price_per_share[($j+1)] = $key4;
                }        
            }
            else
            {
                for($i=1; $i<sizeof($all_artists); $i++)
                {
                    $key = $all_artists[$i];
                    $key2 = $all_shares_bought[$i];
                    $key3 = $all_rates[$i];
                    $key4 = $all_price_per_share[$i];
                    $j = $i-1;
                    while($j >= 0 && $all_artists[$j] > $key)
                    {
                        $all_artists[($j+1)] = $all_artists[$j];
                        $all_shares_bought[($j+1)] = $all_shares_bought[$j];
                        $all_rates[($j+1)] = $all_rates[$j];
                        $all_price_per_share[($j+1)] = $all_price_per_share[$j];
                        $j = $j-1;
                    }
                    $all_artists[($j+1)] = $key;
                    $all_shares_bought[($j+1)] = $key2;
                    $all_rates[($j+1)] = $key3;
                    $all_price_per_share[($j+1)] = $key4;
                }    
            }
        }
        else if($target == "PPS")
        {
            if($indicator = "Ascending")
            {
                for($i=1; $i<sizeof($all_price_per_share); $i++)
                {
                    $key = $all_price_per_share[$i];
                    $key2 = $all_artists[$i];
                    $key3 = $all_rates[$i];
                    $key4 = $all_shares_bought[$i];
                    $j = $i-1;
                    while($j >= 0 && $all_price_per_share[$j] < $key)
                    {
                        $all_price_per_share[($j+1)] = $all_price_per_share[$j];
                        $all_artists[($j+1)] = $all_artists[$j];
                        $all_rates[($j+1)] = $all_rates[$j];
                        $all_shares_bought[($j+1)] = $all_shares_bought[$j];
                        $j = $j-1;
                    }
                    $all_price_per_share[($j+1)] = $key;
                    $all_artists[($j+1)] = $key2;
                    $all_rates[($j+1)] = $key3;
                    $all_shares_bought[($j+1)] = $key4;
                }                
            }
            else
            {
                for($i=1; $i<sizeof($all_price_per_share); $i++)
                {
                    $key = $all_price_per_share[$i];
                    $key2 = $all_artists[$i];
                    $key3 = $all_rates[$i];
                    $key4 = $all_shares_bought[$i];
                    $j = $i-1;
                    while($j >= 0 && $all_price_per_share[$j] > $key)
                    {
                        $all_price_per_share[($j+1)] = $all_price_per_share[$j];
                        $all_artists[($j+1)] = $all_artists[$j];
                        $all_rates[($j+1)] = $all_rates[$j];
                        $all_shares_bought[($j+1)] = $all_shares_bought[$j];
                        $j = $j-1;
                    }
                    $all_price_per_share[($j+1)] = $key;
                    $all_artists[($j+1)] = $key2;
                    $all_rates[($j+1)] = $key3;
                    $all_shares_bought[($j+1)] = $key4;
                }             
            }
        }
        else if($target == "Share")
        {
            if($indicator == "Ascending")
            {
                for($i=1; $i<sizeof($all_shares_bought); $i++)
                {
                    $key = $all_shares_bought[$i];
                    $key2 = $all_artists[$i];
                    $key3 = $all_rates[$i];
                    $key4 = $all_price_per_share[$i];
                    $j = $i-1;
                    while($j >= 0 && $all_shares_bought[$j] < $key)
                    {
                        $all_shares_bought[($j+1)] = $all_shares_bought[$j];
                        $all_artists[($j+1)] = $all_artists[$j];
                        $all_rates[($j+1)] = $all_rates[$j];
                        $all_price_per_share[($j+1)] = $all_price_per_share[$j];
                        $j = $j-1;
                    }
                    $all_shares_bought[($j+1)] = $key;
                    $all_artists[($j+1)] = $key2;
                    $all_rates[($j+1)] = $key3;
                    $all_price_per_share[($j+1)] = $key4;
                }
            }
            else
            {
                for($i=1; $i<sizeof($all_shares_bought); $i++)
                {
                    $key = $all_shares_bought[$i];
                    $key2 = $all_artists[$i];
                    $key3 = $all_rates[$i];
                    $key4 = $all_price_per_share[$i];
                    $j = $i-1;
                    while($j >= 0 && $all_shares_bought[$j] > $key)
                    {
                        $all_shares_bought[($j+1)] = $all_shares_bought[$j];
                        $all_artists[($j+1)] = $all_artists[$j];
                        $all_rates[($j+1)] = $all_rates[$j];
                        $all_price_per_share[($j+1)] = $all_price_per_share[$j];
                        $j = $j-1;
                    }
                    $all_shares_bought[($j+1)] = $key;
                    $all_artists[($j+1)] = $key2;
                    $all_rates[($j+1)] = $key3;
                    $all_price_per_share[($j+1)] = $key4;
                }
            }
        }
        else if($target == "Rate")
        {
            if($indicator == "Ascending")
            {
                for($i=1; $i<sizeof($all_rates); $i++)
                {
                    $key = $all_rates[$i];
                    $key2 = $all_artists[$i];
                    $key3 = $all_shares_bought[$i];
                    $key4 = $all_price_per_share[$i];
                    $j = $i-1;
                    while($j >= 0 && $all_rates[$j] < $key)
                    {
                        $all_rates[($j+1)] = $all_rates[$j];
                        $all_artists[($j+1)] = $all_artists[$j];
                        $all_shares_bought[($j+1)] = $all_shares_bought[$j];
                        $all_price_per_share[($j+1)] = $all_price_per_share[$j];
                        $j = $j-1;
                    }
                    $all_rates[($j+1)] = $key;
                    $all_artists[($j+1)] = $key2;
                    $all_shares_bought[($j+1)] = $key3;
                    $all_price_per_share[($j+1)] = $key4;
                }
            }
            else
            {
                for($i=1; $i<sizeof($all_rates); $i++)
                {
                    $key = $all_rates[$i];
                    $key2 = $all_artists[$i];
                    $key3 = $all_shares_bought[$i];
                    $key4 = $all_price_per_share[$i];
                    $j = $i-1;
                    while($j >= 0 && $all_rates[$j] > $key)
                    {
                        $all_rates[($j+1)] = $all_rates[$j];
                        $all_artists[($j+1)] = $all_artists[$j];
                        $all_shares_bought[($j+1)] = $all_shares_bought[$j];
                        $all_price_per_share[($j+1)] = $all_price_per_share[$j];
                        $j = $j-1;
                    }
                    $all_rates[($j+1)] = $key;
                    $all_artists[($j+1)] = $key2;
                    $all_shares_bought[($j+1)] = $key3;
                    $all_price_per_share[($j+1)] = $key4;
                }
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
                echo '<tr><th scope="row">'.$id.'</th><td><input name = "artist_name['.$all_artists[$i].']" type = "submit" id="abc" style="border:1px transparent; background-color: transparent;" role="button" aria-pressed="true" value = "'.$all_artists[$i].'"></td><td>'.$all_shares_bought[$i].'</td><td>'.$all_price_per_share[$i].'</td>';
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
?>