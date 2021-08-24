<?php
    include '../../APIs/listener/ListenerBackend.php';

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
        $balance = $search_6->fetch_assoc();
        $_SESSION['user_balance'] = $balance['balance'];

         
    }

    function getLowerBound($artist_username)
    {
        $conn = connect();
        $result = getArtistShareLowerBound($conn, $artist_username);
        $lower_bound = $result->fetch_assoc();
         
        return $lower_bound['lower_bound'];
    }

    //gets all the users that has lowest price listed with the passed artist_username param
    function fetchAskedPrice(&$asked_prices, &$user_usernames, &$artist_usernames, &$quantities,  $artist_username)
    {
        $conn = connect();
        $result = getAskedPrices($conn, $artist_username);
        //loading up data so all the arrays have corresponding indices that map to the database
        while($row = $result->fetch_assoc())
        {
            if($row['no_of_share'] > 0 && (strcmp($row['user_username'], $_SESSION['username']) != 0))
            {
                array_push($asked_prices, $row['selling_price']);
                array_push($user_usernames, $row['user_username']);
                array_push($artist_usernames, $row['artist_username']);
                array_push($quantities, $row['no_of_share']);
            }
        }
        //using insertion sort in MyPortfiolioBackend.php file
        insertionSort($asked_prices, $user_usernames, $artist_usernames, $quantities, "Descending");
         
    }

    function askedPriceInit()
    {
        //displaying asked price marketplace
        $asked_prices = array();
        //displaying corresponding user_username
        $user_usernames = array();
        //displaying corresponding artist_username
        $artist_usernames = array();
        //displaying the quantity that are being sold
        $quantities = array();
        //sorting asked_price in a descending order, so the first index would be the lowest value, then swaps the other arrays 
        //to match with asked_price indices
        fetchAskedPrice($asked_prices, $user_usernames, $artist_usernames, $quantities, $_SESSION['selected_artist']);
            echo '
                <div class="py-4 text-left">
                    <h3>Lowest Asked Price </h3>
                </div>';
        if(sizeof($asked_prices) > 0)
        {
            //displays the buy button when user has not clicked on it
            //always displays first index, which is lowest bid price
            if($_SESSION['buy_asked_price'] == 0)
            {
                echo'
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">Seller username</th>
                                <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">Price per share(q̶)</th>
                                <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">Quantity</th>
                                <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">+</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">'.$user_usernames[0].'</th>
                                <td>'.$asked_prices[0].'</td>
                                <td>'.$quantities[0].'</td>
                                <form action="../../APIs/shared/ToggleBuyAskedPriceBackend.php" method="post">
                                    <td><input name="buy_user_selling_price" role="button" type="submit" class="btn btn-primary" value="Buy From '.$user_usernames[0].'" onclick="window.location.reload();"></td>
                                </form>
                            </tr>
                        </tbody>
                    </table>
                ';
            }
            //replaces the Buy button with a slide bar ranging from 0 to the quantity that other users are selling
            else
            {
                echo'
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">Seller username</th>
                                <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">Price per share(q̶)</th>
                                <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">Quantity</th>
                                <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col"></th>
                                <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">+</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">'.$user_usernames[0].'</th>
                                <td>'.$asked_prices[0].'</td>
                                <td>'.$quantities[0].'</td>
                                <td>
                    ';
                        if(strcmp($_SESSION['seller'], $user_usernames[0]) == 0)
                        {
                            $_SESSION['purchase_price'] = $asked_prices[0];
                            $_SESSION['seller_toggle'] = $user_usernames[0];
                            echo'
                                    <form action="../../APIs/shared/BuySharesBackend.php" method="post">
                                        <input name = "purchase_quantity" type="range" min="1" max='.$quantities[0].' value="1" class="slider" id="myRange">
                                        <p>Quantity: <span id="demo"></span></p>
                                        <input name="buy_user_selling_price" type="submit" id="abc" style="border:1px transparent; background-color: transparent;" role="button" aria-pressed="true" value="->" onclick="window.location.reload();">
                                    </form>
                                    <form action="../../APIs/shared/ToggleBuyAskedPriceBackend.php" method="post">
                                        <td><input name="buy_user_selling_price" type="submit" id="abc" style="border:1px transparent; background-color: transparent;" role="button" aria-pressed="true" value="-" onclick="window.location.reload();"></td>
                                    </form>
                            ';
                        }
                        else
                        {
                            echo'
                                    <form action="../../APIs/shared/ToggleBuyAskedPriceBackend.php" method="post">
                                        <td><input name="buy_user_selling_price" role="button" type="submit" class="btn btn-primary" value="Buy From '.$user_usernames[0].'" onclick="window.location.reload();"></td>
                                    </form>
                                    </td>
                                </tr>
                            ';
                        }
                echo '
                        </tbody>
                    </table>
                ';
            }
        }
        //If other users are selling shares, displays nothing
        else
        {
            echo '
                <div class="py-4 text-center">
                    <h4>No shares are currently sold by other users</h4>
                </div>
            ';
        }
    }

    function canCreateSellOrder($user_username, $artist_username)
    {
        $conn = connect();

        $res = searchSpecificInvestment($conn, $user_username, $artist_username);
        $total_share_bought = $res->fetch_assoc();

        $share_being_sold = getAmountSharesSelling($user_username, $artist_username);
        if($share_being_sold < $total_share_bought['no_of_share_bought'])
        {
            return true;
        }

        return false;
    }

    function getAmountSharesSelling($user_username, $artist_username)
    {
        $conn = connect();
        
        $ret = 0;
        $res = getSpecificAskedPrice($conn, $user_username, $artist_username);
        while($row = $res->fetch_assoc())
        {
            $ret += $row['no_of_share'];
        }

        return $ret;
    }
?>