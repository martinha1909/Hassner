<?php
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

            $time_released = dateParser($row['date_posted'])." at ".timeParser($row['time_posted']);

            array_push($offerings, $row['offering']);
            array_push($time_left, $campaign_time_left);
            array_push($eligible_participants, $row['eligible_participants']);
            array_push($min_ethos, $row['minimum_ethos']);
            array_push($types, $row['type']);
            array_push($time_releases, $time_released);
        }
    }

    function calculateEligibleParticipants($artist_username, $criteria)
    {
        $ret = 0;
        $conn = connect();

        $res = getArtistShareHoldersInfo($conn, $artist_username);
        while($row = $res->fetch_assoc())
        {

        }
    }
?>