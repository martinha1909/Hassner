<?php
    /**
    * Prints campaigns that a user is currently participating
    *
    * @param  	username	    user username to query campaigns to display for
    */
    function printParticipatingCampaignTable($username)
    {
        $participating_campaigns = fetchInvestedArtistCampaigns($username);

        if (sizeof($participating_campaigns) > 0) 
        {
            echo '
                <div class="row">
            ';

            for ($i = 0; $i < sizeof($participating_campaigns); $i++) 
            {
                $artist_market_tag = getArtistMarketTag($participating_campaigns[$i]->getArtistUsername());
                echo '
                    <div class="campaign-box-participating col-2.5">
                ';

                if($participating_campaigns[$i]->getType() == CampaignType::BENCHMARK)
                {
                    echo '
                        <h3 class="h3-blue">'.$artist_market_tag.'
                            <b class="text-dark float-right">♦</b>
                        </h3>
                    ';
                }
                else if($participating_campaigns[$i]->getType() == CampaignType::RAFFLE)
                {
                    //Add this to line 32 if we want to include the winning chance
                    // <b class="font-size-15">('.$participating_campaigns[$i]->getWinningChance().'%)</b>
                    echo '
                        <h3 class="h3-blue">'.$artist_market_tag.'
                            <b class="text-dark float-right">♣</b>
                        </h3>
                    ';
                }

                echo '
                        <b class="text-black">❖ '.$participating_campaigns[$i]->getOffering().'</b>
                        <p class="text-black text-bold">⌛ '.$participating_campaigns[$i]->getTimeLeft().'</p>
                        <b class="text-black">⌖ '.$participating_campaigns[$i]->getMinEthos().'</b>
                    </div>
                ';
            }
            echo '
                </div>
            ';
        }
        else
        {
            echo '
                <h6>No campaign found</h6>
            ';
        }
    }

    /**
    * Prints campaigns that a user has participated in the past
    *
    * @param  	username	    user username to query campaigns to display for
    */
    function printPastParticipatedCampaignTable($username)
    {
        $participated_campaigns = fetchParticipatedCampaigns($username);

        if (sizeof($participated_campaigns) > 0) 
        {
            echo '
                <div class="row">
            ';

            for ($i = 0; $i < sizeof($participated_campaigns); $i++) 
            {
                $artist_market_tag = getArtistMarketTag($participated_campaigns[$i]->getArtistUsername());
                if($participated_campaigns[$i]->getWinner() == $username)
                {
                    echo '
                        <div class="campaign-box-participated-winner col-2.5">
                    ';
                }
                else
                {
                    echo '
                        <div class="campaign-box-participated col-2.5">
                    ';
                }

                if($participated_campaigns[$i]->getType() == CampaignType::BENCHMARK)
                {
                    echo '
                        <h3 class="h3-white">'.$artist_market_tag.'
                            <b class="text-white float-right">♦</b>
                        </h3>
                    ';
                }
                else if($participated_campaigns[$i]->getType() == CampaignType::RAFFLE)
                {
                    echo '
                        <h3 class="h3-white">'.$artist_market_tag.'
                            <b class="text-white float-right">♣</b>
                        </h3>
                    ';
                }

                echo '
                        <b class="text-black">❖ '.$participated_campaigns[$i]->getOffering().'</b>
                        <p class="text-black">⌛ '.$participated_campaigns[$i]->getDateExpires().'</p>
                ';

                if($participated_campaigns[$i]->getType() == CampaignType::BENCHMARK)
                {
                    echo '
                        <b class="text-black">Win: N/A</b>
                    ';
                }
                else
                {
                    if($participated_campaigns[$i]->getWinner() == $username)
                    {
                        echo '
                            <b class="text-orange">Win: Yes</b>
                        ';
                    }
                    else
                    {
                        echo '
                            <b class="text-black">Win: No</b>
                        ';
                    }
                }
                        
                echo '
                    </div>
                ';
            }
            echo '
                </div>
            ';
        }
        else 
        {
            echo '<h6>No campaigns participated</h6>';
        }
    }

    /**
    * Prints potential participation campaigns for a user. 
    * The purpose of this is to encourage listeners to discover artists faster as well as for new users to discover campaigns faster
    * The sequence of this function is as follows:
    * If a user has been invested in some artists, then we fetch the artist campaigns that the user has completed 80% or more first
    * If the above condition returns 0 campaigns, we then fetch all the campaigns that the user could potential participate, which is 0% < x < 80%
    * If both conditions above return 0, then it means that a user is a new user and has not invested in any artists, we then browse the most trending campaigns to display
    * Note: a trending campaign is determined by having the most participating users at that given time
    * Maximum number of campaigns to display is 5
    *
    * @param  	username	    user username to query campaigns to display for
    */
    function printNearParticipationCampaignTable($username)
    {
        $campaign_display_max = 5;
        $near_parti_campaigns = fetchNearParticipationCampaign($username);

        if (sizeof($near_parti_campaigns) == 0) 
        {
            $near_parti_campaigns = fetchPotentialParticipationCampaign($username);
            if(sizeof($near_parti_campaigns) == 0)
            {
                $near_parti_campaigns = fetchTrendingCampaign($username);
            }
        }

        if(sizeof($near_parti_campaigns) < 5)
        {
            $campaign_display_max = sizeof($near_parti_campaigns);
        }

        echo '
            <div class="row">
        ';

        for ($i = 0; $i < $campaign_display_max; $i++) 
        {
            $artist_market_tag = getArtistMarketTag($near_parti_campaigns[$i]->getArtistUsername());
            echo '
                <div class="campaign-box-near col-2.5">
            ';

            if($near_parti_campaigns[$i]->getType() == CampaignType::BENCHMARK)
            {
                echo '
                    <form action="../../backend/listener/ArtistTagShareInfoBackend.php" method="post">
                        <h3 class="h3-white"><input name = "artist_tag" type = "submit" class="input-no-border text-white text-bold" role="button" value = "'.$artist_market_tag.'">
                            <b class="text-white float-right">♦</b>
                        </h3>
                    </form>
                ';
            }
            else if($near_parti_campaigns[$i]->getType() == CampaignType::RAFFLE)
            {
                echo '
                    <form action="../../backend/listener/ArtistTagShareInfoBackend.php" method="post">
                        <h3 class="h3-white"><input name = "artist_tag" type = "submit" class="input-no-border text-white text-bold" role="button" value = "'.$artist_market_tag.'">
                            <b class="text-white float-right">♣</b>
                        </h3>
                    </form>
                ';
            }

            echo '
                    <b class="text-black">❖ '.$near_parti_campaigns[$i]->getOffering().'</b>
                    <p class="text-black text-bold">⌛ '.$near_parti_campaigns[$i]->getTimeLeft().'</p>
                    <b class="text-black">⌖ '.$near_parti_campaigns[$i]->getUserOwnedEthos().'/'.$near_parti_campaigns[$i]->getMinEthos().'('.$near_parti_campaigns[$i]->getProgress().'%)</b>
                </div>
            ';
        }
        echo '
            </div>
        ';
    }

    function printArtistCurrentCampaignTable($artist_username)
    {
        $current_campaigns = fetchArtistCurrentCampaigns($artist_username);

        if (sizeof($current_campaigns) > 0) {
            echo '
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Offering</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Eligible Participants</th>
                                    <th scope="col">Minimum Ethos</th>
                                    <th scope="col">Time left</th>
                                    <th scope="col">Roll Result</th>
                                    <th scope="col">Time Released</th>
                                </tr>
                            </thead>
                            <tbody>';

            for ($i = 0; $i < sizeof($current_campaigns); $i++) {
                echo '
                                <tr>
                                    <th>' . $current_campaigns[$i]->getOffering() . '</th>
                                    <td>' . $current_campaigns[$i]->getType() . '</td>
                                    <td>' . $current_campaigns[$i]->getEligibleParticipants() . '</td>
                                    <td>' . $current_campaigns[$i]->getMinEthos() . '</td>
                                    <td>' . $current_campaigns[$i]->getTimeLeft() . '</td>
                                    <td>' . $current_campaigns[$i]->getWinner() . '</td>
                                    <td>' . $current_campaigns[$i]->getDatePosted() . '</td>
                                </tr>
                    ';
            }
            echo '
                            </tbody>
                        </table>
                ';
        }
    }

    function printArtistExpiredCampaignTable($artist_username)
    {
        $expired_campaigns = fetchArtistExpiredCampaigns($artist_username);

        if (sizeof($expired_campaigns) > 0) 
        {
            echo '
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Offering</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Eligible Participants</th>
                                        <th scope="col">Minimum Ethos</th>
                                        <th scope="col">Roll Result</th>
                                        <th scope="col">Time Released</th>
                                    </tr>
                                </thead>
                                <tbody>
            ';

            for ($i = 0; $i < sizeof($expired_campaigns); $i++) 
            {
                echo '
                                    <tr>
                                        <th>' . $expired_campaigns[$i]->getOffering() . '</th>
                                        <td>' . $expired_campaigns[$i]->getType() . '</td>
                                        <td>' . $expired_campaigns[$i]->getEligibleParticipants() . '</td>
                                        <td>' . $expired_campaigns[$i]->getMinEthos() . '</td>
                                        <td>' . $expired_campaigns[$i]->getWinner() . '</td>
                                        <td>' . $expired_campaigns[$i]->getDatePosted() . '</td>
                                    </tr>
                ';
            }
            echo '
                                </tbody>
                            </table>
            ';
        }
    }

    function printArtistApexInvestors($artist_username)
    {
        $investors = fetchAllInvestorsOfArtist($artist_username);

        if(sizeof($investors) > 0)
        {
            echo '
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
            ';
            if($_SESSION['artist_investor_amount_invested_sort'] == 0)
            {
                echo '
                            <form action = "../../backend/artist/include/SortInvestorAmountInvested.php" method="post">
                                <th scope="col"><input type = "submit" class="th-dark" role="button" aria-pressed="true" value = "Amount Invested ($) ↑"></th>
                            </form>
                ';
            }
            else if($_SESSION['artist_investor_amount_invested_sort'] == 1)
            {
                echo '
                            <form action = "../../backend/artist/include/SortInvestorAmountInvested.php" method="post">
                                <th scope="col"><input type = "submit" class="th-dark" role="button" aria-pressed="true" value = "Amount Invested ($) ↓"></th>
                            </form>
                ';
            }
            echo '
                            <th scope="col">Campaigns Participated</th>
                            <th scope="col">Campaigns Won</th>
                        </tr>
                    </thead>
                    <tbody>
            ';

            for ($i = 0; $i < sizeof($investors); $i++) 
            {
                echo '
                                    <tr>
                                        <th>' . $investors[$i]->getUsername() . '</th>
                                        <td>' . $investors[$i]->getEmail() . '</td>
                                        <td>' . $investors[$i]->getAmountInvested() . '</td>
                                        <td>' . $investors[$i]->getCampaignsParticipated() . '</td>
                                        <td>' . $investors[$i]->getCampaignsWon() . '</td>
                                    </tr>
                ';
            }
            echo '
                                </tbody>
                            </table>
            ';
        }
        else
        {
            echo '
                <h4>No investors found</h4>
            ';
        }
    }

    function printArtistRaffleCampaignsWinners($artist_username)
    {
        $campaign_info = array();
        $campaign_winners = fetchArtistCampaignWinners($artist_username, $campaign_info);

        if(sizeof($campaign_winners) > 0)
        {
            echo '
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">Amount Invested ($)</th>
                            <th scope="col">Fulfilled</th>
                            <th scope="col">Campaign Offering</th>
                            <th scope="col">End Date</th>
                        </tr>
                    </thead>
                    <tbody>
            ';

            for ($i = 0; $i < sizeof($campaign_winners); $i++) 
            {
                echo '
                                    <tr>
                                        <th>' . $campaign_winners[$i]->getUsername() . '</th>
                                        <td>' . $campaign_winners[$i]->getEmail() . '</td>
                                        <td>' . $campaign_winners[$i]->getAmountInvested() . '</td>
                ';
                if($campaign_info[$i]->getDeliverProgress() == CampaignDeliverProgress::POSITIVE)
                {
                    echo '<td>✔️</td>';
                }
                else if($campaign_info[$i]->getDeliverProgress() == CampaignDeliverProgress::NEGATIVE)
                {
                    echo '<td>❌</td>';
                }
                elseif($campaign_info[$i]->getDeliverProgress() == CampaignDeliverProgress::IN_PROGRESS)
                {
                    echo '<td>⌛</td>';
                }


                echo '
                                        <td>' . $campaign_info[$i]->getOffering() . '</td>
                                        <td>' . dbDateTimeParser($campaign_info[$i]->getDateExpires()) . '</td>
                                    </tr>
                ';
            }
            echo '
                                </tbody>
                            </table>
            ';
        }
        else
        {
            echo '
                <h4>No investors found</h4>
            ';
        }
    }

    function printArtistQuotesTab($artist_username, $account_info)
    {
        $shareholder_list = fetchCurrentShareholders($_SESSION['username']);
        $market_cap = calculateMarketCap($_SESSION['username']);
        $high = getHighestOrLowestPPS($_SESSION['username'], "MAX");
        $low = getHighestOrLowestPPS($_SESSION['username'], "MIN");

        echo '
            <div class="text-center py-4">
                <h6>Price Per Share: $' . $account_info['price_per_share'] . '</h6>
                <h6>Volumn: ' . $account_info['Share_Distributed'] . '</h6>
                <h6>Current Shareholders: ' . $shareholder_list->num_rows . '</h6>
                <h6>Market cap: $' . $market_cap . '</h6>
                <h6>Day High: $' . $high . '</h6>
                <h6>Day Low: $' . $low . '</h6>
                <br>
                <p id="inject_success" class="suc-msg"></p>
                <input name="display_type" type="submit" class="btn btn-primary py-2" id="inject_shares_btn" value="Inject More Shares">
            </div>

            <div class="div-hidden" id="inject_shares_content">
                <div class="col-6 mx-auto">
                    <p class="text-center text-blue" style="font-weight: bold;">How many shares are you injecting?</p>
                    <input type="text" id="shares_injecting" class="form-control form-control-sm col-4 mx-auto" placeholder="Enter amount">
                    <p>Comments</p>
                    <input type="text" id="comment" class="form-control form-control-sm py-3" placeholder="Enter comment">
                    <p id="inject_error" class="error-msg"></p>
                    <div class="text-center">
                    <input type = "submit" id="confirm_inject_btn" class="btn btn-primary my-4" role="button" value = "Save">  
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button id = "'.GraphOption::ONE_DAY.'" class="btn btn-secondary">'.GraphOption::ONE_DAY.'</button>
                <button id = "'.GraphOption::FIVE_DAY.'" class="btn btn-secondary" aria-pressed="true">'.GraphOption::FIVE_DAY.'</button>
                <button id = "'.GraphOption::ONE_MONTH.'" class="btn btn-secondary" aria-pressed="true">'.GraphOption::ONE_MONTH.'</button>
                <button id = "'.GraphOption::SIX_MONTH.'" class="btn btn-secondary" aria-pressed="true">'.GraphOption::SIX_MONTH.'</button>
                <button id = "'.GraphOption::YEAR_TO_DATE.'" class="btn btn-secondary" aria-pressed="true">'.GraphOption::YEAR_TO_DATE.'</button>
                <button id = "'.GraphOption::ONE_YEAR.'" class="btn btn-secondary" aria-pressed="true">'.GraphOption::ONE_YEAR.'</button>
                <button id = "'.GraphOption::FIVE_YEAR.'" class="btn btn-secondary" aria-pressed="true">'.GraphOption::FIVE_YEAR.'</button>
                <div class="chart-container mx-auto">
                    <canvas id="stock_graph"></canvas>
                </div>
            </div>
        ';
    }

    function printArtistBuyBackSharesTab($artist_username)
    {
        if (artistCanCreateSellOrder($artist_username)) 
        {
            echo '
                <div class="text-right mx-6">
                    <input id="artist_sell_share_btn" type="submit" class="cursor-context menu-style-invert" value="-Sell your shares">
                    <p id="artist_sell_share_success"></p>
                </div>
            ';
        }

        $max = artistRepurchaseShares($artist_username) - artistShareSelling($artist_username);
        echo '
                <div class="div-hidden" id="artist_sell_share_content">
                    <div class="text-right mx-6">
                        <h6>How many shares are you selling?</h6>
                        <div class="wrapper-searchbar">
                            <div class="container-searchbar mx-auto">
                                <label>
                                    <input type="range" min="1" max=' . $max . ' value="1" class="slider" id="myRange">
                                    <p>Quantity: <span id="demo"></span></p>
                                    <p id="artist_sell_share_status"></p>
                                    <input id="artist_post_sell_order_btn" type="submit" class="btn btn-primary my-2 py-2" role="button" value="Post">
                                </label> 
                            </div>
                        </div>
                    </div>
                </div>
        ';

        $amount_repurchase_available = getAmountAvailableForRepurchase($artist_username);
        $price_for_all_available_repurchase = calculatePriceForAllRepurchase($artist_username);
        $owned_shares = getArtistShareRepurchase($artist_username);

        //Only to be used if artist clicks the button to buy back all shares that are being sold
        $_SESSION['repurchase_sell_orders'] = getAllRepurchaseSellOrdersInfo($artist_username);

        echo '
            <div class="text-center px-4">
                <h6>Your owned shares: '.$owned_shares.'</h6>
                <h6>Shares available for repurchase: '.$amount_repurchase_available.'</h6>
                <p id="buy_back_success" class="suc-msg"></p>
            </div>
        ';

        sellOrderInit();

        buyBackableOrdersInit($artist_username);

        if($amount_repurchase_available > 0)
        {
            echo '
                        </tbody>
                    </table>
                    <form class="text-center my-6" action="../../backend/artist/RepurchaseAllSharesBackend.php" method="post">
                        <input type="submit" class="btn btn-primary py-2" value="Purchase all '.$amount_repurchase_available.' at $'.$price_for_all_available_repurchase.'">
                    </form>
            ';
        }
    }

    function printArtistHistoryTab($artist_username)
    {

        //Buy Back shares history 
        echo '
            <div class="mx-auto text-center py-2 col-6">
                <h3 class="h3-blue">Buy Back History</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Price($)</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Seller</th>
                        </tr>
                    </thead>
                    <tbody>
        ';

        $sellers = array();
        $prices = array();
        $quantities = array();
        $date_purchase = array();

        buyHistoryInit($sellers, $prices, $quantities, $date_purchase, $_SESSION['username']);

        for ($i = 0; $i < sizeof($sellers); $i++) {
            echo '
                        <tr>
                            <td>' . $date_purchase[$i] . '</td>
                            <td>' . $prices[$i] . '</td>
                            <td>' . $quantities[$i] . '</td>
                            <td>' . $sellers[$i] . '</td>
                        </tr>
            ';
        }

        echo '
                    </tbody>
                </table>
            </div>
        ';

        tradeHistoryInit($_SESSION['username']);

        echo '<h3 class="h3-blue">Inject history</h3>';

        echo injectionHistoryInit($_SESSION['username']);
    }

    function printArtistTradeHistoryTable($artist_username)
    {
        echo '
            <div class="div-hidden" id="trade_history_found">
                <div class="py-4">
                    <table class="table" id="trade_history_table">
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Price(HIGH/LOW)</th>
                                <th scope="col">Volume</th>
                                <th scope="col">Value</th>
                                <th scope="col">Trades</th>
                            </tr>
                        </thead>
                        <tbody id="trade_history_table_body">
                        </tbody>
                    </table>
                </div>
            </div>

            <h5 class="error-msg" id="trade_history_not_found"></h5>
        ';
    }

    function printUserCurrentArtistCampaign($artist_username)
    {
        $current_campaigns = artistCurrentCampaigns($artist_username);

        if($_SESSION['artist_found'])
        {
            $artist_market_tag = getArtistMarketTag($artist_username);
            echo '
                <h3 data-toggle="tooltip" title="Here the artist offers rewards in return for users buying shares." class="h3-blue tooltip-pointer py-5">Current Campaigns</h3>
                <div class="row">
            ';

            for($i = 0; $i < sizeof($current_campaigns); $i++)
            {
                echo '
                    <div class="campaign-box-participating col-2.5">
                ';
                $type = "Error in parsing type";
                if($current_campaigns[$i]->getType() == CampaignType::RAFFLE)
                {
                    $type = "♣";
                }
                else if($current_campaigns[$i]->getType() == CampaignType::BENCHMARK)
                {
                    $type = "♦";
                }
                echo '
                        <h3 class="h3-blue">'.$artist_market_tag.'
                            <b class="text-dark float-right">'.$type.'</b>
                        </h3>
                        <b class="text-black">🤲 '.$current_campaigns[$i]->getOffering().'</b>
                        <p class="text-black text-bold">⌛ '.dbDateTimeParser($current_campaigns[$i]->getDatePosted()).'</p>
                        <b class="text-black">⌖ '.$current_campaigns[$i]->getMinEthos().'</b>
                    </div>
                ';
            }

            echo '
                </div>
            ';
        }
    }

    function printUserBuyHistoryTable($user_username): string
    {
        $ret = "";

        $sellers = array();
        $prices = array();
        $quantities = array();
        $date_purchase = array();

        buyHistoryInit($sellers, $prices, $quantities, $date_purchase, $user_username);
        $ret .= '
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Seller</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Date Purchased</th>
                    </tr>
                </thead>
                <tbody>
        ';

        

        for ($i = 0; $i < sizeof($sellers); $i++) {
            $ret .= '
                        <tr>
                            <td>' . $sellers[$i] . '</td>
                            <td>' . $prices[$i] . '</td>
                            <td>' . $quantities[$i] . '</td>
                            <td>' . $date_purchase[$i] . '</td>
                        </tr>
            ';
        }

        $ret .= '
                </tbody>
            </table>   
        ';

        return $ret;
    }

    function printOwnedSharesTable($user_username)
    {
        $conn = connect();
        $res = searchUserInvestedArtists($conn, $user_username);
        if($res->num_rows > 0)
        {
            $artist_count = 0;
            //Each row will contain 1 artist
            while($row = $res->fetch_assoc())
            {
                if($row['shares_owned'] <= 0)
                {
                    continue;
                }
                $artist_count++;
                $buy_history_instances = array();
                $total_amount_gain = 0;
                $total_amount_spent = 0;
                //Total percent change with respected to the share price when bought vs the market price now
                $total_percentage_change = 0;
                $owned_shares = $row['shares_owned'];
                $artist_username = $row['artist_username'];
                $artist_market_price = getArtistPricePerShare($artist_username);

                $res_buy_history = searchSpecificInvestment($conn, $user_username, $artist_username);
                while($row_history = $res_buy_history->fetch_assoc())
                {
                    $buy_history = new BuyHistory();

                    $buy_history->setID($row_history['id']);
                    $buy_history->setBuyer($row_history['user_username']);
                    $buy_history->setSeller($row_history['seller_username']);
                    $buy_history->setArtist($row_history['artist_username']);
                    $buy_history->setNoOfShareBought($row_history['no_of_share_bought']);
                    $buy_history->setPPS($row_history['price_per_share_when_bought']);
                    $buy_history->setDatePurchased($row_history['date_purchased']);

                    array_push($buy_history_instances, $buy_history);
                    $total_amount_spent +=  $row_history['price_per_share_when_bought'] * $row_history['no_of_share_bought'];
                }

                //we want to work our way down from the highest price per share when the user bought, just to show the potential amount increase to be greatest
                BuyHistory::sort($buy_history_instances, 0, sizeof($buy_history_instances) - 1, "DESCENDING", "PPS");

                $buy_history_index = 0;
                $res_sell_history = searchSellHistoryByUserAndArtist($conn, $user_username, $artist_username);
                $row_sell_history = $res_sell_history->fetch_assoc();
                while(true)
                {
                    if($row_sell_history == null || $buy_history_index >= sizeof($buy_history_instances))
                    {
                        break;
                    }
                    if($buy_history_instances[$buy_history_index]->getNoOfShareBought() > $row_sell_history['amount_sold'])
                    {
                        $total_amount_spent = $total_amount_spent - ($row_sell_history['amount_sold'] * $buy_history_instances[$buy_history_index]->getPPS());
                        $new_buy_history_no_of_share = $buy_history_instances[$buy_history_index]->getNoOfShareBought() - $row_sell_history['amount_sold'];
                        $buy_history_instances[$buy_history_index]->setNoOfShareBought($new_buy_history_no_of_share);
                        $row_sell_history = $res_sell_history->fetch_assoc();
                    }
                    else
                    {
                        $total_amount_spent = $total_amount_spent - ($buy_history_instances[$buy_history_index]->getNoOfShareBought() * $buy_history_instances[$buy_history_index]->getPPS());
                        $row['amount_sold'] = $row_sell_history['amount_sold'] - $buy_history_instances[$buy_history_index]->getNoOfShareBought();
                        $buy_history_instances[$buy_history_index]->setNoOfShareBought(0);
                        $buy_history_index++;
                    }
                }

                //Showing the total percentage change from when the user bought shares (price can be different at each times user bought them, so we have to take that into account)
                $total_percentage_change = round(((($artist_market_price * $owned_shares) - $total_amount_spent)/$total_amount_spent) * 100, 2);
                $total_amount_gain = round(($artist_market_price * $owned_shares) - $total_amount_spent, 2);

                echo '
                    <div class="portfolio-box-owned-shares">
                ';
                if($total_percentage_change > 0)
                {
                    echo '
                        <form action="../../backend/artist/ArtistShareInfoBackend.php" method="post">
                            <input name = "artist_name" class="input-no-border text-bold" type = "submit" id="abc_blue" role="button" value = "'.$artist_username.'"><b class="portfolio-percentage-positive">+'.$total_percentage_change.'%</b><br>
                        </form>
                        <b class="portfolio-shareamount">'.$owned_shares.'x</b><b class="portfolio-gain">+$'.$total_amount_gain.'</b>
                    ';
                }
                else
                {
                    echo '
                        <form action="../../backend/artist/ArtistShareInfoBackend.php" method="post">
                            <input name = "artist_name" class="input-no-border text-bold" id ="abc_blue" type = "submit" role="button" value = "'.$artist_username.'"><b class="portfolio-percentage-negative">'.$total_percentage_change.'%</b><br>
                        </form>
                        <b class="portfolio-shareamount">'.$owned_shares.'x</b><b class="portfolio-loss">$'.$total_amount_gain.'</b>
                    ';
                }
                echo '
                    </div>
                ';
            }
            if($artist_count == 0)
            {
                echo '<h4 class="h4-blue">No invested artist found</h4>';
            }
        }
        else
        {
            echo '<h4 class="h4-blue">You have not invested in any artists</h4>';
        }

        closeCon($conn);
    }

    function printOpenBuyTable($user_username)
    {
        $conn = connect();
        $res = searchUserBuyOrders($conn, $user_username);
        if($res->num_rows > 0)
        {
            echo '
                <div>
                <h3 class="py-2 mx-2">Buy Order</h3>
            ';
            while($row = $res->fetch_assoc())
            {
                $limit_stop = "no Limit/Stop";
                $artist_username = $row['artist_username'];
                $artist_market_price = getArtistPricePerShare($artist_username);
                $artist_market_tag = getArtistMarketTag($artist_username);
                $amount_spending = $artist_market_price;
                
                if($row['siliqas_requested'] == -1)
                {
                    if($row['buy_limit'] == -1)
                    {
                        $limit_stop = "Stop: ".$row['buy_stop'];
                        $amount_spending = $row['buy_stop'];
                    }
                    else if($row['buy_stop'] == -1)
                    {
                        $limit_stop = "Limit: ".$row['buy_limit'];
                        $amount_spending = $row['buy_limit'];
                    }
                    else if($row['buy_limit'] != -1 && $row['buy_stop'] != -1)
                    {
                        $limit_stop = "Limit: ".$row['buy_limit']."/Stop: ".$row['buy_stop'];
                    }
                }
                echo '
                    <div class="portfolio-box-open-order">
                        <form action="../../backend/listener/RemoveBuyOrderBackend.php" method="post">
                            <input name="remove_id['.$row['id'].']" class="open-order-cancel" type="submit" role="button" value="⊘">
                        </form>
                        <form 
                        <form action="../../backend/listener/TagToArtistShareInfoSwitcher.php" method="post">
                            <input name = "artist_ticker" class="input-no-border text-bold" type = "submit" id="abc_blue" role="button" value = "'.$artist_market_tag.'"><b class="portfolio-sellorder">-'.$amount_spending.'</b><br>
                        </form>
                        <b class="portfolio-shareamount-openorder">'.$row['quantity'].'x</b><b class="portfolio-limitstop">'.$limit_stop.'</b>
                    </div>
                ';
            }
            echo '</div>';
        }

        closeCon($conn);
    }

    function printOpenSellTable($user_username)
    {
        $conn = connect();
        $res = searchSellOrderByUser($conn, $user_username);
        if($res->num_rows > 0)
        {
            echo '
                <div>
                <h3 class="py-2 mx-2">Sell Order</h3>
            ';
            while($row = $res->fetch_assoc())
            {
                $limit_stop = "no Limit/Stop";
                $artist_username = $row['artist_username'];
                $artist_market_price = getArtistPricePerShare($artist_username);
                $artist_market_tag = getArtistMarketTag($artist_username);
                $amount_selling = $artist_market_price;
                
                if($row['selling_price'] == -1)
                {
                    if($row['sell_limit'] == -1)
                    {
                        $limit_stop = "Stop: ".$row['sell_stop'];
                        $amount_selling = $row['sell_stop'];
                    }
                    else if($row['sell_stop'] == -1)
                    {
                        $limit_stop = "Limit: ".$row['sell_limit'];
                        $amount_selling = $row['sell_limit'];
                    }
                    else if($row['sell_limit'] != -1 && $row['sell_stop'] != -1)
                    {
                        $limit_stop = "Limit: ".$row['sell_limit']."/Stop: ".$row['sell_stop'];
                    }
                }
                echo '
                    <div class="portfolio-box-open-order">
                        <form action="../../backend/shared/RemoveSellOrderBackend.php" method="post">
                            <input name="remove_id['.$row['id'].']" class="open-order-cancel" type="submit" role="button" value="⊘">
                        </form>
                        <form action="../../backend/listener/TagToArtistShareInfoSwitcher.php" method="post">
                            <input name = "artist_ticker" class="input-no-border text-bold" type = "submit" id="abc_blue" role="button" value = "'.$artist_market_tag.'"><b class="portfolio-sellorder">+'.$amount_selling.'</b><br>
                        </form>
                        <b class="portfolio-shareamount-openorder">'.$row['no_of_share'].'x</b><b class="portfolio-limitstop">'.$limit_stop.'</b>
                    </div>
                ';
            }
            echo '</div>';
        }

        closeCon($conn);
    }
?>