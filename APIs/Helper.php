<?php
    function getHighestOrLowestPPS($artist_username, $indicator)
    {
        if($indicator == "MAX")
        {
            $conn = connect();

            $res1 = searchArtistCurrentPricePerShare($conn, $artist_username);
            $market_price = $res1->fetch_assoc();

            $res2 = searchArtistHighestPrice($conn, $artist_username);
            $highest_asked_price = $res2->fetch_assoc();

            //if market price is higher, return that as a highest value
            if($market_price['price_per_share'] > $highest_asked_price['maximum'])
            {
                return $market_price['price_per_share'];
            }

            //if somebody is selling higher than market price and higher than other sellers, 
            //return that as a highest value
            if($market_price['price_per_share'] < $highest_asked_price['maximum'])
            {
                return $highest_asked_price['maximum'];
            }

            //if both are the same, then return one of them, in this case return market price
            return $market_price['price_per_share'];
        }
        else
        {
            $conn = connect();

            $res1 = searchArtistCurrentPricePerShare($conn, $artist_username);
            $market_price = $res1->fetch_assoc();

            $res2 = searchArtistLowestPrice($conn, $artist_username);
            $lowest_asked_price = $res2->fetch_assoc();

            //if market price is lower, return that as a lowest value
            if($market_price['price_per_share'] < $lowest_asked_price['minimum'])
            {
                return $market_price['price_per_share'];
            }

            //if somebody is selling lower than market price and lower than other sellers, 
            //return that as a lowest value
            if($market_price['price_per_share'] > $lowest_asked_price['minimum'])
            {
                return $lowest_asked_price['minimum'];
            }

            //if both are the same, then return one of them, in this case return market price
            return $market_price['price_per_share'];
        }
    }
?>