<?php
    function calculateEligibleParticipants($artist_username, $criteria)
    {
        $ret = 0;
        $total_share_of_each_participant = 0;
        $participants = array();
        $conn = connect();

        $res = getArtistShareHoldersInfo($conn, $artist_username);
        while($row = $res->fetch_assoc())
        {
            if(sizeof($participants) == 0)
            {
                array_push($participants, $row['user_username']);
                $total_share_of_each_participant += $row['no_of_share_bought'];
            }
            else
            {
                if($row['user_username'] == $participants[sizeof($participants)-1])
                {
                    $total_share_of_each_participant += $row['no_of_share_bought'];
                }
                //We have encounter a different user
                else
                {
                    if($total_share_of_each_participant >= $criteria)
                    {
                        $ret++;
                    }
                    $total_share_of_each_participant = $row['no_of_share_bought'];
                    array_push($participants, $row['user_username']);
                }
            }
        }
        //We need to check the case that the last index that comes in is the same as the previous one
        //and we end the loop without adding the last one if it meets the criteria
        if($total_share_of_each_participant >= $criteria)
        {
            $ret++;
        }

        return $ret;
    }

    function fetchCampaigns($artist_username, &$offerings, &$time_left, &$eligible_participants, &$min_ethos, &$types, &$time_releases)
    {
        $conn = connect();
        //First index contains date
        //Second index contains time
        $current_date = dayAndTimeSplitter(getCurrentDate("America/Edmonton"));

        $res = searchArtistCampaigns($conn, $artist_username);
        while($row = $res->fetch_assoc())
        {
            $campaign_time_left = calculateTimeLeft($current_date[0], 
                                                    $current_date[1], 
                                                    $row['date_expires'], 
                                                    $row['time_expires']);

            $eligible_participant = calculateEligibleParticipants($_SESSION['username'], $row['minimum_ethos']);
            $time_released = dateParser($row['date_posted'])." at ".timeParser($row['time_posted']);

            array_push($offerings, $row['offering']);
            array_push($time_left, $campaign_time_left);
            array_push($eligible_participants, $eligible_participant);
            array_push($min_ethos, $row['minimum_ethos']);
            array_push($types, $row['type']);
            array_push($time_releases, $time_released);
        }
    }
?>