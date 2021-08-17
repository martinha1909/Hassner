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

    function artistShareHoldersDurationInit($artist_username, &$shareholder_names, &$shareholder_shares_bought, &$shareholder_shares_sold, &$shareholder_shares_duration)
    {
        $conn = connect();

        $res_1 = getArtistShareHoldersInfo($conn, $artist_username);
        while($row = $res_1->fetch_assoc())
        {
            $res_2 = getSpecificAskedPrice($conn, $row['user_username'], $_SESSION['username']);
            $amount = 0;
            while($row_2 = $res_2->fetch_assoc())
            {
                $amount += $row_2['no_of_share'];
            }

            array_push($shareholder_shares_sold, $amount);
            array_push($shareholder_names, $row['user_username']);
            array_push($shareholder_shares_bought, $row['no_of_share_bought']);
            //Just putting a temporary value until figure out how to track real time in PHP
            array_push($shareholder_shares_duration, 1);
        }
    }
?>