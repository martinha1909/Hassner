<?php
    function calculateEligibleParticipants($artist_username, $criteria)
    {
        $ret = 0;
        $total_share_of_each_participant = 0;
        $conn = connect();

        $res = getArtistShareHoldersInfo($conn, $artist_username);
        while($row = $res->fetch_assoc())
        {
            if($row['shares_owned'] >= $criteria)
            {
                $ret++;
            }
        }

        return $ret;
    }

    /**
    * Fetches all current campaigns of a given artist. 
    *
    * @param  	artist_username	    Artist username to fetch campaigns for
    * @return 	ret	                an array of campaign objects, containing all campaigns that are currently active of the artist
    */
    function fetchArtistCurrentCampaigns($artist_username)
    {
        $ret = array();
        $conn = connect();
        //First index contains date
        //Second index contains time
        $current_date = dayAndTimeSplitter(getCurrentDate("America/Edmonton"));

        $res = searchArtistCampaigns($conn, $artist_username);
        while($row = $res->fetch_assoc())
        {
            $current_campaign = new Campaign();
            //Avoid fetching campaigns that are already expired in the past
            if($row['date_expires'] != "0000-00-00 00:00:00")
            {
                $date_expires = explode(" ", $row['date_expires'])[0];
                $time_expires = substr(explode(" ", $row['date_expires'])[1], 0, 5);
                $campaign_time_left = calculateTimeLeft($current_date[0], 
                                                        $current_date[1], 
                                                        $date_expires, 
                                                        $time_expires);
                
                //Result of raffle roll
                //Assuming not applicable, only applicable for type raffle
                $roll_res = "N/A";

                $eligible_participant = calculateEligibleParticipants($_SESSION['username'], $row['minimum_ethos']);

                //If by the time of fetching and found a campaign has expired, mark the campaign in the db as expired
                //so we don't come back to it on late fetches
                if($campaign_time_left == "0000-00-00 00:00:00")
                {
                    if($row['type'] == "raffle")
                    {
                        $res_1 = searchNumberOfShareDistributed($conn, $row['artist_username']);
                        $artist_share_distributed = $res_1->fetch_assoc();
                        $roll_res = getRaffleResult($conn, $row['id'], $artist_share_distributed['Share_Distributed']);
                    }

                    updateRaffleCampaignWinner($conn, $row['id'], $roll_res);
                    updateCampaignExpirationDate($conn, $row['id'], $campaign_time_left);

                    if($eligible_participant != $row['eligible_participants'])
                    {
                        updateCampaignEligibleParticipants($conn, $row['id'], $eligible_participant);
                    }
                }
                else
                {
                    if($row['type'] == "raffle")
                    {
                        $roll_res = $campaign_time_left;
                    }
                    if($eligible_participant != $row['eligible_participants'])
                    {
                        updateCampaignEligibleParticipants($conn, $row['id'], $eligible_participant);
                    }
                    $time_released = dbDateTimeParser($row['date_expires']);

                    $current_campaign->setOffering($row['offering']);
                    $current_campaign->setTimeLeft($campaign_time_left);
                    $current_campaign->setEligibleParticipants($eligible_participant);
                    $current_campaign->setMinEthos($row['minimum_ethos']);
                    $current_campaign->setType($row['type']);
                    $current_campaign->setWinner($roll_res);
                    $current_campaign->setDatePosted($time_released);

                    array_push($ret, $current_campaign);
                }
            }
        }

        return $ret;
    }

    /**
    * Fetches all expired campaigns of a given artist. 
    *
    * @param  	artist_username	    Artist username to fetch campaigns for
    * @return 	ret	                an array of campaign objects, containing all campaigns that are expired of the artist
    */
    function fetchArtistExpiredCampaigns($artist_username)
    {
        $ret = array();
        $conn = connect();

        $res = searchArtistCampaigns($conn, $artist_username);
        while($row = $res->fetch_assoc())
        {
            $expired_campaign = new Campaign();
            if($row['date_expires'] == "0000-00-00 00:00:00")
            {
                $time_released = dbDateTimeParser($row['date_posted']);

                $expired_campaign->setOffering($row['offering']);
                $expired_campaign->setEligibleParticipants($row['eligible_participants']);
                $expired_campaign->setMinEthos($row['minimum_ethos']);
                $expired_campaign->setType($row['type']);
                $expired_campaign->setWinner($row['winner']);
                $expired_campaign->setDatePosted($time_released);

                array_push($ret, $expired_campaign);
            }
        }

        return $ret;
    }

    function getRaffleResult($conn, $campaign_id, $artist_share_distributed): string
    {
        $weighted_chances = array();
        $values = array();
        //Assuming error
        $ret = "Error";

        $res = searchCampaignMinimumEthos($conn, $campaign_id);
        $campaign_info = $res->fetch_assoc();

        $participants = getParticipantList($conn, $campaign_info['minimum_ethos'], $campaign_info['artist_username']);

        participantsWeightedChance($participants, $artist_share_distributed, $weighted_chances, $values);

        //If the size of all weighted chances is 0, then there were no participants that participated in this campaign
        if(sizeof($weighted_chances) == 0)
        {
            $ret = "N/A";
        }
        else
        {
            $ret = weighted_random_chance($values, $weighted_chances);
        }
        return $ret;
    }

    //Checks for all campaigns in the database, if it has expired and if it is a raffle campaign, roll the raffle
    function checkRaffleRoll()
    {
        $conn = connect();
        //First index contains date
        //Second index contains time
        $current_date = dayAndTimeSplitter(getCurrentDate("America/Edmonton"));

        $res = searchCampaignsByType($conn, "raffle");
        while($row = $res->fetch_assoc())
        {
            //Avoid fetching campaigns that are already expired in the past
            if($row['date_expires'] != "0000-00-00 00:00:00")
            {
                $date_expires = explode(" ", $row['date_expires'])[0];
                $time_expires = substr(explode(" ", $row['date_expires'])[1], 0, 5);
                //Assume error
                $roll_res = "roll error";
                $campaign_time_left = calculateTimeLeft($current_date[0], 
                                                        $current_date[1], 
                                                        $date_expires, 
                                                        $time_expires);

                //If by the time of fetching and found a campaign has expired, mark the campaign in the db as expired
                //so we don't come back to it on late fetches
                if($campaign_time_left == "0000-00-00 00:00:00")
                {
                    if($row['type'] == "raffle")
                    {
                        $res_1 = searchNumberOfShareDistributed($conn, $row['artist_username']);
                        $artist_share_distributed = $res_1->fetch_assoc();
                        //If the campaign has expired, we roll the raffle
                        $roll_res = getRaffleResult($conn, $row['id'], $artist_share_distributed['Share_Distributed']);
                        updateRaffleCampaignWinner($conn, $row['id'], $roll_res);
                    }

                    updateCampaignExpirationDate($conn, $row['id'], $campaign_time_left);
                }
            }
        }
    }

    function getParticipantList($conn, $minimum_ethos, $artist_username): ParticipantList
    {
        $ret = new ParticipantList();

        $total_share_of_each_participant = 0;

        $res = getArtistShareHoldersInfo($conn, $artist_username);
        while($row = $res->fetch_assoc())
        {
            if($ret->isListEmpty())
            {
                $total_number_of_shares_bought = calculateTotalNumberOfSharesBought($row['user_username'], $artist_username);
                if($total_number_of_shares_bought >= $minimum_ethos)
                {
                    $participant = new CampaignParticipant();
                    $participant->setArtistName($artist_username);
                    $participant->setParticipantName($row['user_username']);
                    $participant->setEthosOwned($total_number_of_shares_bought);
                    $ret->addItem($participant);
                }
            }
            //Skip the duplicate usernames
            else if($row['user_username'] == $ret->getLastItem()->getParticipantName())
            {
                continue;
            }
            else
            {
                $total_number_of_shares_bought = calculateTotalNumberOfSharesBought($row['user_username'], $artist_username);
                if($total_number_of_shares_bought >= $minimum_ethos)
                {
                    $participant = new CampaignParticipant();
                    $participant->setArtistName($artist_username);
                    $participant->setParticipantName($row['user_username']);
                    $participant->setEthosOwned($total_number_of_shares_bought);
                    $ret->addItem($participant);
                }
            }
        }
        return $ret;
    }

    function participantsWeightedChance(ParticipantList &$participants, $artist_total_shares, &$weighted_chances, &$values)
    {
        $participants->populateWeightedChance($artist_total_shares, $weighted_chances, $values);
    }

    /**
     * weighted_random_chance($values, $weighted_chances)
     * Pick a random item based on weights.
     *
     * @param array $values Array of elements to choose from 
     * @param array $weighted_chances An array of weights. Weight must be a positive number.
     * @return mixed Selected element.
     */
    function weighted_random_chance($values, $weighted_chances)
    {
        //Assume error check
        $ret = "Error in rolling weighted chance";

        $count = count($values); 
        $i = 0; 
        $n = 0; 
        $num = mt_rand(1, array_sum($weighted_chances)); 

        while($i < $count)
        {
            $n += $weighted_chances[$i]; 
            if($n >= $num)
            {
                break; 
            }
            $i++; 
        } 
        $ret = $values[$i];

        return $ret;
    }

    function calculateCampaignWinningChance($user_username, $artist_username, $campaign_id, $min_ethos, $users_total_shares_bought)
    {
        $ret = 0;
        $conn = connect();
        $res_eligible = searchCampaignEligibleParticipants($conn, $campaign_id);
        $row_eligible = $res_eligible->fetch_assoc();
        if($row_eligible['eligible_participants'] == 0)
        {
            //shouldn't reach here, but does this check to be safe anyway
            $ret = 0;
        }
        else if($row_eligible['eligible_participants'] == 1)
        {
            //If the user is the only one participates in the campaign, the chance of him winning is 100%
            $ret = 100;
        }
        else
        {
            $shareholder_values = array();
            $res = searchArtistTotalSharesBought($conn, $artist_username);
            while($row = $res->fetch_assoc())
            {
                if($row['shares_owned'] >= $min_ethos && $row['user_username'] != $user_username)
                {
                    array_push($shareholder_values, $row['shares_owned']);
                }
            }
            
            $sum = $users_total_shares_bought;
            for($i = 0; $i < sizeof($shareholder_values); $i++)
            {
                $sum += $shareholder_values[$i];
            }

            $ret = $users_total_shares_bought/$sum * 100;
        }

        return round($ret, 2);
    }

    /**
    * Determine if a user is eligible to participte in a given campaign or not
    *
    * @param  	user_username	        user username
    * @param  	artist_username	        artist username to query user's investment
    * @param  	campaign_id	            campaign id to be determined
    * @return 	ret	                    true if the user is eligible to participate in the given campaign, false otherwise
    */
    function userIsParticipatingInCampaign($user_username, $artist_username, $campaign_id)
    {
        $conn = connect();
        $ret = false;

        $res_min_ethos = searchCampaignMinimumEthos($conn, $campaign_id);
        $row_min_ethos = $res_min_ethos->fetch_assoc();
        $campaign_min_ethos = $row_min_ethos['minimum_ethos'];

        $res_user_shares = searchSharesInArtistShareHolders($conn, $user_username, $artist_username);
        $row_user_shares = $res_user_shares->fetch_assoc();
        $user_shares = $row_user_shares['shares_owned'];

        if($user_shares > $campaign_min_ethos)
        {
            $ret = true;
        }

        return $ret;
    }

    /**
    * Determine if a user has above 90% of a given campaign requirement or not
    *
    * @param  	user_num_shares	        amount of shares a user has
    * @param  	campaign_min_ethos	    campaign requirement
    * @return 	ret	                    true if the user has above 90% towards the campaign requirement, false otherwise
    */
    function isNearParticipation($user_num_shares, $campaign_min_ethos)
    {
        $conn = connect();
        $ret = false;

        $progress = ($user_num_shares/$campaign_min_ethos) * 100;

        //A campaign is treated as near participation if it is above 90% towards the minimum requirement
        if($progress >= 90 && $progress < 100)
        {
            $ret = true;
        }

        return $ret;
    }
?>