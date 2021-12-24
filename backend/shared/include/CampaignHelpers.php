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

    function fetchCampaigns($artist_username, &$offerings, &$time_left, &$eligible_participants, &$min_ethos, &$types, &$time_releases, &$roll_results)
    {
        $conn = connect();
        //First index contains date
        //Second index contains time
        $current_date = dayAndTimeSplitter(getCurrentDate("America/Edmonton"));

        $res = searchArtistCampaigns($conn, $artist_username);
        while($row = $res->fetch_assoc())
        {
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

                    array_push($offerings, $row['offering']);
                    array_push($time_left, $campaign_time_left);
                    array_push($eligible_participants, $eligible_participant);
                    array_push($min_ethos, $row['minimum_ethos']);
                    array_push($types, $row['type']);
                    array_push($roll_results, $roll_res);
                    array_push($time_releases, $time_released);
                }
            }
        }
    }
    function fetchExpiredCampaigns($artist_username, &$offerings, &$eligible_participants, &$min_ethos, &$types, &$time_releases, &$roll_results)
    {
        $conn = connect();

        $res = searchArtistCampaigns($conn, $artist_username);
        while($row = $res->fetch_assoc())
        {
            if($row['date_expires'] == "0000-00-00 00:00:00")
            {
                $time_released = dbDateTimeParser($row['date_posted']);

                array_push($offerings, $row['offering']);
                array_push($eligible_participants, $row['eligible_participants']);
                array_push($min_ethos, $row['minimum_ethos']);
                array_push($types, $row['type']);
                array_push($roll_results, $row['winner']);
                array_push($time_releases, $time_released);
            }
        }
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
?>