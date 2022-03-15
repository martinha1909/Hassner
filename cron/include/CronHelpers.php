<?php
    function getAllArtist($conn)
    {
        $ret = array();

        $res = searchAccountType($conn, 'artist');
        while($row = $res->fetch_assoc())
        {
            array_push($ret, $row['username']);
        }

        return $ret;
    }

    function getArtistOpenBuyOrdersWithinInterval($conn, $artist_username, $from_date, $to_date): int
    {
        $ret = 0;

        $res = searchBuyOrdersByArtistWithinInterval($conn, $artist_username, $from_date, $to_date);
        if($res->num_rows > 0)
        {
            while($row = $res->fetch_assoc())
            {
                $ret += $row['quantity'];
            }
        }

        return $ret;
    }

    function getArtistOpenSellOrdersWithinInterval($conn, $artist_username, $from_date, $to_date): int
    {
        $ret = 0;

        $res = searchSellOrderByArtistWithinInterval($conn, $artist_username, $from_date, $to_date);
        if($res->num_rows > 0)
        {
            while($row = $res->fetch_assoc())
            {
                $ret += $row['no_of_share'];
            }
        }

        return $ret;
    }

    function calculatePPSBySupplyDemand($conn, $artist_username, $num_open_buy, $num_open_sell): float
    {
        $ret = -1;
        $residual = 0;

        $res = searchArtistCurrentPricePerShare($conn, $artist_username);
        if($res->num_rows > 0)
        {
            $artist_current_pps = $res->fetch_assoc();
            $ret = $artist_current_pps['price_per_share'];

            //No open buy and open sell will be handled by another cron job
            if($num_open_sell > $num_open_buy)
            {
                for($i = 0; $i < ($num_open_sell - $num_open_buy); $i++)
                {
                    $residual -= ((rand(3, 6))/10);
                }
            }
            else if($num_open_sell < $num_open_buy)
            {
                for($i = 0; $i < ($num_open_buy - $num_open_sell); $i++)
                {
                    $residual += ((rand(3, 6))/10);
                }
            }

            $ret += round($residual, 1);
            //Fail safe in case penny stock falls below 0
            if($ret <= 0)
            {
                $ret = 0.01;
            }
        }

        return $ret;
    }
?>