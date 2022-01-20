<?php
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
                            <b class="text-dark">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp♦</b>
                        </h3>
                    ';
                }
                else if($participating_campaigns[$i]->getType() == CampaignType::RAFFLE)
                {
                    echo '
                        <h3 class="h3-blue">'.$artist_market_tag.'
                            <b class="text-dark"><b class="font-size-15">('.$participating_campaigns[$i]->getWinningChance().'%)</b>♣</b>
                        </h3>
                    ';
                }

                echo '
                        <b class="text-black">🤲 '.$participating_campaigns[$i]->getOffering().'</b>
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
                echo '
                    <div class="campaign-box-participated col-2.5">
                ';

                if($participated_campaigns[$i]->getType() == CampaignType::BENCHMARK)
                {
                    echo '
                        <h3 class="h3-white">'.$artist_market_tag.'
                            <b class="text-white">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp♦</b>
                        </h3>
                    ';
                }
                else if($participated_campaigns[$i]->getType() == CampaignType::RAFFLE)
                {
                    echo '
                        <h3 class="h3-white">'.$artist_market_tag.'
                            <b class="text-white">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp♣</b>
                        </h3>
                    ';
                }

                echo '
                        <b class="text-black">🤲 '.$participated_campaigns[$i]->getOffering().'</b>
                        <p class="text-black">⌛ Expired</p>
                        <b class="text-black">⌖ '.$participated_campaigns[$i]->getMinEthos().'</b>
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

    function printNearParticipationCampaignTable($username)
    {
        $near_parti_campaigns = fetchNearParticipationCampaign($username);

        if (sizeof($near_parti_campaigns) > 0) 
        {
            echo '
                <div class="row">
            ';

            for ($i = 0; $i < sizeof($near_parti_campaigns); $i++) 
            {
                $artist_market_tag = getArtistMarketTag($near_parti_campaigns[$i]->getArtistUsername());
                echo '
                    <div class="campaign-box-near col-2.5">
                ';

                if($near_parti_campaigns[$i]->getType() == CampaignType::BENCHMARK)
                {
                    echo '
                        <form action="../../backend/listener/ArtistTagShareInfoBackend.php" method="post">
                            <h3 class="h3-white"><input name = "artist_tag" type = "submit" class="text-bold" role="button" value = "'.$artist_market_tag.'">
                                <b class="text-white">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp♦</b>
                            </h3>
                        </form>
                    ';
                }
                else if($near_parti_campaigns[$i]->getType() == CampaignType::RAFFLE)
                {
                    echo '
                        <form action="../../backend/listener/ArtistTagShareInfoBackend.php" method="post">
                            <h3 class="h3-white"><input name = "artist_tag" type = "submit" id="abc" style="border:1px transparent; background-color: transparent;" role="button" value = "'.$artist_market_tag.'">
                                <b class="text-white">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp♣</b>
                            </h3>
                        </form>
                    ';
                }

                echo '
                        <b class="text-black">🤲 '.$near_parti_campaigns[$i]->getOffering().'</b>
                        <p class="text-black text-bold">⌛ '.$near_parti_campaigns[$i]->getTimeLeft().'</p>
                        <b class="text-black">⌖ '.$near_parti_campaigns[$i]->getUserOwnedEthos().'/'.$near_parti_campaigns[$i]->getMinEthos().'('.$near_parti_campaigns[$i]->getProgress().'%)</b>
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
                                        <td>' . $campaign_info[$i]->getDateExpires() . '</td>
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
                                    <input id="artist_pps_selling" type="text" class="form-control" style="border-color: white;" placeholder="Enter price per share">
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
        </div>
        ';

        sellOrderInit();

        if($_SESSION['logging_mode'] == LogModes::BUY_SHARE)
        {
            if($_SESSION['status'] == StatusCodes::Success)
            {
                getStatusMessage("", "Shares bought back successfully");
            }
            else if($_SESSION['status'] == StatusCodes::ErrGeneric)
            {
                getStatusMessage("An unexpected error occured", "");
            }
        }

        askedPriceInit($artist_username, $_SESSION['account_type']);

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

        injectionHistoryInit($_SESSION['username']);
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
            echo '
                <h3 class="h3-blue py-5">Current Campaigns</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Offering</th>
                            <th scope="col">Minimum Shares</th>
                            <th scope="col">Type</th>
                            <th scope="col">Date Commenced</th>
                        </tr>
                    </thead>
                    <tbody>
            ';

            for($i = 0; $i < sizeof($current_campaigns); $i++)
            {
                $type = "Error in parsing type";
                if($current_campaigns[$i]->getType() == CampaignType::RAFFLE)
                {
                    $type = "♢";
                }
                else if($current_campaigns[$i]->getType() == CampaignType::BENCHMARK)
                {
                    $type = "♧";
                }
                echo '
                        <tr>
                            <th scope="row">' . $current_campaigns[$i]->getOffering() . '</th>
                            <td>' . $current_campaigns[$i]->getMinEthos() . '</td>
                            <td>' . $type .'</td>
                            <td>'. dbDateTimeParser($current_campaigns[$i]->getDatePosted()) .'</td>
                        </tr>
                ';
            }

            echo '
                    </tbody>
                </table>
            ';
        }
    }
?>