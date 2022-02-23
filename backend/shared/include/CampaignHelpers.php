<?php
    /**
    * Recalculate eligible participants of all campaigns of a given artist. This function is called after an artist stock has been traded
    *
    * @param  	buyer_username	        Buyer of the artist stock, can be the artist themselves or a normal user
    * @param  	seller_username	        Seller of the artist stock, can be the artist themselves or a normal user
    * @param  	buyer_account_type	    Account type of buyer, can be user or artist
    * @param  	seller_account_type	    Account type of seller, can be user or artist
    * @param  	artist_username	        artist's stock that is being traded
    */
    function recalcCampaignParticipants($buyer_username, $seller_username, $buyer_account_type, $seller_account_type, $artist_username)
    {
        $remove_err_code = StatusCodes::NONE;
        $add_err_code = StatusCodes::NONE;
        $reduce_err_code = StatusCodes::NONE;
        $increase_err_code = StatusCodes::NONE;
        $conn = connect();
        $connPDO = connectPDO();
        $artist_active_campaigns = getArtistActiveCampaigns($conn, $artist_username);
        //p2p trading
        if($seller_account_type != AccountType::Artist && $buyer_account_type != AccountType::Artist)
        {
            $seller_participating_campaigns = getUserParticipatingCampaign($seller_username);
            hx_debug(HX::CAMPAIGN, "Seller ".$seller_username." participating campaigns: ".json_encode($seller_participating_campaigns));
            $buyer_shares_invested = getShareInvestedInArtist($buyer_username, $artist_username);
            hx_debug(HX::QUERY, "Buyer ".$buyer_username." owns ".$buyer_shares_invested." shares of artist ".$artist_username);
            $seller_shares_invested = getShareInvestedInArtist($seller_username, $artist_username);
            hx_debug(HX::QUERY, "Seller ".$seller_username." owns ".$seller_shares_invested." shares of artist ".$artist_username);

            for($i = 0; $i < sizeof($seller_participating_campaigns); $i++)
            {
                $res = searchCampaignMinimumEthos($conn, $seller_participating_campaigns[$i]);
                $campaign_info = $res->fetch_assoc();
                if(campaignIsActive($seller_participating_campaigns[$i]))
                {
                    if($seller_shares_invested < $campaign_info['minimum_ethos'])
                    {
                        $remove_err_code = removeCampaignParticipant($conn, $seller_username, $seller_participating_campaigns[$i]);
                        if($remove_err_code == StatusCodes::Success)
                        {
                            hx_info(HX::CAMPAIGN, $seller_username." no longer participate in campaign id ".$seller_participating_campaigns[$i]);
                            $reduce_err_code = decreaseCampaignEligibleParticipant($connPDO, $seller_participating_campaigns[$i], 1);
                            if($reduce_err_code == StatusCodes::Success)
                            {
                                hx_debug(HX::CAMPAIGN, "Campaign (id: ".$seller_participating_campaigns[$i].") reduced eligible participants by 1");
                            }
                            else
                            {
                                hx_error(HX::CAMPAIGN, "Failed to reduce eligible participant for campaign id ".$seller_participating_campaigns[$i]);
                            }
                        }
                        else
                        {
                            hx_error(HX::CAMPAIGN, "Failed to remove user ".$seller_username." from participating in campaign id ".$seller_participating_campaigns[$i]);
                        }
                    }
                }
            }

            for($i = 0; $i < sizeof($artist_active_campaigns); $i++)
            {
                //If the user has already participated in this campaign, just skip
                if(!userIsParticipatingInCampaign($buyer_username, $artist_username, $artist_active_campaigns[$i]))
                {
                    if($buyer_shares_invested >= getCampaignMinimumEthos($artist_active_campaigns[$i]))
                    {
                        $add_err_code = addToCampaignParticipant($conn, $buyer_username, $artist_active_campaigns[$i]);
                        if($add_err_code == StatusCodes::Success)
                        {
                            hx_info(HX::CAMPAIGN, $buyer_username." just participated in campaign id ".$artist_active_campaigns[$i]);
                            $increase_err_code = increaseCampaignEligibleParticipant($connPDO, $artist_active_campaigns[$i], 1);
                            if($increase_err_code == StatusCodes::Success)
                            {
                                hx_debug(HX::CAMPAIGN, "Campaign (id: ".$artist_active_campaigns[$i].") increased eligible participants by 1");
                            }
                            else
                            {
                                hx_error(HX::CAMPAIGN, "Failed to increase eligible participant for campaign id ".$artist_active_campaigns[$i]);
                            }
                        }
                        else
                        {
                            hx_error(HX::CAMPAIGN, "Failed to add user ".$buyer_username." to participate in campaign id ".$artist_active_campaigns[$i]);
                        }
                    }
                }
            }
        }
        //Buy from shares injection or from artist's sell order who bought back their own shares
        else if($seller_account_type == AccountType::Artist && $buyer_account_type != AccountType::Artist)
        {
            $buyer_shares_invested = getShareInvestedInArtist($buyer_username, $artist_username);
            hx_debug(HX::QUERY, "Buyer ".$buyer_username." owns ".$buyer_shares_invested." shares of artist ".$artist_username);
            for($i = 0; $i < sizeof($artist_active_campaigns); $i++)
            {
                //If the user has already participated in this campaign, just skip
                if(!userIsParticipatingInCampaign($buyer_username, $artist_username, $artist_active_campaigns[$i]))
                {
                    if($buyer_shares_invested >= getCampaignMinimumEthos($artist_active_campaigns[$i]))
                    {
                        $add_err_code = addToCampaignParticipant($conn, $buyer_username, $artist_active_campaigns[$i]);
                        if($add_err_code == StatusCodes::Success)
                        {
                            hx_info(HX::CAMPAIGN, $buyer_username." just participated in campaign id ".$artist_active_campaigns[$i]);
                            $increase_err_code = increaseCampaignEligibleParticipant($connPDO, $artist_active_campaigns[$i], 1);
                            if($increase_err_code == StatusCodes::Success)
                            {
                                hx_debug(HX::CAMPAIGN, "Campaign (id: ".$artist_active_campaigns[$i].") increased eligible participants by 1");
                            }
                            else
                            {
                                hx_error(HX::CAMPAIGN, "Failed to increase eligible participant for campaign id ".$artist_active_campaigns[$i]);
                            }
                        }
                        else
                        {
                            hx_error(HX::CAMPAIGN, "Failed to add user ".$buyer_username." to participate in campaign id ".$artist_active_campaigns[$i]);
                        }
                    }
                }
            }
        }
        //case when artist buys back shares
        else if($seller_account_type != AccountType::Artist && $buyer_account_type == AccountType::Artist)
        {
            $seller_participating_campaigns = getUserParticipatingCampaign($seller_username);
            hx_debug(HX::CAMPAIGN, "Seller ".$seller_username." participating campaigns: ".json_encode($seller_participating_campaigns));
            $seller_shares_invested = getShareInvestedInArtist($seller_username, $artist_username);
            hx_debug(HX::QUERY, "Seller ".$seller_username." owns ".$seller_shares_invested." shares of artist ".$artist_username);
            for($i = 0; $i < sizeof($seller_participating_campaigns); $i++)
            {
                $res = searchCampaignMinimumEthos($conn, $seller_participating_campaigns[$i]);
                $campaign_info = $res->fetch_assoc();
                if(campaignIsActive($seller_participating_campaigns[$i]))
                {
                    if($seller_shares_invested < $campaign_info['minimum_ethos'])
                    {
                        $remove_err_code = removeCampaignParticipant($conn, $seller_username, $seller_participating_campaigns[$i]);
                        if($remove_err_code == StatusCodes::Success)
                        {
                            hx_info(HX::CAMPAIGN, $seller_username." no longer participate in campaign id ".$seller_participating_campaigns[$i]);
                            $reduce_err_code = decreaseCampaignEligibleParticipant($connPDO, $seller_participating_campaigns[$i], 1);
                            if($reduce_err_code == StatusCodes::Success)
                            {
                                hx_debug(HX::CAMPAIGN, "Campaign (id: ".$seller_participating_campaigns[$i].") reduced eligible participants by 1");
                            }
                            else
                            {
                                hx_error(HX::CAMPAIGN, "Failed to reduce eligible participant for campaign id ".$seller_participating_campaigns[$i]);
                            }
                        }
                        else
                        {
                            hx_error(HX::CAMPAIGN, "Failed to remove user ".$seller_username." from participating in campaign id ".$seller_participating_campaigns[$i]);
                        }
                    }
                }
            }
        }
    }

    function campaignIsActive($campaign_id): bool
    {
        $ret = false;
        $conn = connect();

        $res = searchCampaignActiveStatus($conn, $campaign_id);
        $campaign_active_stat = $res->fetch_assoc();
        if($campaign_active_stat['is_active'])
        {
            $ret = true;
        }

        return $ret;
    }

    function getCampaignMinimumEthos($campaign_id)
    {
        $conn = connect();

        $res = searchCampaignMinimumEthos($conn, $campaign_id);
        $min_ethos = $res->fetch_assoc();

        return $min_ethos['minimum_ethos'];
    }

    function getArtistActiveCampaigns($conn, $artist_username)
    {
        $ret = array();
        $res = searchArtistActiveCampaignsID($conn, $artist_username);
        while($row = $res->fetch_assoc())
        {
            array_push($ret, $row['id']);
        }

        return $ret;
    }

    function calculateEligibleParticipants($artist_username, $criteria)
    {
        $ret = 0;
        $total_share_of_each_participant = 0;
        $conn = connect();

        $res = getArtistShareHoldersInfo($conn, $artist_username);
        while($row = $res->fetch_assoc())
        {
            if($row['shares_owned'] >= $criteria && $row['user_username'] != $artist_username)
            {
                $ret++;
            }
        }

        return $ret;
    }

    function getUserParticipatingCampaign($user_username): array
    {
        $ret = array();
        $conn = connect();
        
        $res = searchUserParticipatingCampaign($conn, $user_username);
        while($row = $res->fetch_assoc())
        {
            array_push($ret, $row['campaign_id']);
        }

        closeCon($conn);
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
            if($row['is_active'] != 0)
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
                    updateCampaignActiveStatus($conn, $row['id'], 0);

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
            if($row['is_active'] == 0)
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
            if($row['is_active'] != 0)
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

                    updateCampaignActiveStatus($conn, $row['id'], 0);
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

    /**
    * Calculates the weighted winning chance of a user in a campaign based on how much shares he/she has. 
    * The higher the amount of shares the user has, the higher the winning chance
    *
    * @param  	user_username	                        user username to determine the winning chance
    * @param  	artist_username	                        username of the artist who distributed the campaign
    * @param  	campaign_id	                            campaign id distributed by the artist
    * @param  	min_ethos	                            campaign minumum ethos requirement
    * @param  	users_total_shares_bought	            total number of shares bought by the user towards the given artist
    *
    * @return 	ret	                                    the winning chance of the user
    */
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
    function userIsParticipatingInCampaign($user_username, $artist_username, $campaign_id): bool
    {
        $conn = connect();
        $ret = false;

        $res = searchUserSpecificParticipatingCampaign($conn, $user_username, $campaign_id);
        if($res->num_rows > 0)
        {
            $ret = true;
        }

        return $ret;
    }

    /**
    * Determine if a user has above 80% of a given campaign requirement or not
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
        if($progress >= 80 && $progress < 100)
        {
            $ret = true;
        }

        return $ret;
    }

    /**
    * Determine the number of campaigns a user has won with a given artist
    *
    * @param  	user_username	        user username to determine the amount of campaigns won
    * @param  	artist_username	        given artist username
    *
    * @return 	ret	                    the amount of campaigns won of a user towards the given artist
    */
    function getUserCampaignWonByArtist($user_username, $artist_username): int
    {
        $ret = 0;
        $conn = connect();

        $res = searchCampaignWinnerByArtist($conn, $artist_username);
        if($res->num_rows > 0)
        {
            while($row = $res->fetch_assoc())
            {
                if($row['winner'] == $user_username)
                {
                    $ret++;
                }
            }
        }

        closeCon($conn);
        return $ret;
    }

    /**
    * Determine the number of campaigns a user has participated with a given artist
    *
    * @param  	user_username	        user username to determine the amount of campaigns participated
    * @param  	artist_username	        given artist username
    *
    * @return 	ret	                    the amount of campaigns participated of a user towards the given artist
    */
    function getUserCampaignParticipatedByArtist($user_username, $artist_username): int
    {
        $ret = 0;
        $conn = connect();

        $res = searchArtistCampaigns($conn, $artist_username);
        if($res->num_rows > 0)
        {
            while($row = $res->fetch_assoc())
            {
                if(userIsParticipatingInCampaign($user_username, $artist_username, $row['id']) && $row['date_expires'] == "0000-00-00 00:00:00")
                {
                    $ret++;
                }
            }
        }

        closeCon($conn);
        return $ret;
    }
?>