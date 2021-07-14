<?php
    //gets all the users that has lowest price listed with the passed artist_username param
    function fetchAskedPrice(&$min_prices, $artist_username)
    {
        $conn = connect();
        $result = getLowestPrice($conn, $artist_username);
        while($row = $result->fetch_assoc())
        {
            array_push($min_prices, $row);
        }
    }

    function fetchMarketPrice($artist_username)
    {
        $conn = connect();
        $search_1 = searchSpecificInvestment($conn, $_SESSION['username'], $_SESSION['selected_artist']);
        if($search_1->num_rows > 0)
        {
            //number of share that current user has bought from selected artist
            $shares_owned = $search_1->fetch_assoc();
            $_SESSION['shares_owned'] = $shares_owned['no_of_share_bought'];
        }
        else
        {
            $_SESSION['shares_owned'] = 0;
        }
        
        $search_2 = searchArtistCurrentPricePerShare($conn, $_SESSION['selected_artist']);
        //current price per share of selected artist
        $_SESSION['current_pps'] = $search_2->fetch_assoc(); 

        $search_3 = searchInitialPriceWhenBought($conn, $_SESSION['username'], $_SESSION['selected_artist']);
        if($search_3->num_rows > 0)
        {
        //price per share when this user bought with the selected artist
            $_SESSION['bought_pps'] = $search_3->fetch_assoc();

            //displaying profit in siliqas
            $_SESSION['profit'] = $_SESSION['bought_pps']['price_per_share_when_bought'] - $_SESSION['current_pps']['price_per_share'];
            $_SESSION['profit'] = round($_SESSION['profit'], 2);
            //displaying profit in %
            $_SESSION['profit_rate'] = ($_SESSION['profit']/$_SESSION['current_pps']['price_per_share']) * 100;
            $_SESSION['profit_rate'] = round($_SESSION['profit_rate'], 2);
        }
        else
        {
            $_SESSION['bought_pps'] = "N/A";

            //displaying profit in siliqas
            $_SESSION['profit'] = "N/A";
            //displaying profit in %
            $_SESSION['profit_rate'] = 0;
        }

        $search_4 = searchArtistTotalSharesBought($conn, $_SESSION['selected_artist']);
        //total number of shares bought accross all users with the selected artist
        $total_share_bought = $search_4->fetch_assoc();
        $search_5 = searchNumberOfShareDistributed($conn, $_SESSION['selected_artist']);
        //Number of share distributed by the selected artist
        $share_distributed = $search_5->fetch_assoc();
        //shares available for purchase of the selected artist
        $_SESSION['available_shares'] = $share_distributed['Share_Distributed'] - $total_share_bought['Shares'];

        $search_6 = searchAccount($conn, $_SESSION['username']);
        $_SESSION['user_balance'] = $search_6->fetch_assoc();
    }
?>