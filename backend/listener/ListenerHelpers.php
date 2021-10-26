<?php
    function queryInvestment($listener_username)
    {
        $conn = connect();
        $result = searchUsersInvestment($conn, $listener_username);
         
        return $result;
    }

    function populateVars($user_username, &$all_artists, &$all_shares_bought, &$all_rates, &$all_price_per_share)
    {
        $no_of_shares_bought = 0;
        $conn = connect();

        //Gets all artists that the user has invested in
        $res = searchUserInvestedArtists($conn, $user_username);
        while($row = $res->fetch_assoc())
        {
            $res_pps = searchArtistCurrentPricePerShare($conn, $row['artist_username']);
            $current_pps = $res_pps->fetch_assoc();

            array_push($all_artists, $row['artist_username']);
            array_push($all_shares_bought, $row['shares_owned']);
            array_push($all_price_per_share, $current_pps['price_per_share']);
            //This is to calculate the change of artist's stock in the last 24 hours, 
            //will have a separate PR for this
            array_push($all_rates, 0);
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

    function totalShareDistributed($artist_username)
    {
        $conn = connect();
        $res = searchNumberOfShareDistributed($conn, $artist_username);

        $ret = $res->fetch_assoc();

        return $ret['Share_Distributed'];
    }

    function fetchBuyOrders($user_username, &$artist_usernames, &$quantities_requested, &$siliqas_requested, &$date_posted, &$buy_order_ids)
    {
        $current_date = getCurrentDate("America/Edmonton");
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
                $relative_time_posted = toRelativeTime($current_date, 
                                                       $row['date_posted'], 
                                                       $row['time_posted']);

                array_push($artist_usernames, $row['artist_username']);
                array_push($quantities_requested, $row['quantity']);
                array_push($siliqas_requested, $row['siliqas_requested']);
                array_push($date_posted, $relative_time_posted);
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
                                                $date_parser[1],
                                                "AUTO_PURCHASE");

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
                                                $date_parser[1],
                                                "AUTO_PURCHASE");

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

        return $request_quantity;
    }

    function getAllInvestedArtists($user_username)
    {
        $ret = array();
        $conn = connect();

        $res = searchUsersInvestment($conn, $user_username);
        while($row = $res->fetch_assoc()) {
            if(sizeof($ret) == 0) {
                array_push($ret, $row['artist_username']);
            } else if ($row['artist_username'] != $ret[sizeof($ret) - 1]) {
                array_push($ret, $row['artist_username']);
            }
        }

        return $ret;
    }

    function fetchInvestedArtistCampaigns($user_username, &$artists, &$offerings, &$progress, &$time_left, &$minimum_ethos, &$owned_ethos, &$types, &$chances)
    {
        $current_date = dayAndTimeSplitter(getCurrentDate("America/Edmonton"));
        $conn = connect();
        $all_artists = getAllInvestedArtists($user_username);

        for($i = 0; $i < sizeof($all_artists); $i++) {
            $total_shares_bought = calculateTotalNumberOfSharesBought($user_username, $all_artists[$i]);
            $res = searchArtistCampaigns($conn, $all_artists[$i]);
            while($row = $res->fetch_assoc()) {
                //assume not applicable
                $chance = -1;
                $res_1 = searchNumberOfShareDistributed($conn, $row['artist_username']);
                $artist_share_distributed = $res_1->fetch_assoc();
                if($row['date_expires'] != "Expired")
                {
                    if($total_shares_bought >= $row['minimum_ethos']) {
                        $progress_calc = 100;
                    } else {
                        $progress_calc = ($total_shares_bought/$row['minimum_ethos']) * 100;
                    }
                    $campaign_time_left = calculateTimeLeft($current_date[0], 
                                                            $current_date[1], 
                                                            $row['date_expires'], 
                                                            $row['time_expires']);
                    //If by the time of fetching and found a campaign has expired, mark the campaign in the db as expired
                    //so we don't come back to it on late fetches
                    if($campaign_time_left == "Expired")
                    {
                        $roll_res = "N/A";
                        if($row['type'] == "raffle")
                        {
                            $roll_res = getRaffleResult($conn, $row['id'], $artist_share_distributed['Share_Distributed']);
                        }
                        updateRaffleCampaignWinner($conn, $row['id'], $roll_res);
                        updateCampaignExpirationDate($conn, $row['id'], $campaign_time_left);
                    }
                    if($row['type'] == "raffle")
                    {
                        $chance = $total_shares_bought/$artist_share_distributed['Share_Distributed'] * 100;
                    }
                    array_push($artists, $row['artist_username']);
                    array_push($offerings, $row['offering']);
                    array_push($progress, $progress_calc);
                    array_push($time_left, $campaign_time_left);
                    array_push($minimum_ethos, $row['minimum_ethos']);
                    array_push($owned_ethos, $total_shares_bought);
                    array_push($types, $row['type']);
                    array_push($chances, $chance);
                }
            }
        }
    }

    function fetchParticipatedCampaigns($user_username, &$artists, &$offerings, &$minimum_ethos, &$winners, &$time_releases, &$types)
    {
        $conn = connect();
        $all_artists = getAllInvestedArtists($user_username);

        for($i = 0; $i < sizeof($all_artists); $i++) {
            $total_shares_bought = calculateTotalNumberOfSharesBought($user_username, $all_artists[$i]);
            $res = searchArtistCampaigns($conn, $all_artists[$i]);
            while($row = $res->fetch_assoc()) {
                if($row['date_expires'] == "Expired")
                {
                    $time_released = dateParser($row['date_posted'])." at ".timeParser($row['time_posted']);

                    array_push($artists, $row['artist_username']);
                    array_push($offerings, $row['offering']);
                    array_push($minimum_ethos, $row['minimum_ethos']);
                    array_push($winners, $row['winner']);
                    array_push($time_releases, $time_released);
                    array_push($types, $row['type']);
                }
            }
        }
    }

    function tradeHistoryInit($artist_username)
    {
        $conn = connect();

        if($_SESSION['trade_history_from'] == 0 || $_SESSION['trade_history_to'] == 0)
        {
            echo '
                <div class="col-6">
                    <h3 class="h3-blue py-2">Trade History</h3>
                    <form action="../../backend/shared/TradeHistoryRangeSwitcher.php" method="post">
                        <h6>From</h6>
                        <input type="date" name="trade_history_from">
                        <h6>To</h6>
                        <input type="date" name="trade_history_to">
                        <input type="submit" class="cursor-context" role="button" aria-pressed="true" value="->">
                    </form>
            ';
        }
        else
        {
            echo '
                <div class="col-6">
                    <h3 class="h3-blue py-2">Trade History</h3>
                    <form action="../../backend/shared/TradeHistoryRangeSwitcher.php" method="post">
                        <h6>From</h6>
                        <input type="date" name="trade_history_from" value="'.$_SESSION['trade_history_from'].'">
                        <h6>To</h6>
                        <input type="date" name="trade_history_to" value="'.$_SESSION['trade_history_to'].'">
                        <input type="submit" class="cursor-context" role="button" aria-pressed="true" value="->">
                    </form>
            ';
        }

        if($_SESSION['trade_history_from'] == 0 || $_SESSION['trade_history_to'] == 0)
        {
            echo '<p class="error-msg">Please choose a range</p>';
        }
        else
        {
            $date = explode("-", $_SESSION['trade_history_from']);
            //reformat to match the expectation of isInTheFuture, which is of form DD-MM-YYYY
            $from_date = array($date[2], $date[1], $date[0]);
            //We don't care about time 
            $time = "00:00:00";
            $from_time = explode(":", $time);
            $to_date = explode("-", $_SESSION['trade_history_to']);
            $time = "00:00";
            $to_time = explode(":", $time);
            if(!isInTheFuture($to_date, $from_date, $to_time, $from_time))
            {
                echo '<p class="error-msg">To date has to be later than from date</p>';
            }
            else
            {
                //Price displays the highest and lowest trades of the day
                //Volumn displays how many total shares of the artist that was traded that day
                //Value displays total amount of siliqas that was traded of the artist that day
                //Trades displays the total number of trades that day
                echo '
                            <div class="py-4">
                            <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Price(HIGH/LOW)</th>
                                    <th scope="col">Volume</th>
                                    <th scope="col">Value</th>
                                    <th scope="col">Trades</th>
                                </tr>
                            </thead>
                            <tbody>
                    </div>
                ';
                $res = searchSharesBoughtFromArtist($conn, $artist_username);
                $trade_history_list = populateTradeHistory($conn, $res);

                $trade_history_list->addListToTable();

                echo '
                            </tbody>
                        </table>
                        </div>
                ';
            }
        }
    }

    function getAllArtist()
    {
        $ret = array();
        $conn = connect();

        $res = searchAccountType($conn, "artist");
        while($row = $res->fetch_assoc())
        {
            $artist = new ArtistInfo();
            array_push($ret, $artist);
        }
    }

    //Stock Ticker temporary waiting for backend to fill out values
    // <input name = "artist_name" type = "submit" id="abc" style="border:1px transparent; background-color: transparent;" role="button" aria-pressed="true" value = "'.$all_artists[$i].'">
    function displayTicker()
    {
        $tickers = getAllArtistTickers();
        echo '
                <div class="card">
                    <div class="card-body text-dark">
                        <marquee direction="left">
                            <form action="../../backend/listener/TagToArtistShareInfoSwitcher.php" method = "post">
        ';
        for($i = 0; $i < sizeof($tickers); $i++)
        {
            echo '
                                <strong><input name = "artist_ticker" type = "submit" style="border:1px transparent; background-color: transparent; font-weight: bold;" aria-pressed="true" value ="'.$tickers[$i]->getTag().'"></strong> '.$tickers[$i]->getPPS().'
            ';
            
            if($tickers[$i]->getChange() < 0)
            {
                echo '
                                <mark class="markup-red">-'.$tickers[$i]->getChange().'%</mark>
                ';
            }
            else if($tickers[$i]->getChange() > 0)
            {
                echo '
                                <mark class="markup-green">+'.$tickers[$i]->getChange().'%</mark>
                ';
            }
            if($tickers[$i]->getChange() == 0)
            {
                echo '
                                <mark>'.$tickers[$i]->getChange().'%</mark>
                ';
            }

            echo " | ";
        }
        echo '
                            </form>
                        </marquee>
                    </div>
                </div>
        ';
    }
?>