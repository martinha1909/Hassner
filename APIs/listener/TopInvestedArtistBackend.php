<?php
    function query_account($account_type)
    {
        $conn = connect();
        $result = searchAccountType($conn, $account_type);
        closeCon($conn);
        return $result;
    }

    function populateArray(&$all_shares, &$users, &$result)
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
                break;
            $price_per_share = getArtistPricePerShare($users[$i]);
            $rate = getArtistCurrentRate($users[$i]);
            echo '<tr><th scope="row">'.$id.'</th>
                        <td><input name = "artist_name" type = "submit" id="abc" style="border:1px transparent; background-color: transparent;" role="button" aria-pressed="true" value = "'.$users[$i].'"></td></td>
                        <td style="color: white">'.$all_shares[$i].'</td>
                        <td style="color: white">'.$price_per_share.'</td>';
            if($rate > 0)
                echo '<td class="increase">+'.$rate.'%</td></tr>';
            else if($rate == 0)
                echo '<td>'.$rate.'%</td></tr>';
            else
                echo '<td class="decrease">'.$rate.'%</td></tr>';       
            $id++;
        }        
    }

    // 
?>