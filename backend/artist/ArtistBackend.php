<?php
    function getArtistAccount($artist_username, $account_type)
    {
        $conn = connect();
        $result = searchAccount($conn, $artist_username);
        $account_info = $result->fetch_assoc();
         
        return $account_info;
    }

    function fetchCurrentShareholders($artist_username)
    {
        $conn = connect();
        return getArtistShareHolders($conn, $artist_username);
    }

    function calculateMarketCap($artist_username)
    {
        $conn = connect();
        $market_cap = 0;
        $res1 = searchArtistTotalSharesBought($conn, $artist_username);
        $res2 = searchArtistCurrentPricePerShare($conn, $artist_username);
        $pps = $res2->fetch_assoc();
        while($row = $res1->fetch_assoc())
        {
            $market_cap += ($row['no_of_share_bought'] * $pps['price_per_share']);
        }

         

        return $market_cap;
    }

    function artistShareHoldersDurationInit($artist_username, &$shareholder_names, &$share_holder_selling_price, &$shareholder_shares_sold, &$shareholder_shares_duration)
    {
        $_SESSION['current_date'] = getCurrentDate('America/Edmonton');

        $conn = connect();

        $res_1 = getArtistShareHoldersInfo($conn, $artist_username);
        while($row = $res_1->fetch_assoc())
        {
            $res_2 = getSpecificAskedPrice($conn, $row['user_username'], $_SESSION['username']);
            while($row_2 = $res_2->fetch_assoc())
            {
                array_push($shareholder_shares_sold, $row_2['no_of_share']);
                array_push($shareholder_names, $row_2['user_username']);
                array_push($share_holder_selling_price, $row_2['selling_price']);
            }
        }
         
    }

    function fetchInjectionHistory($artist_username, &$comments, &$amount_injected, &$date_injected, &$time_injected)
    {
        $conn = connect();

        $res = getInjectionHistory($conn, $artist_username);

        while($row = $res->fetch_assoc())
        {
            $date = dateParser($row['date_injected']);
            $time = timeParser($row['time_injected']);

            $day = dayToText($date[0]);
            $month = monthToText($date[1]);
            $year = "20".$date[2];

            $inject_date = $month." ".$day.", ".$year;
            $inject_time = timeToText($time[0], $time[1]);

            array_push($comments, $row['comment']);
            array_push($amount_injected, $row['amount']);
            array_push($date_injected, $inject_date);
            array_push($time_injected, $inject_time);
        }
    }
?>