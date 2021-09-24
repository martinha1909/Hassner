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
            $res_2 = searchSellOrderByArtistAndUser($conn, $row['user_username'], $_SESSION['username']);
            while($row_2 = $res_2->fetch_assoc())
            {
                array_push($shareholder_shares_sold, $row_2['no_of_share']);
                array_push($shareholder_names, $row_2['user_username']);
                array_push($share_holder_selling_price, $row_2['selling_price']);
            }
        }
         
    }

    function artistRepurchaseShares($artist_username)
    {
        $conn = connect();
        $res = searchArtistRepurchaseShares($conn, $artist_username);
        $ret = $res->fetch_assoc();

        return $ret['shares_repurchase'];
    }

    function artistShareSelling($artist_username)
    {
        $conn = connect();
        $shares_selling = 0;

        $res = searchSellOrderByArtistAndUser($conn, $artist_username, $artist_username);
        while($row = $res->fetch_assoc())
        {
            $shares_selling += $row['no_of_share'];
        }

        return $shares_selling;
    }

    function artistCanCreateSellOrder($artist_username)
    {
        $ret = false;

        if(artistRepurchaseShares($artist_username) > 0)
        {
            

            if(artistShareSelling($artist_username) < artistRepurchaseShares($artist_username))
            {
                $ret = true;
            }
        }

        return $ret;
    }
?>