<?php
    function queryInvestment($listener_username)
    {
        $conn = connect();
        $result = searchUsersInvestment($conn, $listener_username);
         
        return $result;
    }

    function totalShareDistributed($artist_username)
    {
        $conn = connect();
        $res = searchNumberOfShareDistributed($conn, $artist_username);
        hx_debug(HX::QUERY, "searchNumberOfShareDistributed returned ".$res->num_rows." entries");

        $ret = $res->fetch_assoc();
        hx_debug(HX::QUERY, "ret data: ".json_encode($ret));

        return $ret['Share_Distributed'];
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

        $res = searchUserInvestedArtists($conn, $user_username);
        while($row = $res->fetch_assoc()) {
            array_push($ret, $row['artist_username']);
        }

        return $ret;
    }

    /**
    * Fetches all participating campaign of a given user. 
    * If a user has less than the required ethos amount, the campaign will not be added to the returning array
    *
    * @param  	user_username	    Username to fetch campaigns for
    * @return 	ret	                an array of campaign objects, containing all campaigns that a user is participating in
    */
    function fetchInvestedArtistCampaigns($user_username)
    {
        $ret = array();
        $current_date = dayAndTimeSplitter(getCurrentDate("America/Edmonton"));
        $conn = connect();
        $all_artists = getAllInvestedArtists($user_username);

        for($i = 0; $i < sizeof($all_artists); $i++) 
        {
            $total_shares_bought = calculateTotalNumberOfSharesBought($user_username, $all_artists[$i]);
            // $res = searchArtistCampaigns($conn, $all_artists[$i]);
            $res = searchUserParticipatingCampaign($conn, $user_username);
            while($row = $res->fetch_assoc()) 
            {
                //time complexity of O(1) at all times
                $res_campaign = searchCampaignByID($conn, $row['campaign_id']);
                $campaign_info = $res_campaign->fetch_assoc();

                $participating_campaign = new Campaign();
                //assume not applicable
                $chance = -1;
                $res_1 = searchNumberOfShareDistributed($conn, $campaign_info['artist_username']);
                $artist_share_distributed = $res_1->fetch_assoc();
                if($campaign_info['is_active'] != 0)
                {
                    if($total_shares_bought >= $campaign_info['minimum_ethos']) 
                    {
                        $progress_calc = 100;
                    }
                    else 
                    {
                        $progress_calc = ($total_shares_bought/$campaign_info['minimum_ethos']) * 100;
                    }
                    $date_expires = explode(" ", $campaign_info['date_expires'])[0];
                    $time_expires = substr(explode(" ", $campaign_info['date_expires'])[1], 0, 5);
                    $campaign_time_left = calculateTimeLeft($current_date[0], 
                                                            $current_date[1], 
                                                            $date_expires, 
                                                            $time_expires);
                    //If by the time of fetching and found a campaign has expired, mark the campaign in the db as expired
                    //so we don't come back to it on late fetches
                    if($campaign_time_left == "0000-00-00 00:00:00")
                    {
                        $roll_res = "N/A";
                        if($campaign_info['type'] == "raffle")
                        {
                            $roll_res = getRaffleResult($conn, $campaign_info['id'], $artist_share_distributed['Share_Distributed']);
                        }
                        updateRaffleCampaignWinner($conn, $campaign_info['id'], $roll_res);
                        updateCampaignActiveStatus($conn, $campaign_info['id'], 0);
                    }
                    if($campaign_info['type'] == "raffle")
                    {
                        if(userIsParticipatingInCampaign($user_username, $campaign_info['artist_username'], $campaign_info['id']))
                        {
                            $chance = calculateCampaignWinningChance($user_username, 
                                                                     $campaign_info['artist_username'],
                                                                     $campaign_info['id'],
                                                                     $campaign_info['minimum_ethos'],
                                                                     $total_shares_bought);
                        }
                    }

                    if(userIsParticipatingInCampaign($user_username, $campaign_info['artist_username'], $campaign_info['id']))
                    {
                        $participating_campaign->setID($campaign_info['id']);
                        $participating_campaign->setEligibleParticipants($campaign_info['eligible_participants']);
                        $participating_campaign->setArtistUsername($campaign_info['artist_username']);
                        $participating_campaign->setOffering($campaign_info['offering']);
                        $participating_campaign->setProgress($progress_calc);
                        $participating_campaign->setTimeLeft($campaign_time_left);
                        $participating_campaign->setMinEthos($campaign_info['minimum_ethos']);
                        $participating_campaign->setUserOwnedEthos($total_shares_bought);
                        $participating_campaign->setType($campaign_info['type']);
                        $participating_campaign->setWinningChance($chance);

                        array_push($ret, $participating_campaign);
                    }
                }
            }
        }

        return $ret;
    }

    /**
    * Fetches all past participated campaigns of a given user.
    *
    * @param  	user_username	    Username to fetch campaigns for
    * @return 	ret	                an array of campaign objects, containing all campaigns that a user has participated in
    */
    function fetchParticipatedCampaigns($user_username)
    {
        $ret = array();
        $conn = connect();
        $res = searchUserParticipatingCampaign($conn, $user_username);
        // $res = searchArtistCampaigns($conn, $all_artists[$i]);
        while($row = $res->fetch_assoc()) 
        {
            //time complexity of O(1) at all times
            $res_campaign = searchCampaignByID($conn, $row['campaign_id']);
            $campaign_info = $res_campaign->fetch_assoc();
            $participated_campaign = new Campaign();
            if($campaign_info['is_active'] == 0)
            {
                $time_released = dbDateTimeParser($campaign_info['date_posted']);

                $participated_campaign->setId($campaign_info['id']);
                $participated_campaign->setArtistUsername($campaign_info['artist_username']);
                $participated_campaign->setOffering($campaign_info['offering']);
                $participated_campaign->setMinEthos($campaign_info['minimum_ethos']);
                if($campaign_info['type'] == CampaignType::RAFFLE)
                {
                    $participated_campaign->setWinner($campaign_info['winner']);
                }
                $participated_campaign->setDatePosted($time_released);
                $participated_campaign->setType($campaign_info['type']);
                $participated_campaign->setDateExpires(dbDateTimeParser($campaign_info['date_expires']));
                $participated_campaign->setEligibleParticipants($campaign_info['eligible_participants']);

                array_push($ret, $participated_campaign);
            }
        }

        return $ret;
    }

    /**
    * Fetches all near participation campaign of a given user. 
    * A near participation campaign is determined if a user has a completion progress of more than 80% towards the campaign minimum requirement
    * For example, a campaign that has a minimum requirement of 20 ethos, any users that has invested 16 or more ethos in the owner of that campaign 
    * will be treated as a near participation campaign
    *
    * @param  	user_username	    Username to fetch campaigns for
    * @return 	ret	                an array of campaign objects, containing all campaigns that a user almost has enough ethos to participate
    */
    function fetchNearParticipationCampaign($user_username)
    {
        $ret = array();
        $current_date = dayAndTimeSplitter(getCurrentDate("America/Edmonton"));
        $conn = connect();
        $all_artists = getAllInvestedArtists($user_username);
        
        for($i = 0; $i < sizeof($all_artists); $i++) 
        {
            $total_shares_bought = calculateTotalNumberOfSharesBought($user_username, $all_artists[$i]);

            $res = searchArtistCampaigns($conn, $all_artists[$i]);
            while($row = $res->fetch_assoc())
            {
                $near_participation_campaign = new Campaign();

                //Skip inactive campaigns
                if($row['is_active'] != 0)
                {
                    if(!userIsParticipatingInCampaign($user_username, $row['artist_username'], $row['id']) && isNearParticipation($total_shares_bought, $row['minimum_ethos']))
                    {
                        $date_expires = explode(" ", $row['date_expires'])[0];
                        $time_expires = substr(explode(" ", $row['date_expires'])[1], 0, 5);
                        $campaign_time_left = calculateTimeLeft($current_date[0], 
                                                                $current_date[1], 
                                                                $date_expires, 
                                                                $time_expires);

                        $progress_calc = round(($total_shares_bought/$row['minimum_ethos']) * 100, 2);

                        if($campaign_time_left != "0000-00-00 00:00:00")
                        {
                            $near_participation_campaign->setId($row['id']);
                            $near_participation_campaign->setEligibleParticipants($row['eligible_participants']);
                            $near_participation_campaign->setArtistUsername($row['artist_username']);
                            $near_participation_campaign->setOffering($row['offering']);
                            $near_participation_campaign->setProgress($progress_calc);
                            $near_participation_campaign->setTimeLeft($campaign_time_left);
                            $near_participation_campaign->setMinEthos($row['minimum_ethos']);
                            $near_participation_campaign->setUserOwnedEthos($total_shares_bought);
                            $near_participation_campaign->setType($row['type']);
                            //User hasn't participated yet, so winning chance is still 0
                            $near_participation_campaign->setWinningChance(0);
    
                            array_push($ret, $near_participation_campaign);
                        }
                    }
                }
            }
        }

        if(sizeof($ret) > 1)
        {
            Campaign::sort($ret, 0, (sizeof($ret)-1), "Descending", "Progress");
        }

        return $ret;
    }

    /**
    * Fetches partly participated campaigns of a user (0% < x < 80%, with x is progress towards campaign's completion). 
    * Sort by the progress towards that campaign descendingly
    *
    * @param  	user_username	    Username to fetch campaigns for
    * @return 	ret	                an array of campaign objects, containing all campaigns that a user has more than 0% of progress
    */
    function fetchPotentialParticipationCampaign($user_username)
    {
        $ret = array();
        $current_date = dayAndTimeSplitter(getCurrentDate("America/Edmonton"));
        $conn = connect();
        $all_artists = getAllInvestedArtists($user_username);

        for($i = 0; $i < sizeof($all_artists); $i++) 
        {
            $total_shares_bought = calculateTotalNumberOfSharesBought($user_username, $all_artists[$i]);
            $res = searchArtistCampaignsByExpDateNotEnough($conn, $all_artists[$i], $total_shares_bought);
            while($row = $res->fetch_assoc())
            {
                $near_participation_campaign = new Campaign();
                $date_expires = explode(" ", $row['date_expires'])[0];
                $time_expires = substr(explode(" ", $row['date_expires'])[1], 0, 5);

                $campaign_time_left = calculateTimeLeft($current_date[0], 
                                                        $current_date[1], 
                                                        $date_expires, 
                                                        $time_expires);

                $progress_calc = round(($total_shares_bought/$row['minimum_ethos']) * 100, 2);

                //Just a safe check
                if($campaign_time_left != "0000-00-00 00:00:00")
                {
                    $near_participation_campaign->setId($row['id']);
                    $near_participation_campaign->setEligibleParticipants($row['eligible_participants']);
                    $near_participation_campaign->setArtistUsername($row['artist_username']);
                    $near_participation_campaign->setOffering($row['offering']);
                    $near_participation_campaign->setProgress($progress_calc);
                    $near_participation_campaign->setTimeLeft($campaign_time_left);
                    $near_participation_campaign->setMinEthos($row['minimum_ethos']);
                    $near_participation_campaign->setUserOwnedEthos($total_shares_bought);
                    $near_participation_campaign->setType($row['type']);
                    //User hasn't participated yet, so winning chance is still 0
                    $near_participation_campaign->setWinningChance(0);

                    array_push($ret, $near_participation_campaign);
                }
            }
            
        }

        if(sizeof($ret) > 1)
        {
            Campaign::sort($ret, 0, (sizeof($ret)-1), "Descending", "Progress");
        }

        return $ret;
    }

    /**
    * Fetch campaigns that have the most users participated in accross all artists
    *
    * @param  	username	    user username to query campaigns to display for
    *
    * @return   ret             an array of campaigns
    */
    function fetchTrendingCampaign($user_username)
    {
        $ret = array();
        $current_date = dayAndTimeSplitter(getCurrentDate("America/Edmonton"));
        $conn = connect();
        $res = searchTrendingCampaign($conn);
        while($row = $res->fetch_assoc())
        {
            $trending_campaign = new Campaign();
            $total_shares_bought = calculateTotalNumberOfSharesBought($user_username, $row['artist_username']);

            $date_expires = explode(" ", $row['date_expires'])[0];
            $time_expires = substr(explode(" ", $row['date_expires'])[1], 0, 5);

            $campaign_time_left = calculateTimeLeft($current_date[0], 
                                                    $current_date[1], 
                                                    $date_expires, 
                                                    $time_expires);

            $progress_calc = round(($total_shares_bought/$row['minimum_ethos']) * 100, 2);

            if($campaign_time_left != "0000-00-00 00:00:00" && $progress_calc < 100)
            {
                $trending_campaign->setId($row['id']);
                $trending_campaign->setEligibleParticipants($row['eligible_participants']);
                $trending_campaign->setArtistUsername($row['artist_username']);
                $trending_campaign->setOffering($row['offering']);
                $trending_campaign->setProgress($progress_calc);
                $trending_campaign->setTimeLeft($campaign_time_left);
                $trending_campaign->setMinEthos($row['minimum_ethos']);
                $trending_campaign->setUserOwnedEthos($total_shares_bought);
                $trending_campaign->setType($row['type']);
                //User hasn't participated yet, so winning chance is still 0
                $trending_campaign->setWinningChance(0);

                array_push($ret, $trending_campaign);
            }
        }

        return $ret;
    }

    /**
    * Prints trade history of unique days of a given artist depends on the range that the user chooses
    * Trade History information:
    * -  Price displays the highest and lowest trades of the day
    * -  Volumn displays how many total shares of the artist that was traded that day
    * -  Value displays total amount of siliqas that was traded of the artist that day
    * -  Trades displays the total number of trades that day
    *
    * @param  	artist_username	    chosen artist
    * @return   ret                 string that contains the information to be printed on the frontend
    */
    function tradeHistoryInit($artist_username): string
    {
        $ret = '';
        $conn = connect();

        $ret .= '
            <div class="mx-auto text-center py-2 col-8">
                <h3 class="h3-blue py-2">Trade History</h3>
                <h6>From</h6>
                <input id="listener_trade_history_from" type="date" name="trade_history_from">
                <h6>To</h6>
                <input id="listener_trade_history_to" type="date" name="trade_history_to">

                <p id="listener_trade_history_status" class="error-msg"></p>

                <input id="listener_trade_history_btn" type="submit" class="cursor-context" role="button" value="->">
            </div>

            <div class="div-hidden" id="listener_trade_history_found">
                <div class="py-4">
                    <table class="table" id="listener_trade_history_table">
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Price(HIGH/LOW)</th>
                                <th scope="col">Volume</th>
                                <th scope="col">Value</th>
                                <th scope="col">Trades</th>
                            </tr>
                        </thead>
                        <tbody id="listener_trade_history_table_body">
                        </tbody>
                    </table>
                </div>
            </div>

            <h5 class="error-msg" id="listener_trade_history_not_found"></h5>
        ';

        $_SESSION['trade_history_from'] = 0;
        $_SESSION['trade_history_to'] = 0;

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
        hx_debug(HX::HELPER, "artist_share_distributed is ".$artist_share_distributed);

        //Trivial case, if artist hasn't gone IPO then users can't create buy orders
        if($artist_share_distributed > 0)
        {
            $num_of_shares_invested = getShareInvestedInArtist($user_username, $artist_username);
            hx_debug(HX::HELPER, "num_of_shares_invested is ".$num_of_shares_invested);
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

            $artist = new Artist();

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
        Artist::sort($all_artists, 0, (sizeof($all_artists)-1), "Descending", "Day Change");

        echo '
            <h3 class="h3-blue">Tops And Flops</h3>
            <form action="../../backend/artist/ArtistShareInfoBackend.php" method="post">
        ';

        for($i = 0; $i < sizeof($all_artists); $i++)
        {
            echo '
                <p class="p-white">
                <input name = "artist_name" type = "submit" id="abc" class="input-no-border" value ="'.$all_artists[$i]->getUsername().'"> ('.$all_artists[$i]->getMarketTag().')
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
            $artist_info = new Artist();

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
                    <input name = "artist_name" id="abc" type = "submit" class="cursor-context" value ="'.$followed_artists[$i]->getUsername().'"> ('.$followed_artists[$i]->getMarketTag().')
                </p>
            ';
        }

        echo "</form>";
    }

    function apex($all_artists)
    {
        Artist::sort($all_artists, 0, (sizeof($all_artists)-1), "Descending", "Market Cap");
        echo '
            <h3 class="h3-blue">Apex (Market Cap)</h3>
            <form action="../../backend/artist/ArtistShareInfoBackend.php" method="post">
        ';

        for($i = 0; $i < sizeof($all_artists); $i++)
        {
            echo '
                <p class="p-white">
                <input name = "artist_name" id="abc" type = "submit" class="input-no-border" value ="'.$all_artists[$i]->getUsername().'"> ('.$all_artists[$i]->getMarketTag().') $'.$all_artists[$i]->getMarketCap().'
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