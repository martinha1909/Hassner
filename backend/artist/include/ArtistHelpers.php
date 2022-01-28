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
                        <option value="'.TradeHistoryType::SHARE_REPURCHASE.'">'.TradeHistoryType::SHARE_REPURCHASE.'</option>
                        <option value="'.TradeHistoryType::SHARE_BOUGHT.'">'.TradeHistoryType::SHARE_BOUGHT.'</option>
                    </select>
                </div>

                <p id="artist_trade_history_status" class="error-msg"></p>

                <input id="artist_trade_history_btn" type="submit" class="cursor-context" role="button" value="->">
            </div>
        ';
        printArtistTradeHistoryTable($username);

        $_SESSION['trade_history_type'] = 0;
        $_SESSION['trade_history_from'] = 0;
        $_SESSION['trade_history_to'] = 0;
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

    function buyBackableOrdersInit($artist_username)
    {
        $sell_orders = fetchBuyBackableOrders($artist_username);

        echo '
                <div>
                    <h3 class="h3-blue py-5 text-center">Available Sell Orders</h3>
                </div>
        ';

        if (sizeof($sell_orders) > 0) 
        {
            echo '
                <div class="col-6 mx-auto">
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
                        <tbody>
            ';

            for ($i = 0; $i < sizeof($sell_orders); $i++) 
            {
                echo '
                            <tr>
                                <th scope="row">' . $sell_orders[$i]->getID() . '</th>
                                <td>' . $sell_orders[$i]->getUser() . '</td>
                                <td>' . $sell_orders[$i]->getSellingPrice() . '</td>
                                <td>' . $sell_orders[$i]->getNoOfShare() . '</td>
                ';
                if (hasEnoughBalance($sell_orders[$i]->getSellingPrice(), $_SESSION['user_balance'])) 
                {
                    echo '
                                <td><input role="button" type="submit" class="btn btn-primary" value="Buy" id="artist_buy_back_shares_btn">
                                    <div class="div-hidden" id="artist_buy_back_content">
                                        <label for="buy_num_shares"># Shares:</label>
                                        <input type="text" class="buy_back_shares_slider_text" id="buy_num_shares" style="border:0; color:#f6931f; font-weight:bold;">
                                        <div class="slider_container">
                                            <div class="slider_slider" id="buy_num"></div>
                                        </div>
                                    </div>
                                    </div>
                                </td>
                    ';
                }
                else 
                {
                    $_SESSION['status'] = "ERROR";
                    echo '
                                <td>
                    ';
                    getStatusMessage("Not enough balance", "");
                    echo '
                                </td>
                    ';
                }
                echo '
                            </tr>
                    ';
            }
            echo '
                        </tbody>
                    </table>
                </div>
            ';
        }
        else 
        {
            echo '
                <div class="py-4 text-center">
                    <h4>No shares are currently sold by other users</h4>
                </div>
            ';
        }

        // if (sizeof($sell_orders) > 0) {
        //     //displays the buy button when user has not clicked on it
        //     if ($_SESSION['buy_asked_price'] == 0) {
        //         echo '
        //             <div class="col-6 mx-auto">
        //                 <table class="table">
        //                     <thead>
        //                         <tr>
        //                             <th scope="col">#</th>
        //                             <th scope="col">Seller username</th>
        //                             <th scope="col">Price per share(q̶)</th>
        //                             <th scope="col">Quantity</th>
        //                             <th scope="col">+</th>
        //                         </tr>
        //                     </thead>
        //                     <tbody>';
        //         for ($i = 0; $i < sizeof($sell_orders); $i++) {
        //             echo '
        //                         <tr>
        //                             <th scope="row">' . $sell_orders[$i]->getID() . '</th>
        //                             <td>' . $sell_orders[$i]->getUser() . '</td>
        //                             <td>' . $sell_orders[$i]->getSellingPrice() . '</td>
        //                             <td>' . $sell_orders[$i]->getNoOfShare() . '</td>
        //                             <form action="../../backend/shared/ToggleBuyAskedPriceBackend.php" method="post">
        //                 ';
        //             if (hasEnoughSiliqas($sell_orders[$i]->getSellingPrice(), $_SESSION['user_balance'])) {
        //                 echo '
        //                                     <td><input name="buy_user_selling_price[' . $sell_orders[$i]->getID() . ']" role="button" type="submit" class="btn btn-primary" value="Buy" onclick="window.location.reload();"></td>
        //                     ';
        //             } else {
        //                 $_SESSION['status'] = "ERROR";
        //                 echo '
        //                                     <td>
        //                     ';
        //                 getStatusMessage("Not enough siliqas", "");
        //                 echo '
        //                                     </td>
        //                     ';
        //             }
        //             echo '
        //                                 </form>
        //                             </tr>
        //                 ';
        //         }
        //         echo '
        //                 </tbody>
        //             </table>
        //         </div>
        //         ';
        //     }
        //     //replaces the Buy button with a slide bar ranging from 0 to the quantity that other users are selling
        //     else {
        //         echo '
        //                 <table class="table">
        //                     <thead>
        //                         <tr>
        //                             <th scope="col">#</th>
        //                             <th scope="col">Seller username</th>
        //                             <th scope="col">Price per share(q̶)</th>
        //                             <th scope="col">Quantity</th>
        //                             <th scope="col"></th>
        //                             <th scope="col">+</th>
        //                         </tr>
        //                     </thead>
        //                     <tbody>';
        //         for ($i = 0; $i < sizeof($sell_orders); $i++) {
        //             echo '
        //                         <tr>
        //                             <th scope="row">' . $sell_orders[$i]->getID() . '</th>
        //                             <td>' . $sell_orders[$i]->getUser() . '</td>
        //                             <td>' . $sell_orders[$i]->getSellingPrice() . '</td>
        //                             <td>' . $sell_orders[$i]->getNoOfShare() . '</td>
        //                             <td>
        //                 ';
        //             if ($_SESSION['seller'] == $sell_orders[$i]->getID()) {
        //                 $_SESSION['purchase_price'] = $sell_orders[$i]->getSellingPrice();
        //                 $_SESSION['seller_toggle'] = $sell_orders[$i]->getID();
        //                 echo '
        //                                 <form action="../../backend/artist/BuySharesBackend.php" method="post">
        //                                     <input name = "purchase_quantity" type="range" min="1" max=' . $sell_orders[$i]->getNoOfShare() . ' value="1" class="slider" id="myRange">
        //                                     <p>Quantity: <span id="demo"></span></p>
        //                                     <input name="asked_price[' . $sell_orders[$i]->getSellingPrice() . ']" type="submit" id="abc" style="border:1px transparent; background-color: transparent;" role="button" aria-pressed="true" value="->" onclick="window.location.reload();">
        //                                 </form>
        //                                 <form action="../../backend/shared/ToggleBuyAskedPriceBackend.php" method="post">
        //                                     <td><input name="buy_user_selling_price[' . $sell_orders[$i]->getID() . ']" type="submit" id="abc" style="border:1px transparent; background-color: transparent;" role="button" aria-pressed="true" value="-" onclick="window.location.reload();"></td>
        //                                 </form>
        //                     ';
        //             } else {
        //                 echo '
        //                                 <form action="../../backend/shared/ToggleBuyAskedPriceBackend.php" method="post">
        //                                 <td><input name="buy_user_selling_price[' . $sell_orders[$i]->getID() . ']" role="button" type="submit" class="btn btn-primary" value="Buy" onclick="window.location.reload();"></td>
        //                                 </form>
        //                             </td>
        //                         </tr>
        //                     ';
        //             }
        //         }
        //         echo '
        //                     </tbody>
        //                 </table>
        //             ';
        //     }
        // }
        
        // //If other users are selling shares, displays nothing
        // else {
        //     echo '
        //             <div class="py-4 text-center">
        //                 <h4>No shares are currently sold by other users</h4>
        //             </div>
        //         ';
        // }
    }

    //gets all the users that has lowest price listed with the passed artist_username param
    function fetchBuyBackableOrders($artist_username)
    {
        $debug_index = 0;
        $ret = array();
        $conn = connect();

        $result = searchAllSellOrdersNoLimitStop($conn, $artist_username);
        hx_debug(HX::QUERY, "searchAllSellOrdersNoLimitStop returned ".$result->num_rows." entries");
        while ($row = $result->fetch_assoc()) 
        {
            hx_debug(HX::QUERY, "index ".$debug_index." row data ".json_encode(($row)));
            if ($row['no_of_share'] > 0 && (strcmp($row['user_username'], $_SESSION['username']) != 0)) 
            {
                $sell_order = new SellOrder($row['id'], 
                                            $row['user_username'], 
                                            $row['artist_username'], 
                                            $row['selling_price'], 
                                            $row['no_of_share'], 
                                            $row['date_posted']);

                array_push($ret, $sell_order);
            }
            $debug_index++;
        }
        SellOrder::sort($ret, 0, (sizeof($ret) - 1), "ASCENDING", "PRICE");

        return $ret;
    }
?>