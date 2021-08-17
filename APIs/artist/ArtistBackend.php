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

    function getLowerBound($artist_username)
    {
        $conn = connect();
        return getArtistShareLowerBound($conn, $artist_username)->fetch_assoc();
    }

    function ArtistShareHoldersDurationInit($artist_username, &$shareholder_names, &$shareholder_shares_bought, &$shareholder_shares_sold, &$shareholder_shares_duration)
    {
        $conn = connect();

        $res_1 = getArtistShareHoldersInfo($conn, $artist_username);
        while($row = $res_1->fetch_assoc())
        {
            //we query the amount of entries in the user_artist_sell_share table of the database and 
            //the number of entries returned is how many shares of this artist the user is selling, 
            //since each entry represents 1 share sold by user of a specific artist
            $res_2 = getSpecificAskedPrice($conn, $row['user_username'], $_SESSION['username']);

            array_push($shareholder_shares_sold, $res_2->num_rows);
            array_push($shareholder_names, $row['user_username']);
            array_push($shareholder_shares_bought, $row['no_of_share_bought']);
            //Just putting a temporary value until figure out how to track real time in PHP
            array_push($shareholder_shares_duration, 1);
        }
    }
?>