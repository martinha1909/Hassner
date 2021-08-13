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

    function ArtistShareHoldersInfoInit($artist_username, &$shareholder_names, &$shareholder_shares_bought, &$shareholder_shares_sold, &$shareholder_shares_duration)
    {
        $conn = connect();

        $res_1 = getArtistShareHoldersInfo($conn, $artist_username);
        while($row = $res_1->fetch_assoc())
        {
            array_push($shareholder_names, $row['user_username']);
            array_push($shareholder_shares_bought, $row['no_of_share_bought']);
        }
    }
?>