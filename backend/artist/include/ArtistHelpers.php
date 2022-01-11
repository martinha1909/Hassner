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
            if(artistShareSelling($artist_username) >= artistRepurchaseShares($artist_username))
            {
                $ret = true;
            }
        }
        return $ret;
    }

    function tradeHistoryInit($username)
    {
        $conn = connect();

        echo '
            <div class="mx-auto text-center py-2 col-8">
                <h3 class="h3-blue py-2">Trade History</h3>
                <h6>From</h6>
                <input id="artist_trade_history_from" type="date" name="trade_history_from">
                <h6>To</h6>
                <input id="artist_trade_history_to" type="date" name="trade_history_to">

                <div class="my-4 mx-auto select-dark">
                    <select class="select-dropdown select-dropdown-dark" id="artist_trade_history_type">
                        <option id="artist_trade_history_type_selected" selected disabled>'.TradeHistoryType::SHARE_BOUGHT.'</option>
                        <option value="share repurchase">'.TradeHistoryType::SHARE_REPURCHASE.'</option>
                        <option value="share bought">'.TradeHistoryType::SHARE_BOUGHT.'</option>
                    </select>
                </div>

                <p id="artist_trade_history_status" class="error-msg"></p>

                <input id="artist_trade_history_btn" type="submit" class="cursor-context" role="button" value="->">
            </div>
        ';

        // printArtistShareRepurchaseTable($username);
        printArtistShareBoughtTable($username);

        $_SESSION['trade_history_type'] = 0;
        $_SESSION['trade_history_from'] = 0;
        $_SESSION['trade_history_to'] = 0;

        // echo '
        //         '..'

        //     <div class="div-hidden" id="artist_shares_bought_content">
        //         '.printArtistShareBoughtTable($username).'
        //     </div>
        // ';
        
        //         //Fetching arrays if shares repurchase was chosen
        //         if($_SESSION['trade_history_type'] == TradeHistoryType::SHARE_REPURCHASE)
        //         {
        //             $res = searchArtistBuyBackShares($conn, $username);
        //         }
        //         //Fetching arrays if shares bought was chosen
        //         else if($_SESSION['trade_history_type'] == TradeHistoryType::SHARE_BOUGHT)
        //         {
        //             $res = searchSharesBoughtFromArtist($conn, $username);
        //         }

                // $trade_history_list = populateTradeHistory($conn, $res);

                // if($trade_history_list->getListSize() > 0)
                // {
                //     //Price displays the highest and lowest trades of the day
                //     //Volumn displays how many total shares of the artist that was traded that day
                //     //Value displays total amount of siliqas that was traded of the artist that day
                //     //Trades displays the total number of trades that day
                //     echo '
                //         <div class="py-4">
                //             <table class="table">
                //                 <thead>
                //                     <tr>
                //                         <th scope="col">Date</th>
                //                         <th scope="col">Price(HIGH/LOW)</th>
                //                         <th scope="col">Volume</th>
                //                         <th scope="col">Value</th>
                //                         <th scope="col">Trades</th>
                //                     </tr>
                //                 </thead>
                //                 <tbody>
                //     ';

                //     $trade_history_list->addListToTable();
                // }
                // else
                // {
                //     echo '<h5>No trades found</h5>';
                // }

        //     echo '
        //                     </tbody>
        //                 </table>
        //             </div>
        //     ';
        // echo '</div>';
    }

    //Stock Ticker temporary waiting for backend to fill out values
    function displayTicker()
    {
        $tickers = getAllArtistTickers();
        echo '
                    <div class="marquee">
                        <p>
        ';
        for($i = 0; $i < sizeof($tickers); $i++)
        {
            echo '
                            <mark class="font-weight-bold">'.$tickers[$i]->getTag().'</mark>
            ';
            
            if($tickers[$i]->getChange() < 0)
            {
                echo '
                            <mark class="markup-red">'.$tickers[$i]->getChange().'%</mark>
                ';
            }
            else if($tickers[$i]->getChange() > 0)
            {
                echo '
                            <mark class="markup-green">+'.$tickers[$i]->getChange().'%</mark>
                ';
            }
            if($tickers[$i]->getChange() == 0)
            {
                echo '
                            <mark>'.$tickers[$i]->getChange().'%</mark>
                ';
            }
            echo '<mark> '.$tickers[$i]->getPPS().'</mark>';
            echo " | ";
        }
        echo '
                    </p>
                </div>
        ';
    }

    function getArtistShareRepurchase($artist_username)
    {
        $ret = 0;
        $conn = connect();

        $res = searchArtistRepurchaseShares($conn, $artist_username);
        $ret = $res->fetch_assoc();

        return $ret['shares_repurchase'];
    }

    function getAmountAvailableForRepurchase($artist_username): int
    {
        $ret = 0;
        $conn = connect();

        $res = searchSellOrderByArtist($conn, $artist_username);
        while($row = $res->fetch_assoc())
        {
            //skipping their own orders
            if($row['user_username'] != $artist_username)
            {
                $ret += $row['no_of_share'];
            }
        }

        return $ret;
    }

    function calculatePriceForAllRepurchase($artist_username): float
    {
        $ret = 0;
        $conn = connect();

        $res = searchSellOrderByArtist($conn, $artist_username);
        while($row = $res->fetch_assoc())
        {
            //Skipping their own orders
            if($row['user_username'] != $artist_username)
            {
                $price_per_sell_order = $row['no_of_share'] * $row['selling_price'];
                $ret += $price_per_sell_order;
            }
        }

        return $ret;
    }

    function getAllSellOrderIDsForRepurchase($artist_username)
    {
        $ret = array();
        $conn = connect();

        $res = searchSellOrderByArtist($conn, $artist_username);
        while($row = $res->fetch_assoc())
        {
            //Skipping their own orders
            if($row['user_username'] != $artist_username)
            {
                array_push($ret, $row['id']);
            }
        }

        return $ret;
    }

    function getAllRepurchaseSellOrdersInfo($artist_username)
    {
        $ret = array();
        $conn = connect();

        $res = searchSellOrderByArtist($conn, $artist_username);
        while($row = $res->fetch_assoc())
        {
            //Skipping their own orders
            if($row['user_username'] != $artist_username)
            {
                //the fields that are being sent as "" means we do not need those fields for this case so they can be empty
                $sell_order_item_info = new SellOrder($row['id'], 
                                                    $row['user_username'], 
                                                    "", 
                                                    $row['selling_price'], 
                                                    $row['no_of_share'], 
                                                    "", 
                                                    "");
                array_push($ret, $sell_order_item_info);
            }
        }

        return $ret;
    }

    function fetchAllInvestorsOfArtist($artist_username)
    {
        $ret = array();
        $conn = connect();

        $res = getArtistShareHoldersInfo($conn, $artist_username);
        while($row = $res->fetch_assoc())
        {
            $investor = new Investor();
            //Skips artist own share repurchase
            if($row['user_username'] != $artist_username)
            {
                $res_account_info = searchAccount($conn, $row['user_username']);
                $investor_info = $res_account_info->fetch_assoc();

                $amount_invested = getAmountInvestedBetweenUserAndArtist($row['user_username'], $artist_username);
                $campaigns_won = getUserCampaignWonByArtist($row['user_username'], $artist_username);
                $campaigns_participated = getUserCampaignParticipatedByArtist($row['user_username'], $artist_username);

                $investor->setUsername($row['user_username']);
                $investor->setEmail($investor_info['email']);
                $investor->setAmountInvested($amount_invested);
                $investor->setCampaignsWon($campaigns_won);
                $investor->setCampaignsParticipated($campaigns_participated);

                array_push($ret, $investor);
            }
        }

        closeCon($conn);

        //By default shows the top investor
        if($_SESSION['artist_investor_amount_invested_sort'] == 0)
        {
            Investor::sort($ret, 0, (sizeof($ret)-1), "Descending", "Amount Invested");
        }
        else if($_SESSION['artist_investor_amount_invested_sort'] == 1)
        {
            Investor::sort($ret, 0, (sizeof($ret)-1), "Ascending", "Amount Invested");
        }

        return $ret;
    }

    function fetchArtistCampaignWinners($artist_username, &$campaign_info)
    {
        $current_date = dayAndTimeSplitter(getCurrentDate("America/Edmonton"));
        $conn = connect();
        $ret = array();

        $res = getArtistShareHoldersInfo($conn, $artist_username);
        while($row = $res->fetch_assoc())
        {
            //Skips artist own share repurchase
            if($row['user_username'] != $artist_username)
            {
                $res_winning_campaign = searchArtistCampaigns($conn, $artist_username);
                while($row_winning_campaign = $res_winning_campaign->fetch_assoc())
                {
                    if($row_winning_campaign['type'] == "raffle" && $row_winning_campaign['winner'] == $row['user_username'])
                    {
                        $investor = new Investor();
                        $campaign = new Campaign();

                        $res_account_info = searchAccount($conn, $row['user_username']);
                        $investor_info = $res_account_info->fetch_assoc();

                        $amount_invested = getAmountInvestedBetweenUserAndArtist($row['user_username'], $artist_username);

                        $investor->setUsername($row['user_username']);
                        $investor->setEmail($investor_info['email']);
                        $investor->setAmountInvested($amount_invested);

                        if($row_winning_campaign['date_expires'] != "0000-00-00 00:00:00")
                        {
                            $campaign->setDateExpires(dbDateTimeParser($row_winning_campaign['date_expires']));
                            $campaign->setDeliverProgress(CampaignDeliverProgress::IN_PROGRESS);
                        }
                        else
                        {
                            //For now assume all campaigns are not delivered, TODO: have a checkbox or something for artist to check when they deliver 
                            //the campaign promises
                            $campaign->setDeliverProgress(CampaignDeliverProgress::NEGATIVE);
                            $campaign->setDateExpires("Expired");
                        }
                        $campaign->setOffering($row_winning_campaign['offering']);

                        array_push($ret, $investor);
                        array_push($campaign_info, $campaign);
                    }
                }
            }
        }

        closeCon($conn);
        return $ret;
    }
?>