<?php
    include '../../APIs/listener/MyPortfolioBackend.php';

    //gets all the users that has lowest price listed with the passed artist_username param
    function fetchAskedPrice(&$asked_prices, &$user_usernames, &$artist_usernames, &$quantities,  $artist_username)
    {
        $conn = connect();
        $result = getAskedPrices($conn, $artist_username);
        //loading up data so all the arrays have corresponding indices that map to the database
        while($row = $result->fetch_assoc())
        {
            array_push($asked_prices, $row['selling_price']);
            array_push($user_usernames, $row['user_username']);
            array_push($artist_usernames, $row['artist_username']);
            array_push($quantities, $row['no_of_share']);
        }
        //using insertion sort in MyPortfiolioBackend.php file
        insertionSort($asked_prices, $user_usernames, $artist_usernames, $quantities, "Descending");
    }

    //fetching the market price, if current user has not invested in the selected artist, simply just populate default values
    //default values should be displayed on the table like this:
    //  Owned Shares: 0
    //  Artist: selected artist
    //  Current price per share (q̶): grabs current price per share from database
    //  Selling profit per share (q̶): N/A(0%)
    //  Available Shares: grabs current shares available for purchase in the database in case the user wants to purchase their first share
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
            $_SESSION['profit'] = $_SESSION['current_pps']['price_per_share'] - $_SESSION['bought_pps']['price_per_share_when_bought'];
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
        $total_share_bought = 0;
        while($row = $search_4->fetch_assoc())
        {
            $total_share_bought += $row['no_of_share_bought'];
        }
        $search_5 = searchNumberOfShareDistributed($conn, $_SESSION['selected_artist']);
        //Number of share distributed by the selected artist
        $share_distributed = $search_5->fetch_assoc();
        //shares available for purchase of the selected artist
        $_SESSION['available_shares'] = $share_distributed['Share_Distributed'] - $total_share_bought;

        $search_6 = searchAccount($conn, $_SESSION['username']);
        $_SESSION['user_balance'] = $search_6->fetch_assoc();
    }
?>