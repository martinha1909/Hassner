<?php
include '../../backend/constants/StatusCodes.php';
include '../../backend/constants/LoggingModes.php';
include '../../backend/constants/AccountTypes.php';

if ($_SESSION['dependencies'] == "FRONTEND") 
{
    //we want to limit the access of artist account to these functions
    if ($_SESSION['account_type'] == AccountType::User) 
    {
        include '../../backend/listener/include/ListenerHelpers.php';
    }
} 
else if ($_SESSION['dependencies'] == "BACKEND") 
{
    if ($_SESSION['account_type'] == AccountType::User) 
    {
        include '../listener/include/ListenerHelpers.php';
    }
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
    $_SESSION['shares_owned'] = 0;

    $conn = connect();
    $search_1 = searchSharesInArtistShareHolders($conn, $_SESSION['username'], $_SESSION['selected_artist']);
    if($search_1->num_rows != 0)
    {
        $row = $search_1->fetch_assoc();
        $_SESSION['shares_owned'] = $row['shares_owned'];
    }

    $search_2 = searchArtistCurrentPricePerShare($conn, $_SESSION['selected_artist']);
    //current price per share of selected artist
    $_SESSION['current_pps'] = $search_2->fetch_assoc();

    $search_3 = searchInitialPriceWhenBought($conn, $_SESSION['username'], $_SESSION['selected_artist']);
    if ($search_3->num_rows > 0) {
        //price per share when this user bought with the selected artist
        $_SESSION['bought_pps'] = $search_3->fetch_assoc();

        //displaying profit in siliqas
        $_SESSION['profit'] = $_SESSION['current_pps']['price_per_share'] - $_SESSION['bought_pps']['price_per_share_when_bought'];
        $_SESSION['profit'] = round($_SESSION['profit'], 2);
        //displaying profit in %
        $_SESSION['profit_rate'] = ($_SESSION['profit'] / $_SESSION['current_pps']['price_per_share']) * 100;
        $_SESSION['profit_rate'] = round($_SESSION['profit_rate'], 2);
    } else {
        $_SESSION['bought_pps'] = "N/A";

        //displaying profit in siliqas
        $_SESSION['profit'] = "N/A";
        //displaying profit in %
        $_SESSION['profit_rate'] = 0;
    }

    $search_4 = searchArtistTotalSharesBought($conn, $_SESSION['selected_artist']);
    //total number of shares bought accross all users with the selected artist
    $total_share_bought = 0;
    while ($row = $search_4->fetch_assoc()) {
        $total_share_bought += $row['shares_owned'];
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

//gets all the users that has lowest price listed with the passed artist_username param
function fetchAskedPrice(&$ids, &$asked_prices, &$user_usernames, &$artist_usernames, &$quantities,  $artist_username)
{
    $conn = connect();
    $result = searchSellOrderByArtist($conn, $artist_username);
    //loading up data so all the arrays have corresponding indices that map to the database
    while ($row = $result->fetch_assoc()) {
        if ($row['no_of_share'] > 0 && (strcmp($row['user_username'], $_SESSION['username']) != 0)) {
            array_push($ids, $row['id']);
            array_push($asked_prices, $row['selling_price']);
            array_push($user_usernames, $row['user_username']);
            array_push($artist_usernames, $row['artist_username']);
            array_push($quantities, $row['no_of_share']);
        }
    }
    //using insertion sort in MyPortfiolioBackend.php file
    insertionSort($asked_prices, $user_usernames, $artist_usernames, $quantities, "Descending");
    singleSort($ids, "Descending");
}

function askedPriceInit($artist_username, $account_type)
{
    //displaying asked price marketplace
    $asked_prices = array();
    //displaying corresponding user_username
    $user_usernames = array();
    //displaying corresponding artist_username
    $artist_usernames = array();
    //displaying the quantity that are being sold
    $quantities = array();
    //storing unique id of sell orders
    $ids = array();
    //sorting asked_price in a descending order, so the first index would be the lowest value, then swaps the other arrays 
    //to match with asked_price indices
    fetchAskedPrice($ids, $asked_prices, $user_usernames, $artist_usernames, $quantities, $artist_username);

    if($account_type == AccountType::User)
    {
        echo '
                <div>
                    <h3 class="h3-blue py-5 text-center">Bid Price</h3>
                </div>
        ';
    }
    else if($account_type == AccountType::Artist)
    {
        echo '
                <div>
                    <h3 class="h3-blue py-5 text-center">Shares available at bid price</h3>
                </div>
        ';
    }
    if (sizeof($asked_prices) > 0) {
        //displays the buy button when user has not clicked on it
        if ($_SESSION['buy_asked_price'] == 0) {
            echo '
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Seller username</th>
                                <th scope="col">Price per share(q̶)</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">+</th>
                            </tr>
                        </thead>
                        <tbody>';
            for ($i = 0; $i < sizeof($artist_usernames); $i++) {
                echo '
                            <tr>
                                <th scope="row">' . $ids[$i] . '</th>
                                <td>' . $user_usernames[$i] . '</td>
                                <td>' . $asked_prices[$i] . '</td>
                                <td>' . $quantities[$i] . '</td>
                                <form action="../../backend/shared/ToggleBuyAskedPriceBackend.php" method="post">
                    ';
                if (hasEnoughSiliqas($asked_prices[0], $_SESSION['user_balance'])) {
                    echo '
                                        <td><input name="buy_user_selling_price[' . $ids[$i] . ']" role="button" type="submit" class="btn btn-primary" value="Buy" onclick="window.location.reload();"></td>
                        ';
                } else {
                    $_SESSION['status'] = "ERROR";
                    echo '
                                        <td>
                        ';
                    getStatusMessage("Not enough siliqas", "");
                    echo '
                                        </td>
                        ';
                }
                echo '
                                    </form>
                                </tr>
                    ';
            }
            echo '
                    </tbody>
                </table>
                ';
        }
        //replaces the Buy button with a slide bar ranging from 0 to the quantity that other users are selling
        else {
            echo '
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Seller username</th>
                                <th scope="col">Price per share(q̶)</th>
                                <th scope="col">Quantity</th>
                                <th scope="col"></th>
                                <th scope="col">+</th>
                            </tr>
                        </thead>
                        <tbody>';
            for ($i = 0; $i < sizeof($artist_usernames); $i++) {
                echo '
                            <tr>
                                <th scope="row">' . $ids[$i] . '</th>
                                <td>' . $user_usernames[$i] . '</td>
                                <td>' . $asked_prices[$i] . '</td>
                                <td>' . $quantities[$i] . '</td>
                                <td>
                    ';
                if ($_SESSION['seller'] == $ids[$i]) {
                    $_SESSION['purchase_price'] = $asked_prices[$i];
                    $_SESSION['seller_toggle'] = $ids[$i];
                    echo '
                                <form action="../../backend/shared/BuySharesBackend.php" method="post">
                                    <input name = "purchase_quantity" type="range" min="1" max=' . $quantities[$i] . ' value="1" class="slider" id="myRange">
                                    <p>Quantity: <span id="demo"></span></p>
                                    <input name="asked_price[' . $asked_prices[$i] . ']" type="submit" id="abc" style="border:1px transparent; background-color: transparent;" role="button" aria-pressed="true" value="->" onclick="window.location.reload();">
                                </form>
                                <form action="../../backend/shared/ToggleBuyAskedPriceBackend.php" method="post">
                                    <td><input name="buy_user_selling_price[' . $ids[$i] . ']" type="submit" id="abc" style="border:1px transparent; background-color: transparent;" role="button" aria-pressed="true" value="-" onclick="window.location.reload();"></td>
                                </form>
                        ';
                } else {
                    echo '
                                <form action="../../backend/shared/ToggleBuyAskedPriceBackend.php" method="post">
                                <td><input name="buy_user_selling_price[' . $ids[$i] . ']" role="button" type="submit" class="btn btn-primary" value="Buy" onclick="window.location.reload();"></td>
                                </form>
                                </td>
                            </tr>
                        ';
                }
            }
            echo '
                        </tbody>
                    </table>
                ';
        }
    }
    
    //If other users are selling shares, displays nothing
    else {
        echo '
                <div class="py-4 text-center">
                    <h4>No shares are currently sold by other users</h4>
                </div>
            ';
    }
}

    //retrieves from the database all the rows that contains all selling shares accrossed all artists of $user_username
    //If notices a row that has quantity of 0, simply just removes it from the database
    function fetchSellOrders($user_username, &$artist_usernames, &$roi, &$selling_prices, &$share_amounts, &$profits, &$date_posted, &$ids)
    {
        $current_date = getCurrentDate("America/Edmonton");
        $conn = connect();
        $result = searchSellOrderByUser($conn, $user_username);
        while($row = $result->fetch_assoc())
        {
            if($row['no_of_share'] == 0)
            {
                removeSellOrder($conn, $row['id']);
            }
            else
            {
                $result_2 = searchArtistCurrentPricePerShare($conn, $row['artist_username']);
                $pps = $result_2->fetch_assoc();
                $_roi = (($row['selling_price'] - $pps['price_per_share'])/($pps['price_per_share']))*100;
                $profit = $row['selling_price'] - $pps['price_per_share'];

                $relative_time_posted = toRelativeTime($current_date, 
                                                       $row['date_posted'], 
                                                       $row['time_posted']);

                array_push($artist_usernames, $row['artist_username']);
                array_push($roi, round($_roi, 2));
                array_push($selling_prices, $row['selling_price']);
                array_push($share_amounts, $row['no_of_share']);
                array_push($profits, $profit);
                array_push($date_posted, $relative_time_posted);
                array_push($ids, $row['id']);
            }
        }
    }

    function sellOrderInit()
    {
        //Displaying sell order section
        $artist_usernames = array();
        $roi = array();
        $selling_prices = array();
        $share_amounts = array();
        $profits = array();
        $date_posted = array();
        $ids = array();

        //update the shares that the user is currently selling
        fetchSellOrders(
            $_SESSION['username'],
            $artist_usernames,
            $roi,
            $selling_prices,
            $share_amounts,
            $profits,
            $date_posted,
            $ids
        );

        if (sizeof($selling_prices) > 0) {
            echo '    
                
                <div class="container py-6 my-auto mx-auto">    
                <h3>Sell orders</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="th-tan" scope="col">Order ID</th>
                                <th class="th-tan" scope="col">Artist</th>
                                <th class="th-tan" scope="col">Selling for (q̶)</th>
                                <th class="th-tan" scope="col">Quantity</th>
                                <th class="th-tan" scope="col">ROI</th>
                                <th class="th-tan" scope="col">Gain/Loss (q̶)</th>
                                <th class="th-tan" scope="col">Date Posted</th>
                                <th class="th-tan" scope="col">Remove Order</th>
                            </tr>
                        </thead>
                        <tbody>';
        for ($i = 0; $i < sizeof($selling_prices); $i++) {
            //Allowing users to remove/cancek their share order
            echo '
                            <form action="../../backend/shared/RemoveSellOrderBackend.php" method="post">
                                <tr>
                                    <th scope="row"><input name="remove_id" class="cursor-context" value = "' . $ids[$i] . '"></th>
                                    <td>' . $artist_usernames[$i] . '</th>
                                    <td>' . $selling_prices[$i] . '</td>
                                    <td>' . $share_amounts[$i] . '</td>
                                    <td>' . $roi[$i] . '%</td>
                                    <td>' . $profits[$i] . '</td>
                                    <td>' . $date_posted[$i] . '</td>
                                    <td><input type="submit" id="abc" class="cursor-context" role="button" aria-pressed="true" value="☉" onclick="window.location.reload();"></td>
                                </tr>
                            </form>
                    ';
        }
        echo '
                        </tbody>
                    </table>
                    </div>
                ';
    }
}

function canCreateSellOrder($user_username, $artist_username)
{
    $total_share_bought = 0;
    $conn = connect();

    $res = searchSpecificInvestment($conn, $user_username, $artist_username);

    while ($row = $res->fetch_assoc()) {
        $total_share_bought += $row['no_of_share_bought'];
    }

    $share_being_sold = getAmountSharesSelling($user_username, $artist_username);
    if ($share_being_sold < $total_share_bought) {
        return true;
    }

    return false;
}

function canCreateBuyOrder($user_username, $artist_username, $shares_requesting)
{
    $conn = connect();

    $res = searchNumberOfShareDistributed($conn, $artist_username);
    $total_share_dist = $res->fetch_assoc();

    if ($shares_requesting >= $total_share_dist['Share_Distributed']) {
        return false;
    }

    return true;
}

function getAmountSharesSelling($user_username, $artist_username)
{
    $conn = connect();

    $ret = 0;
    $res = searchSharesSelling($conn, $user_username, $artist_username);
    while ($row = $res->fetch_assoc()) {
        $ret += $row['no_of_share'];
    }

    return $ret;
}

function getAmountSharesRequesting($user_username, $artist_username)
{
    $conn = connect();

    $ret = 0;
    $res = searchSharesRequested($conn, $user_username, $artist_username);
    while ($row = $res->fetch_assoc()) {
        $ret += $row['quantity'];
    }

    return $ret;
}

function getHighestOrLowestPPS($artist_username, $indicator)
{
    if ($indicator == "MAX") {
        $conn = connect();

        $res1 = searchArtistCurrentPricePerShare($conn, $artist_username);
        $market_price = $res1->fetch_assoc();

        $res2 = searchArtistHighestPrice($conn, $artist_username);
        $highest_asked_price = $res2->fetch_assoc();

        //if market price is higher, return that as a highest value
        if ($market_price['price_per_share'] > $highest_asked_price['maximum']) {
            return $market_price['price_per_share'];
        }

        //if somebody is selling higher than market price and higher than other sellers, 
        //return that as a highest value
        if ($market_price['price_per_share'] < $highest_asked_price['maximum']) {
            return $highest_asked_price['maximum'];
        }

        //if both are the same, then return one of them, in this case return market price
        return $market_price['price_per_share'];
    } else {
        $conn = connect();

        $res1 = searchArtistCurrentPricePerShare($conn, $artist_username);
        $market_price = $res1->fetch_assoc();

        $res2 = searchSellOrderByArtist($conn, $_SESSION['username']);

        //If there are no users that are selling this artist's shares other than himself, 
        //return the market price per share 
        if ($res2->num_rows == 0) {
            return $market_price['price_per_share'];
        }

        $res3 = searchArtistLowestPrice($conn, $artist_username);
        if ($res3->num_rows == 0) {
            return $market_price['price_per_share'];
        }
        $lowest_asked_price = $res3->fetch_assoc();

        //if market price is lower, return that as a lowest value
        if ($market_price['price_per_share'] < $lowest_asked_price['minimum']) {
            return $market_price['price_per_share'];
        }

        //if somebody is selling lower than market price and lower than other sellers, 
        //return that as a lowest value
        if ($market_price['price_per_share'] > $lowest_asked_price['minimum']) {
            return $lowest_asked_price['minimum'];
        }

        //if both are the same, then return one of them, in this case return market price
        return $market_price['price_per_share'];
    }
}

function currenciesToUSD($amount, $currency): float
{
    //Doesn't change if currency is CAD
    $ret = $amount;

    if($currency == "CAD")
    {
        //Probably will need to functionality to pull real fluctuating value from the world in the future
        $ret *= 0.81;
    }
    else if($currency == "EUR")
    {
        $ret *= 1.16;
    }

    return $ret;
}

function USDToCurrencies($amount, $currency): float
{
    $ret = $amount;
    if($currency == "CAD")
    {
        //Probably will need to functionality to pull real fluctuating value from the world in the future
        $ret *= 1.24;
    }
    else if($currency == "EUR")
    {
        $ret *= 0.86;
    }

    return $ret;
}

function fiatInit()
{
    $account_info = getAccount($_SESSION['username']);
    $balance = getUserBalance($_SESSION['username']);

    echo '
            <section id="login" class="py-5";>
                <div class="container">
                    <div class="col-12 mx-auto my-auto text-center">
                        <form action="../../backend/shared/CurrencyBackend.php" method="post">
        ';

    if ($_SESSION['logging_mode'] == LogModes::DEPOSIT) {
        if ($_SESSION['status'] == StatusCodes::ErrEmpty) {
            $_SESSION['status'] = StatusCodes::ErrGeneric;
            getStatusMessage("Please fill out all fields and try again", "");
        } else if($_SESSION['status'] == StatusCodes::ErrNum) {
            $_SESSION['status'] = StatusCodes::ErrGeneric;
            getStatusMessage("Amount has to be a number", "");
        } else {
            getStatusMessage("Failed to buy, an error occured", "Succeeded");
        }
    } else if ($_SESSION['logging_mode'] == LogModes::WITHDRAW) {
        if ($_SESSION['status'] == StatusCodes::ErrEmpty) {
            $_SESSION['status'] = StatusCodes::ErrGeneric;
            getStatusMessage("Please fill out all fields and try again", "");
        } else if ($_SESSION['status'] == StatusCodes::ErrNotEnough) {
            $_SESSION['status'] = StatusCodes::ErrGeneric;
            getStatusMessage("Not enough CAD", "");
        } else {
            getStatusMessage("An error occured", "Succeeded");
        }
    }

    if ($_SESSION['currency'] == 0) {
        echo '
                    <div style="float:none;margin:auto;" class="select-dark">
                        <select name="currency" id="dark" onchange="this.form.submit()">
                            <option selected disabled>Currency</option>
                            <option value="USD">USD</option>
                            <option value="CAD">CAD</option>
                            <option value="EUR">EUR</option>
                        </select>
                    </div>
            ';
        echo "Account balance: " . $balance . "<br>";
    } else {
        echo '
                    <div style="float:none;margin:auto;" class="select-dark">
                        <select name="currency" id="dark" onchange="this.form.submit()">
                            <option selected disabled>' . $_SESSION['currency'] . '</option>
                            <option value="USD">USD</option>
                            <option value="CAD">CAD</option>
                            <option value="EUR">EUR</option>
                        </select>
                    </div>
            ';
        echo "Account balance: " . $balance . "<br>";
        echo '
                    </form>
                    <form action = "../../backend/shared/FiatOptionsSwitcher.php" method = "post">
            ';
        if ($_SESSION['currency'] == 0) {
            echo '
                        <h5 style="padding-top:150px;"> Please choose a currency</h5>
                ';
        } else {
            if ($_SESSION['fiat_options'] == BalanceOption::NONE) {
                echo '
                            <div class="navbar-light bg-dark" class="col-md-8 col-12 mx-auto pt-5 text-center">
                                <input name = "options" type = "submit" class="btn btn-secondary" role="button" aria-pressed="true" name = "button" value = "'.BalanceOption::DEPOSIT.'" onclick="window.location.reload();"> 
                                <input name = "options" type = "submit" class="btn btn-secondary" role="button" aria-pressed="true" name = "button" value = "'.BalanceOption::WITHDRAW.'" onclick="window.location.reload();"> 
                            </div>
                        </form>
                    ';
            } else if ($_SESSION['fiat_options'] == BalanceOption::DEPOSIT_CAPS) {
                echo '
                            <div class="navbar-light bg-dark" class="col-md-8 col-12 mx-auto pt-5 text-center">
                                <input name = "options" type = "submit" class="btn btn-primary" role="button" aria-pressed="true" name = "button" value = "'.BalanceOption::DEPOSIT.'" onclick="window.location.reload();"> 
                                <input name = "options" type = "submit" class="btn btn-secondary" role="button" aria-pressed="true" name = "button" value = "'.BalanceOption::WITHDRAW.'" onclick="window.location.reload();"> 
                            </div>
                        </form>
                    ';
            } else if ($_SESSION['fiat_options'] == BalanceOption::WITHDRAW_CAPS) {
                echo '
                            <div class="navbar-light bg-dark" class="col-md-8 col-12 mx-auto pt-5 text-center">
                                <input name = "options" type = "submit" class="btn btn-secondary" role="button" aria-pressed="true" name = "button" value = "'.BalanceOption::DEPOSIT.'" onclick="window.location.reload();"> 
                                <input name = "options" type = "submit" class="btn btn-primary" role="button" aria-pressed="true" name = "button" value = "'.BalanceOption::WITHDRAW.'" onclick="window.location.reload();"> 
                            </div>
                        </form>
                    ';
            }
        }
    }
    if ($_SESSION['fiat_options'] == BalanceOption::DEPOSIT_CAPS) {
        echo '
                    <form action = "../../backend/shared/FiatSendSwitcher.php" method = "post">
                        <div class="form-group">
            ';
        echo '
                            <h5 style="padding-top:150px;">Enter Amount in ' . $_SESSION['currency'] . '</h5>
                            <input type="text" name = "currency" style="border-color: white;" class="form-control form-control-sm" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter amount">
                        </div>
                        <div class="navbar-light bg-dark" class="col-md-8 col-12 mx-auto pt-5 text-center">
                                <input type = "submit" class="btn btn-primary" role="button" aria-pressed="true" name = "button" value = "Continue to Checkout" onclick="window.location.reload();"> 
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>';
    } else if ($_SESSION['fiat_options'] == BalanceOption::WITHDRAW_CAPS) {
        echo '
                    <form action = "../../backend/shared/FiatSendSwitcher.php" method = "post">
                        <div class="form-group">
                            <h5 style="padding-top:150px;">Enter Amount in USD</h5>
                            <input type="text" name = "currency" style="border-color: white;" class="form-control form-control-sm" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter amount">
                        </div>
                        <div class="navbar-light bg-dark" class="col-md-8 col-12 mx-auto pt-5 text-center">
                            <input type = "submit" class="btn btn-primary" role="button" aria-pressed="true" name = "button" value = "Continue to Checkout" onclick="window.location.reload();"> 
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>';
    }
}

function fetchInjectionHistory($artist_username, &$comments, &$amount_injected, &$date_injected, &$time_injected)
{
    $conn = connect();

    $res = getInjectionHistory($conn, $artist_username);

    while ($row = $res->fetch_assoc()) {
        $date = dateParser($row['date_injected']);
        $time = timeParser($row['time_injected']);

        array_push($comments, $row['comment']);
        array_push($amount_injected, $row['amount']);
        array_push($date_injected, $date);
        array_push($time_injected, $time);
    }
}

function injectionHistoryInit($artist_username)
{
    $comments = array();
    $amount_injected = array();
    $date_injected = array();
    $time_injected = array();

    fetchInjectionHistory(
        $artist_username,
        $comments,
        $amount_injected,
        $date_injected,
        $time_injected
    );
    echo '
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Ethos amount</th>
                        <th scope="col">Comment</th>
                        <th scope="col">Date Injected</th>
                    </tr>
                </thead>
                <tbody>
        ';

    for ($i = 0; $i < sizeof($amount_injected); $i++) {
        echo '
                    <tr>
                        <th scope="row">' . $amount_injected[$i] . '</th>
                        <td>' . $comments[$i] . '</td>
                        <td>' . $date_injected[$i] . ' at '.$time_injected[$i].'</td>
                    </tr>
            ';
    }

    echo '
                    </tbody>
                </table>
        ';
}

function refreshSellOrderTable()
{
    $conn = connect();

    $res = searchAllSellOrders($conn);
    while ($row = $res->fetch_assoc()) {
        if ($row['no_of_share'] <= 0) {
            removeSellOrder($conn, $row['id']);
        }
    }
}

function refreshBuyOrderTable()
{
    $conn = connect();

    $res = searchAllBuyOrders($conn);
    while ($row = $res->fetch_assoc()) {
        if ($row['quantity'] <= 0) {
            removeBuyOrder($conn, $row['id']);
        }
    }
}

function autoSell($user_username, $artist_username, $asked_price, $quantity)
{
    $conn = connect();

    $res = searchBuyOrdersByArtist($conn, $artist_username);
    while ($row = $res->fetch_assoc()) {
        if ($quantity <= 0) {
            break;
        }

        if ($row['user_username'] == $user_username) {
            continue;
        }

        if ($row['siliqas_requested'] == $asked_price) {
            //If the sell order is selling more shares than the posted buy order
            if ($quantity >= $row['quantity']) {
                $current_date_time = getCurrentDate("America/Edmonton");
                $date_parser = dayAndTimeSplitter($current_date_time);

                $result = searchAccount($conn, $user_username);
                $account_info = $result->fetch_assoc();

                //if the user buys from the bid price, the siliqas will go to the other user since they are the seller
                $seller_new_balance = $account_info['balance'] + ($row['quantity'] * $asked_price);

                $seller_new_share_amount = $account_info['Shares'] - $row['quantity'];

                $res_1 = searchAccount($conn, $row['user_username']);
                $buyer_account_info = $res_1->fetch_assoc();
                $buyer_new_share_amount = $buyer_account_info['Shares'] + $row['quantity'];

                //subtracts siliqas from the user
                $buyer_new_balance = $buyer_account_info['balance'] - (($row['quantity'] * $asked_price));

                //In the case of buying in asked price, the new market price will become the last purchased price
                $new_pps = $asked_price;

                $res_latest_time = getArtistLatestPPSChangeTimeByDay($conn, $_SESSION['selected_artist'], $date_parser[0]);
                $fetch = $res_latest_time->fetch_assoc();
                $latest_time = new DateTime($fetch['latest_time']);
                //We only care about the hours and minutes, not the seconds
                $current_time = new DateTime(substr($date_parser[1], 0, 5));
                $interval = $current_time->diff($latest_time);

                $log_pps = FALSE;
                //Only update the pps if it has been more than the specified interval
                if($interval->format("%i") > $_SESSION['update_pps_interval'])
                {
                    $log_pps = TRUE;
                }

                $connPDO = connectPDO();

                purchaseAskedPriceShare($connPDO,
                                        $row['user_username'],
                                        $user_username,
                                        $artist_username,
                                        $buyer_new_balance,
                                        $seller_new_balance,
                                        $_SESSION['current_pps']['price_per_share'],
                                        $new_pps,
                                        $buyer_new_share_amount,
                                        $seller_new_share_amount,
                                        $_SESSION['shares_owned'],
                                        $row['quantity'],
                                        $row['siliqas_requested'],
                                        $row['id'],
                                        $date_parser[0],
                                        $date_parser[1],
                                        "AUTO_SELL",
                                        $log_pps);

                updateBuyOrderQuantity($conn, $row['id'], 0);

                //The return value should be the amount of share requested subtracted by the amount that 
                //is automatically bought
                $quantity = $quantity - $row['quantity'];
            } else if ($quantity < $row['quantity']) {
                $current_date_time = getCurrentDate("America/Edmonton");
                $date_parser = dayAndTimeSplitter($current_date_time);

                $result = searchAccount($conn, $user_username);
                $account_info = $result->fetch_assoc();

                //if the user buys from the bid price, the siliqas will go to the other user since they are the seller
                $seller_new_balance = $account_info['balance'] + ($quantity * $asked_price);

                $seller_new_share_amount = $account_info['Shares'] - $quantity;

                $res_1 = searchAccount($conn, $row['user_username']);
                $buyer_account_info = $res_1->fetch_assoc();
                $buyer_new_share_amount = $buyer_account_info['Shares'] + $quantity;

                //subtracts siliqas from the user
                $buyer_new_balance = $buyer_account_info['balance'] - (($quantity * $asked_price));

                //In the case of buying in asked price, the new market price will become the last purchased price
                $new_pps = $asked_price;

                $res_latest_time = getArtistLatestPPSChangeTimeByDay($conn, $_SESSION['selected_artist'], $date_parser[0]);
                $fetch = $res_latest_time->fetch_assoc();
                $latest_time = new DateTime($fetch['latest_time']);
                //We only care about the hours and minutes, not the seconds
                $current_time = new DateTime(substr($date_parser[1], 0, 5));
                $interval = $current_time->diff($latest_time);

                $log_pps = FALSE;
                //Only update the pps if it has been more than the specified interval
                if($interval->format("%i") > $_SESSION['update_pps_interval'])
                {
                    $log_pps = TRUE;
                }

                $connPDO = connectPDO();

                purchaseAskedPriceShare($connPDO,
                                        $row['user_username'],
                                        $user_username,
                                        $artist_username,
                                        $buyer_new_balance,
                                        $seller_new_balance,
                                        $_SESSION['current_pps']['price_per_share'],
                                        $new_pps,
                                        $buyer_new_share_amount,
                                        $seller_new_share_amount,
                                        $_SESSION['shares_owned'],
                                        $quantity,
                                        $row['siliqas_requested'],
                                        $row['id'],
                                        $date_parser[0],
                                        $date_parser[1],
                                        "AUTO_SELL",
                                        $log_pps);

                //The return value should be the amount of share requested subtracted by the amount that 
                //is automatically bought
                $quantity = $quantity - $row['quantity'];
            }
        }
    }

    return $quantity;
}
    function calculateTotalNumberOfSharesBought($user_username, $artist_username)
    {
        $ret = 0;
        $conn = connect();

        $res = searchSpecificInvestment($conn, $user_username, $artist_username);
        while($row = $res->fetch_assoc()) {
            $ret += $row['no_of_share_bought'];
        }

        return $ret;
    }

    function buyHistoryInit(&$sellers, &$prices, &$quantities, &$date_purchase, &$time_purchase, $username)
    {
        $conn = connect();

        $res = searchUsersInvestment($conn, $username);

        while($row = $res->fetch_assoc())
        {
            if($_SESSION['account_type'] == AccountType::Artist)
            {
                $date = toYYYYMMDD($row['date_purchased']);
            }
            else if($_SESSION['account_type'] == AccountType::User)
            {
                $date = dateParser($row['date_purchased']);
            }
            $time = timeParser($row['time_purchased']);

            array_push($prices, $row['price_per_share_when_bought']);
            array_push($sellers, $row['seller_username']);
            array_push($quantities, $row['no_of_share_bought']);
            array_push($date_purchase, $date);
            array_push($time_purchase, $time);
        }
    }

    function populateTradeHistory($conn, $query_result): TradeHistoryList
    {
        $trade_history_list = new TradeHistoryList();

        while($row = $query_result->fetch_assoc())
        {
            //only display the dates that are in the range that the user chose
            if(isInRange($row['date_purchased'], $_SESSION['trade_history_from'], $_SESSION['trade_history_to']))
            {
                if($trade_history_list->isListEmpty())
                {
                    $item = new TradeHistoryItem($row['date_purchased']);
                    $item->addPrice($row['price_per_share_when_bought']);
                    $item->addValue($row['price_per_share_when_bought']);
                    $item->addVolume($row['no_of_share_bought']);
                    $item->addTrade();

                    $trade_history_list->addItem($item);
                }
                else
                {
                    if($trade_history_list->dateHasExisted($row['date_purchased']))
                    {
                        $trade_history_list->addToExistedDate($row['date_purchased'],
                                                                $row['price_per_share_when_bought'],
                                                                $row['no_of_share_bought']);
                    }
                    else
                    {
                        $item = new TradeHistoryItem($row['date_purchased']);
                        $item->addPrice($row['price_per_share_when_bought']);
                        $item->addValue($row['price_per_share_when_bought']);
                        $item->addVolume($row['no_of_share_bought']);
                        $item->addTrade();

                        $trade_history_list->addItem($item);
                    }
                }
            }
        }

        if($trade_history_list->getListSize() > 0)
        {
            $trade_history_list->finalize();
        }

        return $trade_history_list;
    }

    function getAllArtistTickers()
    {
        $ret = array();
        $conn = connect();

        //Searches all artists in the program
        $res = searchAccountType($conn, "artist");
        while($row = $res->fetch_assoc())
        {
            $ticker_info = new TickerInfo();
            $artist_username = $row['username'];

            $res_ticker = searchArtistTicker($conn, $artist_username);
            $artist_ticker = $res_ticker->fetch_assoc();
            $ticker_info->setTag($artist_ticker['ticker']);

            $res_pps = searchArtistCurrentPricePerShare($conn, $artist_username);
            $artist_pps = $res_pps->fetch_assoc();
            $ticker_info->setPPS($artist_pps['price_per_share']);

            //Will implement a last 24 change calculation later
            $change = 1;
            $ticker_info->setChange($change);

            array_push($ret, $ticker_info);
        }

        return $ret;
    }

    function isAlreadyFollowed($user_username, $artist_username): bool
    {
        $ret = FALSE;
        $conn = connect();

        $res = searchSpecificFollow($conn, $user_username, $artist_username);
        if($res->num_rows > 0)
        {
            $ret = TRUE;
        }
        return $ret;
    }

    function calculateMarketCap($artist_username)
    {
        $conn = connect();
        $connPDO = connectPDO();
        $market_cap = 0;
        $res1 = searchArtistTotalSharesBought($conn, $artist_username);
        $res2 = searchArtistCurrentPricePerShare($conn, $artist_username);
        $pps = $res2->fetch_assoc();
        while($row = $res1->fetch_assoc())
        {
            $market_cap += ($row['shares_owned'] * $pps['price_per_share']);
        }

        //update the market cap 
        updateArtistMarketCap($connPDO, $artist_username, $market_cap);

        return $market_cap;
    }

    function getArtistJSONChange($artist_username, $graph_option)
    {
        $conn = connect();
        $ret = array();
        //update interval, in minutes. Default is set to the global variable of update interval
        $update_interval = $_SESSION['update_pps_interval'];
        $current_date_time = getCurrentDate("America/Edmonton");
        $date_parser = dayAndTimeSplitter($current_date_time);
        $current_day = $date_parser[0];
        $current_time = $date_parser[1];

        if($graph_option == GraphOption::ONE_DAY)
        {
            $res = getArtistPPSChange($conn, $artist_username, $graph_option);
            $one_day_ago = date('d-m-Y', strtotime('-1 day', strtotime($date_parser[0])));

            while($row = $res->fetch_assoc())
            {
                if(isInRange($row['date_recorded'], $one_day_ago, $current_day))
                {
                    if(sizeof($ret) == 0)
                    {
                        array_push($ret, $row);
                    }
                    else if(isOutSideOfUpdateInterval($ret[sizeof($ret) - 1]['time_recorded'], $row['time_recorded'], $update_interval))
                    {
                        array_push($ret, $row);
                    }
                }
            }
        }
        else if($graph_option == GraphOption::FIVE_DAY)
        {
            $res = getArtistPPSChange($conn, $artist_username, $graph_option);
            $five_days_ago = date('d-m-Y', strtotime('-5 days', strtotime($date_parser[0])));
            $update_interval = 60;
            while($row = $res->fetch_assoc())
            {
                if(isInRange($row['date_recorded'], $five_days_ago, $current_day))
                {
                    echo $row['time_recorded'];
                    echo "--".$row['date_recorded'];
                    echo "<br>";
                    if(sizeof($ret) == 0)
                    {
                        array_push($ret, $row);
                    }
                    else if(dateIsInTheFuture($current_day, $row['date_recorded']))
                    {
                        array_push($ret, $row);
                    }
                    else if(isOutSideOfUpdateInterval($ret[sizeof($ret) - 1]['time_recorded'], $row['time_recorded'], $update_interval))
                    {
                        array_push($ret, $row);
                    }
                }
            }
        }

        closeCon($conn);

        //now print the data
        return json_encode($ret);
    }

    function getArtistMarketTag($artist_username)
    {
        $ret = "Error in getting artist market tag";
        $conn = connect();

        $res = searchArtistTicker($conn, $artist_username);
        if($res->num_rows > 0)
        {
            $artist_tag = $res->fetch_assoc();
            $ret = $artist_tag['ticker'];
        }

        closeCon($conn);

        return $ret;
    }
?>