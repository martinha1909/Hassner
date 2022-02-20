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

/**
* Gets the current price per share of an artist stock
*
* @param  	artist_username	   artist username to retrieve stock price from
*
*
* @return 	ret	               current stock price of the artist
*/
function getArtistPricePerShare($artist_username): float
{
    $ret = 0;
    $conn = connect();

    $result = searchAccount($conn, $artist_username);
    hx_debug(HX::QUERY, "searchAccount returned ".$result->num_rows." entries");
    $price_per_share = $result->fetch_assoc();
    hx_debug(HX::QUERY, "price_per_share data: ".json_encode($price_per_share));
        
    $ret = $price_per_share['price_per_share'];

    return $ret;
}

/**
* Fetches the market price, if current user has not invested in the selected artist, simply just populate default values
* Default values:
* Owned Shares: 0
* Artist: selected artist
* Current price per share (q̶): grabs current price per share from database
* Selling profit per share (q̶): N/A(0%)
* Available Shares: grabs current shares available for purchase in the database in case the user wants to purchase their first share
*/
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
                                                       explode(" ", $row['date_posted'])[0], 
                                                       explode(" ", $row['date_posted'])[1]);

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
            ';
            if($roi[$i] > 0)
            {
                echo'
                                    <td class="suc-msg">+' . $roi[$i] . '%</td>';
            }
            if($roi[$i] < 0)
            {
                echo'
                                    <td class="error-msg">' . $roi[$i] . '%</td>';
            }
            if($roi[$i] == 0)
            {
                echo'
                                    <td>' . $roi[$i] . '%</td>';
            }
            
            echo '
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

/**
* Determines if a user can create a sell order or not
*
* @param  	user_username      user that is trying to create a sell order
*
* @param  	artist_username    targetted artist that the sell order is selling
*
* @return 	ret	               true if the user can create a sell order, false otherwise
*/
function canCreateSellOrder($user_username, $artist_username)
{
    $total_share_bought = 0;
    $conn = connect();

    $res = searchSharesInArtistShareHolders($conn, $user_username, $artist_username);

    while ($row = $res->fetch_assoc()) {
        $total_share_bought += $row['shares_owned'];
    }

    $share_being_sold = getAmountSharesSelling($user_username, $artist_username);
    if ($share_being_sold < $total_share_bought) {
        return true;
    }

    return false;
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

/**
* Get artist highest or lowest sell order, depends on the selected option
*
* @param  	artist_username	  artist username to retrieve all the sell orders from
* @param  	indicator	      option to have action upon, MAX would get the highest and MIN would get the lowest
*
* @return 	the price of the sell order, as indicated by indicator
*/
function getHighestOrLowestPPS($artist_username, $indicator)
{
    if ($indicator == "MAX") 
    {
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
    } 
    else 
    {
        $conn = connect();

        $res1 = searchArtistCurrentPricePerShare($conn, $artist_username);
        $market_price = $res1->fetch_assoc();

        $res2 = searchArtistLowestPrice($conn, $artist_username);
        $lowest_asked_price = $res2->fetch_assoc();

        //If no sell order found, return the market price
        if($lowest_asked_price['minimum'] == NULL)
        {
            return $market_price['price_per_share'];
        }

        //if market price is lower, return that as a lowest value
        if ($market_price['price_per_share'] < $lowest_asked_price['minimum']) {
            return $market_price['price_per_share'];
        }

        //if somebody is selling higher than market price and higher than other sellers, 
        //return that as a highest value
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
    $balance = getUserBalance($_SESSION['username']);
    $msg = "getUserBalance returned ".$balance." as a result";
    hx_debug(HX::HELPER, $msg);

    echo '
        <section id="login" class="py-5";>
            <div class="container">
                <div class="col-12 mx-auto my-auto text-center">
    ';
    if(!($_SESSION['testing_phase']))
    {
        $balance = getUserBalance($_SESSION['username']);
        $msg = "getUserBalance returned ".$balance." as a result";
        hx_debug(HX::HELPER, $msg);

        echo '

                    <div style="float:none;margin:auto;" class="select-dark">
                        <select id="balance_dropdown" class="select-dropdown select-dropdown-dark">
                            <option id="balance_dropdown_selected" selected disabled>Choose a currency</option>
                            <option value="'.Currency::USD.'">'.Currency::USD.'</option>
                            <option value="'.Currency::CAD.'">'.Currency::CAD.'</option>
                            <option value="'.Currency::EUR.'">'.Currency::EUR.'</option>
                        </select>
                    </div>
                    Account balance: ' . $balance . '<br>
                    '.showJSStatusMsg().'

                    <div class="div-hidden" id="after_currency_div">
                        <div class="navbar-light bg-dark" class="col-md-8 col-12 mx-auto pt-5 text-center">
                            <input name = "options" type = "submit" class="btn btn-secondary" name = "button" id="deposit_btn" value = "'.BalanceOption::DEPOSIT.'"> 
                            <input name = "options" type = "submit" class="btn btn-secondary" name = "button" id="withdraw_btn" value = "'.BalanceOption::WITHDRAW.'"> 
                        </div>

                        <div class="div-hidden" id="balance_div">  
                            <div class="form-group">
                                <h5 style="padding-top:150px;" id="deposit_or_withdraw_header"></h5>
                                <input type="text" name = "amount" style="border-color: white;" class="form-control form-control-sm" id="deposit_withdraw_amount" placeholder="Enter amount">
                            </div>
                            <div class="navbar-light bg-dark" class="col-md-8 col-12 mx-auto pt-5 text-center">
                                <input type = "submit" class="btn btn-primary" id="checkout_btn" value = "Continue to Checkout"> 
                            </div>
                        </div>
                    </div>
        ';
    }
    else
    {
        echo'
                    <h4>Balance tab is not available during testing phase</h4>
        ';
    }

    echo '
                </div>
            </div>
        </section>    
    ';
};

function fetchInjectionHistory($artist_username, &$comments, &$amount_injected, &$date_injected)
{
    $conn = connect();

    $res = getInjectionHistory($conn, $artist_username);

    while ($row = $res->fetch_assoc()) 
    {
        $date_from_db = reformatDateTime($row['date_injected']);

        array_push($comments, $row['comment']);
        array_push($amount_injected, $row['amount']);
        array_push($date_injected, $date_from_db);
    }
}

/**
* Fetch injection history of a given artist
*
* @param  	artist_username	    chosen artist username
*
* @return 	ret	                a string to be printed out in the frontend, 
*                               contains injection history information in tabular format
*/
function injectionHistoryInit($artist_username): string
{
    $ret = '';
    $comments = array();
    $amount_injected = array();
    $date_injected = array();
    $time_injected = array();

    fetchInjectionHistory(
        $artist_username,
        $comments,
        $amount_injected,
        $date_injected,
    );
    $ret .= '
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Share amount</th>
                        <th scope="col">Comment</th>
                        <th scope="col">Date Injected</th>
                    </tr>
                </thead>
                <tbody>
    ';

    for ($i = 0; $i < sizeof($amount_injected); $i++) {
        $ret .= '
                    <tr>
                        <th scope="row">' . $amount_injected[$i] . '</th>
                        <td>' . $comments[$i] . '</td>
                        <td>' . dbDateTimeParser($date_injected[$i]).'</td>
                    </tr>
        ';
    }

    $ret .= '
                    </tbody>
                </table>
    ';

    return $ret;
}

/**
* Removes all sell orders that have quantity of 0
*/
function refreshSellOrderTable()
{
    $conn = connect();

    $res = searchAllSellOrdersZeroQuantity($conn);
    while ($row = $res->fetch_assoc()) {
        removeSellOrder($conn, $row['id']);
    }
}

/**
* Removes all buy orders that have quantity of 0
*/
function refreshBuyOrderTable()
{
    $conn = connect();

    $res = searchAllBuyOrdersZeroQuantity($conn);
    while ($row = $res->fetch_assoc()) {
        removeBuyOrder($conn, $row['id']);
    }
}

    function calculateTotalNumberOfSharesBought($user_username, $artist_username)
    {
        $ret = 0;
        $conn = connect();

        $res = searchSharesInArtistShareHolders($conn, $user_username, $artist_username);
        if($res->num_rows > 0)
        {
            $row = $res->fetch_assoc();
            $ret = $row['shares_owned'];
        }

        return $ret;
    }

    /**
    * Initializes buy history with indices from each array corresponds to the same information presented in a row
    *
    * @param[out]  	sellers           array contains all sellers that the user has purchased from 
    *
    * @param[out]  	prices            array containing all prices that were bought
    * 
    * @param[out]  	quantities        array containing all quantities that were bought
    *
    * @param[out]  	date_purchase     array containing all date that shares were bought
    *
    * @param[in]  	username          user that buy history is gathering data for
    *
    */
    function buyHistoryInit(&$sellers, &$prices, &$quantities, &$date_purchase, $username)
    {
        $conn = connect();

        $res = searchUsersInvestment($conn, $username);

        while($row = $res->fetch_assoc())
        {
            array_push($prices, $row['price_per_share_when_bought']);
            array_push($sellers, $row['seller_username']);
            array_push($quantities, $row['no_of_share_bought']);
            array_push($date_purchase, dbDateTimeParser($row['date_purchased']));
        }
    }

    /**
    * Initializes trade history given a range from 2 global variables
    *
    * @param  	conn                connection to db
    *
    * @param  	query_result        result from db, contains all trading information of a specific user
    *
    */
    function populateTradeHistory($conn, $query_result): TradeHistoryList
    {
        $trade_history_list = new TradeHistoryList();

        while($row = $query_result->fetch_assoc())
        {
            $db_date_time = $row['date_purchased'];
            $row['date_purchased'] = toDDMMYYYY(explode(" ", $row['date_purchased'])[0]);
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

            $change = getArtistDayChange($row['username']);
            $ticker_info->setChange($change);

            array_push($ret, $ticker_info);
        }
        TickerInfo::sort($ret, 0, (sizeof($ret) - 1), "DESCENDING", "CHANGE");

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
        $res1 = getArtistShareHoldersInfo($conn, $artist_username);
        $res2 = searchArtistCurrentPricePerShare($conn, $artist_username);
        $pps = $res2->fetch_assoc();
        while($row = $res1->fetch_assoc())
        {
            //skip artist own buy back shares
            if($row['user_username'] != $artist_username)
            {
                $market_cap += ($row['shares_owned'] * $pps['price_per_share']);
            }
        }

        //update the market cap 
        updateArtistMarketCap($connPDO, $artist_username, $market_cap);

        return $market_cap;
    }

    /**
    * Get artist's 4-letter market tag
    *
    * @param  	artist_username   given username of the artist to search for their market tag
    *
    * @return 	ret	              a string, containing 4 letters of the artist market tag
    */
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

    /**
    * Get artist's username based on a given market tag
    *
    * @param  	artist_market_tag   given market tag of the artist to search for their username
    *
    * @return 	ret	                a string, containing the artist username
    */
    function getArtistUsernameFromTag($artist_market_tag)
    {
        $ret = StatusCodes::ErrGeneric;
        $conn = connect();

        $res = searchArtistByTicker($conn, $artist_market_tag);
        if($res->num_rows > 0)
        {
            $artist_username = $res->fetch_assoc();
            $ret = $artist_username['artist_username'];
        }

        closeCon($conn);

        return $ret;
    }

    /**
    * Get the maximum price per share of an artist within a given day
    *
    * @param  	all_pps_in_a_day   an array containing all price per share of a specific day
    *
    * @return 	ret	               maximum value in the array all_pps_in_a_day
    */
    function getMaxPPSByDay($all_pps_in_a_day)
    {
        $ret = 0;

        if(sizeof($all_pps_in_a_day) != 0)
        {
            $ret = $all_pps_in_a_day[0];

            for($i = 0; $i < sizeof($all_pps_in_a_day); $i++)
            {
                if($all_pps_in_a_day[$i] > $ret)
                {
                    $ret = $all_pps_in_a_day[$i];
                }
            }
        }

        return $ret;
    }

    /**
        * Calculates artist last 24 hours stock change
        *
        * @param  	artist_username username of artist to get the last 24-hour price change
        *
        * @return   ret             last 24 hours change, in percentage
    */
    function getArtistDayChange($artist_username)
    {
        $ret = 0;
        $conn = connect();
        $all_pps_in_a_day = array();
        $db_current_date_time = date('Y-m-d H:i:s');
        $db_current_date_time = date("Y-m-d H:i:s", strtotime("-3 days"));
        $days_ago = date("Y-m-d H:i:s", strtotime("-4 day"));

        $res = searchArtistCurrentPricePerShare($conn, $artist_username);
        $current_pps = $res->fetch_assoc();

        $res = getJSONDataWithinInterval($conn, $artist_username, $days_ago, $db_current_date_time);
        while($row = $res->fetch_assoc())
        {
            array_push($all_pps_in_a_day, $row['price_per_share']);
        }

        $prev_day_high = round(getMaxPPSByDay($all_pps_in_a_day), 2);
        //if the return value from getMaxPPSByDay is 0, it means that there was no trade going on in the previous day
        //In this case we can just return 0
        if($prev_day_high == 0)
        {
            $ret = 0;
        }
        else
        {
            //Day change is compared between yesterday's high vs current price per share
            $ret = round((($current_pps['price_per_share'] - $prev_day_high)/$prev_day_high) * 100, 2);
        }

        return $ret;
    }

    /**
        * Gets current active campaign of a selected artist
        *
        * @param  	artist_username username of artist to get current active campaigns
        *
        * @return   ret             an array of current active campaigns
    */
    function artistCurrentCampaigns($artist_username)
    {
        $ret = array();
        $conn = connect();

        $res = searchArtistCampaigns($conn, $artist_username);
        while($row = $res->fetch_assoc())
        {
            if($row['is_active'] != 0)
            {
                $campaign = new Campaign();
                $campaign->setOffering($row['offering']);
                $campaign->setMinEthos($row['minimum_ethos']);
                $campaign->setType($row['type']);
                $campaign->setDatePosted($row['date_posted']);

                array_push($ret, $campaign);
            }
        }

        closeCon($conn);
        return $ret;
    }

    /**
     * Calculates the total amount of money a user has invested in an artist, which is determined by all the past buy activities
     *
     * @param user_username         user username to determine total amount has invested
     * @param artist_username       artist username that the user has invested in
     *
     * @return ret                  Total amount a user has spent on the given artist
     */
    function getAmountInvestedBetweenUserAndArtist($user_username, $artist_username): float
    {
        $ret = 0;
        $conn = connect();
        
        $res = searchInitialPriceWhenBought($conn, $user_username, $artist_username);
        while($row = $res->fetch_assoc())
        {
            $ret += $row['price_per_share_when_bought'];
        }

        closeCon($conn);
        return $ret;
    }
?>
