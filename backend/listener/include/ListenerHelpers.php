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
            $change = getArtistDayChange($row['artist_username']);

            array_push($all_artists, $row['artist_username']);
            array_push($all_shares_bought, $row['shares_owned']);
            array_push($all_price_per_share, $current_pps['price_per_share']);
            //This is to calculate the change of artist's stock in the last 24 hours, 
            //will have a separate PR for this
            array_push($all_rates, $change);
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
                                                       explode(" ", $row['date_posted'])[0], 
                                                       explode(" ", $row['date_posted'])[1]);

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

    function getArtistCurrentRate($artist_username)
    {
        $conn = connect();
        $result = searchAccount($conn, $artist_username);
        $rate = $result->fetch_assoc();
        $rate['rate'] = $rate['rate'] * 100;
         
        return $rate['rate'];
    }

    function getArtistShareVolume($artist_username)
    {
        $ret = 0;
        $conn = connect();

        $res = searchArtistCurrentPricePerShare($conn, $artist_username);
        $volume = $res->fetch_assoc();
        $ret = $volume['price_per_share'];

        closeCon($conn);
        return $ret;
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

        for($i = 0; $i < sizeof($all_artists); $i++) 
        {
            $total_shares_bought = calculateTotalNumberOfSharesBought($user_username, $all_artists[$i]);
            $res = searchArtistCampaigns($conn, $all_artists[$i]);
            while($row = $res->fetch_assoc()) 
            {
                //assume not applicable
                $chance = -1;
                $res_1 = searchNumberOfShareDistributed($conn, $row['artist_username']);
                $artist_share_distributed = $res_1->fetch_assoc();
                if($row['date_expires'] != "0000-00-00 00:00:00")
                {
                    if($total_shares_bought >= $row['minimum_ethos']) 
                    {
                        $progress_calc = 100;
                    } 
                    else 
                    {
                        $progress_calc = ($total_shares_bought/$row['minimum_ethos']) * 100;
                    }
                    $date_expires = explode(" ", $row['date_expires'])[0];
                    $time_expires = substr(explode(" ", $row['date_expires'])[1], 0, 5);
                    $campaign_time_left = calculateTimeLeft($current_date[0], 
                                                            $current_date[1], 
                                                            $date_expires, 
                                                            $time_expires);
                    //If by the time of fetching and found a campaign has expired, mark the campaign in the db as expired
                    //so we don't come back to it on late fetches
                    if($campaign_time_left == "0000-00-00 00:00:00")
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
                if($row['date_expires'] == "0000-00-00 00:00:00")
                {
                    $time_released = dbDateTimeParser($row['date_posted']);

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
                <div class="mx-auto text-center py-2 col-8">
                    <h3 class="h3-blue">Trade History</h3>
                    <form class="form-inline" action="../../backend/shared/TradeHistoryRangeSwitcher.php" method="post">
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
                <div class="mx-auto text-center py-2 col-8">
                    <h3 class="h3-blue">Trade History</h3>
                    <form class="form-inline" action="../../backend/shared/TradeHistoryRangeSwitcher.php" method="post">
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
        if($res->num_rows > 0)
        {
            $shares_owned = $res->fetch_assoc();
            $ret = $shares_owned['shares_owned'];
        }

        closeCon($conn);
        return $ret;
    }

    /**
    * Determines if a user can create a buy order or not.  
    *
    * @param  	user_username      user that is trying to create a buy order
    *
    * @param  	artist_username    targetted artist that the buy order is requesting shares from
    *
    * @return 	ret	               true if the user can create a buy order, false otherwise
    */
    function canCreateBuyOrder($user_username, $artist_username)
    {
        $conn = connect();
        $ret = false;

        $artist_share_distributed = totalShareDistributed($artist_username);
        //Trivial case, if artist hasn't gone IPO then users can't create buy orders
        if($artist_share_distributed > 0)
        {
            $num_of_shares_invested = getShareInvestedInArtist($user_username, $artist_username);
            //Trivial case, if artist has gone IPO and user hasn't invested, then they can create a buy order
            if($num_of_shares_invested == 0)
            {
                $ret = true;
            }
            else
            {
                //If the user hasn't bought all shares of the artist, he can create the buy order
                if($artist_share_distributed > $num_of_shares_invested)
                {
                    $ret = true;
                }
            }
        }

        closeCon($conn);
        return $ret;
    }

    function getAllArtist()
    {
        $ret = array();
        $conn = connect();

        $res = searchAccountType($conn, "artist");
        $counter = 0;
        while($row = $res->fetch_assoc())
        {
            $res_ticker = searchArtistTicker($conn, $row['username']);
            $ticker = $res_ticker->fetch_assoc();

            //Changes in last 24 hours
            $change = getArtistDayChange($row['username']);

            $artist = new ArtistInfo();

            //only populate fields that we need to use in this case
            $artist->setUsername($row['username']);
            $artist->setMarketTag($ticker['ticker']);
            $artist->setDayChange($change);
            $artist->setMarketCap($row['Market_cap']);

            array_push($ret, $artist);
            $counter++;
        }

        return $ret;
    }

    function displayTicker()
    {
        $tickers = getAllArtistTickers();
        echo '
                <div>
                    <div class="marquee">
                            <form action="../../backend/listener/TagToArtistShareInfoSwitcher.php" method = "post">
                            <p>
        ';
        for($i = 0; $i < sizeof($tickers); $i++)
        {
            echo '
                                <input name = "artist_ticker" type = "submit" class="bold-ticker" aria-pressed="true" value ="'.$tickers[$i]->getTag().'"> '.$tickers[$i]->getPPS().'
            ';
            
            if($tickers[$i]->getChange() < 0)
            {
                echo '
                                <mark class="markup-red">'.$tickers[$i]->getChange().'%</mark>
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
                        </p>
                    </form>
                </div>
        ';
    }

    function topsAndFlops($all_artists)
    {
        ArtistInfo::sort($all_artists, 0, (sizeof($all_artists)-1), "Descending", "Day Change");

        echo '
            <h3 class="h3-blue">Tops And Flops</h3>
            <form action="../../backend/artist/ArtistShareInfoBackend.php" method="post">
        ';

        for($i = 0; $i < sizeof($all_artists); $i++)
        {
            echo '
                <p class="p-white">
                <input name = "artist_name" type = "submit" style="border:1px transparent; background-color: transparent; font-weight: bold; color: white;" aria-pressed="true" value ="'.$all_artists[$i]->getUsername().'"> ('.$all_artists[$i]->getMarketTag().')
            ';
            if($all_artists[$i]->getDayChange() > 0)
            {
                echo '
                    <span class="suc-msg">
                        +'.$all_artists[$i]->getDayChange().'%
                    </span>
                ';
            }
            else if($all_artists[$i]->getDayChange() < 0)
            {
                echo '
                    <span class="error-msg">
                        '.$all_artists[$i]->getDayChange().'%
                    </span>
                ';
            }
            else if($all_artists[$i]->getDayChange() == 0)
            {
                echo '
                    <span>
                        '.$all_artists[$i]->getDayChange().'%
                    </span>
                ';
            }
            echo '
                </p>
            ';
        }
        echo "</form>";
    }

    function followedArtist($user_username)
    {
        $followed_artists = array();
        $conn = connect();

        $res = searchFollowingArtist($conn, $user_username);
        while($row = $res->fetch_assoc())
        {
            $artist_info = new ArtistInfo();

            $res_ticker = searchArtistTicker($conn, $row['artist_username']);
            $artist_ticker = $res_ticker->fetch_assoc();

            $artist_info->setUsername($row['artist_username']);
            $artist_info->setMarketTag($artist_ticker['ticker']);

            array_push($followed_artists, $artist_info);
        }

        echo '
            <h3 class="h3-blue">Followed</h3>
            <form action="../../backend/artist/ArtistShareInfoBackend.php" method="post">
        ';

        for($i = 0; $i < sizeof($followed_artists); $i++)
        {
            echo '
                <p>
                <input name = "artist_name" type = "submit" class="cursor-context" aria-pressed="true" value ="'.$followed_artists[$i]->getUsername().'"> ('.$followed_artists[$i]->getMarketTag().')
                </p>
            ';
        }

        echo "</form>";
    }

    function apex($all_artists)
    {
        ArtistInfo::sort($all_artists, 0, (sizeof($all_artists)-1), "Descending", "Market Cap");
        echo '
            <h3 class="h3-blue">Apex (Market Cap)</h3>
            <form action="../../backend/artist/ArtistShareInfoBackend.php" method="post">
        ';

        for($i = 0; $i < sizeof($all_artists); $i++)
        {
            echo '
                <p class="p-white">
                <input name = "artist_name" type = "submit" style="border:1px transparent; background-color: transparent; font-weight: bold; color: white;" aria-pressed="true" value ="'.$all_artists[$i]->getUsername().'"> ('.$all_artists[$i]->getMarketTag().') $'.$all_artists[$i]->getMarketCap().'
                </p>
            ';
        }

        echo "</form>";
    }

    function localArtist()
    {
        //Nothing to do now, leave this for future implementation
        echo '
            <h3 class="h3-blue">Local artist</h3>
        ';
    }
?>