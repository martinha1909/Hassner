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

    function totalShareDistributed($artist_username)
    {
        $conn = connect();
        $res = searchNumberOfShareDistributed($conn, $artist_username);

        $ret = $res->fetch_assoc();

        return $ret['Share_Distributed'];
    }

    //retrieves from the database all the rows that contains all selling shares accrossed all artists of $user_username
    //If notices a row that has quantity of 0, simply just removes it from the database
    function fetchSellOrders($user_username, &$artist_usernames, &$roi, &$selling_prices, &$share_amounts, &$profits, &$date_posted, &$time_posted, &$ids)
    {
        $conn = connect();
        $result = searchSellOrderByUser($conn, $user_username);
        while($row = $result->fetch_assoc())
        {
            if($row['no_of_share'] == 0)
            {
                removeSellOrder($conn, $row['id']);
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
                array_push($date_posted, $row['date_posted']);
                array_push($time_posted, $row['time_posted']);
                array_push($ids, $row['id']);
            }
        }
    }

    function fetchBuyOrders($user_username, &$artist_usernames, &$quantities_requested, &$siliqas_requested, &$date_posted, &$time_posted, &$buy_order_ids)
    {
        $conn = connect();
        $res = searchUserBuyOrders($conn, $user_username);
        while($row = $res->fetch_assoc())
        {
            if($row['quantity'] == 0)
            {
                removeBuyOrder($conn, 
                               $row['id']);
            }
            else
            {
                array_push($artist_usernames, $row['artist_username']);
                array_push($quantities_requested, $row['quantity']);
                array_push($siliqas_requested, $row['siliqas_requested']);
                array_push($date_posted, $row['date_posted']);
                array_push($time_posted, $row['time_posted']);
                array_push($buy_order_ids, $row['id']);
            }
        }
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

    function buyHistoryInit(&$sellers, &$prices, &$quantities, &$date_purchase, &$time_purchase, $username)
    {
        $conn = connect();

        $res = searchUsersInvestment($conn, $username);

        while($row = $res->fetch_assoc())
        {
            $date = dateParser($row['date_purchased']);
            $time = timeParser($row['time_purchased']);

            array_push($prices, $row['price_per_share_when_bought']);
            array_push($sellers, $row['seller_username']);
            array_push($quantities, $row['no_of_share_bought']);
            array_push($date_purchase, $date);
            array_push($time_purchase, $time);
        }
    }

    function autoPurchase($conn, $user_username, $artist_username, $request_quantity, $request_price)
    {
        $static_quantity_var = $request_quantity;

        $res = searchSellOrderByArtist($conn, $artist_username);
        while($row = $res->fetch_assoc())
        {
            if($request_quantity <= 0)
            {
                break;
            }
            //Skip your own sell order
            if($row['user_username'] == $user_username)
            {
                continue;
            }
            else
            {
                //Only auto purchase the orders that match the same artist's share that is being requested
                if($artist_username == $row['artist_username'])
                {
                    if($request_price == $row['selling_price'])
                    {
                        if($request_quantity >= $row['no_of_share'])
                        {
                            $current_date_time = getCurrentDate("America/Edmonton");
                            $date_parser = dayAndTimeSplitter($current_date_time);

                            $result = searchAccount($conn, $row['user_username']);
                            $seller_account_info = $result->fetch_assoc();

                            $res_1 = searchAccount($conn, $user_username);
                            $buyer_account_info = $res_1->fetch_assoc();

                            //if the user buys from the bid price, the siliqas will go to the other user since they are the seller
                            $seller_new_balance = $seller_account_info['balance'] + ($row['no_of_share'] * $row['selling_price']); 

                            //subtracts siliqas from the user
                            $buyer_new_balance = $buyer_account_info['balance'] - ($row['no_of_share'] * $row['selling_price']);

                            $seller_new_share_amount = $seller_account_info['Shares'] - $row['no_of_share'];
        
                            $buyer_new_share_amount = $buyer_account_info['Shares'] + $row['no_of_share'];

                            //In the case of buying in asked price, the new market price will become the last purchased price
                            $new_pps = $row['selling_price'];

                            purchaseAskedPriceShare($conn, 
                                                    $_SESSION['username'], 
                                                    $row['user_username'], 
                                                    $_SESSION['selected_artist'],
                                                    $buyer_new_balance, 
                                                    $seller_new_balance, 
                                                    $_SESSION['current_pps']['price_per_share'], 
                                                    $new_pps, 
                                                    $buyer_new_share_amount, 
                                                    $seller_new_share_amount,
                                                    $_SESSION['shares_owned'], 
                                                    $row['no_of_share'],
                                                    $row['selling_price'],
                                                    $row['id'],
                                                    $date_parser[0],
                                                    $date_parser[1]);

                            //The return value should be the amount of share requested subtracted by the amount that 
                            //is automatically bought
                            $request_quantity = $request_quantity - $row['no_of_share'];
                        }
                        else if($request_quantity < $row['no_of_share'])
                        {
                            $current_date_time = getCurrentDate("America/Edmonton");
                            $date_parser = dayAndTimeSplitter($current_date_time);

                            $result = searchAccount($conn, $row['user_username']);
                            $seller_account_info = $result->fetch_assoc();

                            $res_1 = searchAccount($conn, $user_username);
                            $buyer_account_info = $res_1->fetch_assoc();

                            //if the user buys from the bid price, the siliqas will go to the other user since they are the seller
                            $seller_new_balance = $seller_account_info['balance'] + ($request_quantity * $row['selling_price']); 

                            //subtracts siliqas from the user
                            $buyer_new_balance = $buyer_account_info['balance'] - ($request_quantity * $row['selling_price']);

                            $seller_new_share_amount = $seller_account_info['Shares'] - $request_quantity;
        
                            $buyer_new_share_amount = $buyer_account_info['Shares'] + $request_quantity;

                            //In the case of buying in asked price, the new market price will become the last purchased price
                            $new_pps = $row['selling_price'];

                            purchaseAskedPriceShare($conn, 
                                                    $_SESSION['username'], 
                                                    $row['user_username'], 
                                                    $_SESSION['selected_artist'],
                                                    $buyer_new_balance, 
                                                    $seller_new_balance, 
                                                    $_SESSION['current_pps']['price_per_share'], 
                                                    $new_pps, 
                                                    $buyer_new_share_amount, 
                                                    $seller_new_share_amount,
                                                    $_SESSION['shares_owned'], 
                                                    $request_quantity,
                                                    $row['selling_price'],
                                                    $row['id'],
                                                    $date_parser[0],
                                                    $date_parser[1]);

                            //The return value should be the amount of share requested subtracted by the amount that 
                            //is automatically bought
                            $request_quantity = $request_quantity - $row['no_of_share'];
                        }
                    }
                    //Skip the sell orders that do not meet the requested price
                    else
                    {
                        continue;
                    }
                }
            }
        }

        return $request_quantity;
    }

    function autoSell($user_username, $artist_username, $asked_price, $quantity)
    {
        $conn = connect();

        $res = searchBuyOrdersByArtist($conn, $artist_username);
        while($row = $res->fetch_assoc())
        {
            if($quantity <= 0)
            {
                break;
            }

            if($row['user_username'] == $user_username)
            {
                continue;
            }

            if($row['siliqas_requested'] == $asked_price)
            {
                //If the sell order is selling more shares than the posted buy order
                if($quantity >= $row['quantity'])
                {
                    $current_date_time = getCurrentDate("America/Edmonton");
                    $date_parser = dayAndTimeSplitter($current_date_time);

                    $result = searchAccount($conn, $user_username);
                    $account_info = $result->fetch_assoc();

                    //if the user buys from the bid price, the siliqas will go to the other user since they are the seller
                    $seller_new_balance = $account_info['balance'] + ($row['quantity'] * $asked_price); 

                    //subtracts siliqas from the user
                    $buyer_new_balance = $_SESSION['user_balance'] - (($row['quantity'] * $asked_price)); 

                    $seller_new_share_amount = $account_info['Shares'] - $row['quantity'];

                    $res_1 = searchAccount($conn, $row['user_username']);
                    $buyer_account_info = $res_1->fetch_assoc();
                    $buyer_new_share_amount = $buyer_account_info['Shares'] + $row['quantity'];

                    //In the case of buying in asked price, the new market price will become the last purchased price
                    $new_pps = $asked_price;

                    purchaseAskedPriceShare($conn, 
                                            $row['user_username'],
                                            $_SESSION['username'],
                                            $_SESSION['selected_artist'],
                                            $buyer_new_balance, 
                                            $seller_new_balance, 
                                            $_SESSION['current_pps']['price_per_share'], 
                                            $new_pps, 
                                            $buyer_new_share_amount, 
                                            $seller_new_share_amount,
                                            $_SESSION['shares_owned'], 
                                            $row['quantity'],
                                            $row['siliqas_requested'],
                                            $row['id'],
                                            $date_parser[0],
                                            $date_parser[1]);

                    updateBuyOrderQuantity($conn, $row['id'], 0);

                    //The return value should be the amount of share requested subtracted by the amount that 
                    //is automatically bought
                    $quantity = $quantity - $row['quantity'];
                }
                else if($quantity < $row['quantity'])
                {
                    $current_date_time = getCurrentDate("America/Edmonton");
                    $date_parser = dayAndTimeSplitter($current_date_time);

                    $result = searchAccount($conn, $user_username);
                    $account_info = $result->fetch_assoc();

                    //if the user buys from the bid price, the siliqas will go to the other user since they are the seller
                    $seller_new_balance = $account_info['balance'] + ($quantity * $asked_price); 

                    //subtracts siliqas from the user
                    $buyer_new_balance = $_SESSION['user_balance'] - (($quantity * $asked_price)); 

                    $seller_new_share_amount = $account_info['Shares'] - $quantity;

                    $res_1 = searchAccount($conn, $row['user_username']);
                    $buyer_account_info = $res_1->fetch_assoc();
                    $buyer_new_share_amount = $buyer_account_info['Shares'] + $quantity;

                    //In the case of buying in asked price, the new market price will become the last purchased price
                    $new_pps = $asked_price;

                    purchaseAskedPriceShare($conn, 
                                            $row['user_username'],
                                            $_SESSION['username'],
                                            $_SESSION['selected_artist'],
                                            $buyer_new_balance, 
                                            $seller_new_balance, 
                                            $_SESSION['current_pps']['price_per_share'], 
                                            $new_pps, 
                                            $buyer_new_share_amount, 
                                            $seller_new_share_amount,
                                            $_SESSION['shares_owned'], 
                                            $quantity,
                                            $row['siliqas_requested'],
                                            $row['id'],
                                            $date_parser[0],
                                            $date_parser[1]);

                    $new_buy_order_quantity = $row['quantity'] - $quantity;

                    updateBuyOrderQuantity($conn, $row['id'], $new_buy_order_quantity);

                    //The return value should be the amount of share requested subtracted by the amount that 
                    //is automatically bought
                    $quantity = $quantity - $row['quantity'];
                }
            }
        }
    }

    function refreshSellOrderTable()
    {
        $conn = connect();

        $res = searchAllSellOrders($conn);
        while($row = $res->fetch_assoc())
        {
            if($row['no_of_share'] <= 0)
            {
                removeSellOrder($conn, $row['id']);
            }
        }
    }

    function refreshBuyOrderTable()
    {
        $conn = connect();

        $res = searchAllBuyOrders($conn);
        while($row = $res->fetch_assoc())
        {
            if($row['quantity'] <= 0)
            {
                removeBuyOrder($conn, $row['id']);
            }
        }
    }
?>