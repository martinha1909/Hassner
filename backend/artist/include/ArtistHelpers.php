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
            

            if(artistShareSelling($artist_username) < artistRepurchaseShares($artist_username))
            {
                $ret = true;
            }
        }

        return $ret;
    }

    function tradeHistoryInit($username)
    {
        $conn = connect();

        if($_SESSION['trade_history_from'] == 0 || $_SESSION['trade_history_to'] == 0)
        {
            echo '
                <div class="col-6">
                    <h3 class="h3-blue py-2">Trade History</h3>
                    <form action="../../backend/shared/TradeHistoryRangeSwitcher.php" method="post">
                        <h6>From</h6>
                        <input type="date" name="trade_history_from">
                        <h6>To</h6>
                        <input type="date" name="trade_history_to">
                        <input type="submit" class="cursor-context" role="button" aria-pressed="true" value="->">
                    </form>
            ';
        }
        else
        {
            echo '
                <div class="col-6">
                    <h3 class="h3-blue py-2">Trade History</h3>
                    <form action="../../backend/shared/TradeHistoryRangeSwitcher.php" method="post">
                        <h6>From</h6>
                        <input type="date" name="trade_history_from" value="'.$_SESSION['trade_history_from'].'">
                        <h6>To</h6>
                        <input type="date" name="trade_history_to" value="'.$_SESSION['trade_history_to'].'">
                        <input type="submit" class="cursor-context" role="button" aria-pressed="true" value="->">
                    </form>
            ';
        }

        //By default we display all shares bought activity
        if ($_SESSION['trade_history_type'] == 0) 
        {
            echo '
                <div class="py-4">
                    <form action="../../backend/shared/TradeHistoryTypeSwitcher.php" method="post">
                        <div class="select-dark">
                                <select name="trade_history_type" id="dark" onchange="this.form.submit()">
                                    <option selected disabled>'.TradeHistoryType::SHARE_BOUGHT.'</option>
                                    <option value="share repurchase">shares repurchase</option>
                                    <option value="share repurchase">shares bought</option>
                                </select>
                        </div>
                    </form>
                </div>
            ';
            $_SESSION['trade_history_type'] = TradeHistoryType::SHARE_BOUGHT;
        } 
        else
        {
            echo '
                <div class="py-4">
                    <form action="../../backend/shared/TradeHistoryTypeSwitcher.php" method="post">
                        <div class="select-dark">
                                <select name="trade_history_type" id="dark" onchange="this.form.submit()">
                                    <option selected disabled>' . $_SESSION['trade_history_type'] . '</option>
                                    <option value="'.TradeHistoryType::SHARE_REPURCHASE.'">'.TradeHistoryType::SHARE_REPURCHASE.'</option>
                                    <option value="'.TradeHistoryType::SHARE_BOUGHT.'">'.TradeHistoryType::SHARE_BOUGHT.'</option>
                                </select>
                        </div>
                    </form>
                </div>
            ';
        }
        
        if($_SESSION['trade_history_from'] == 0 || $_SESSION['trade_history_to'] == 0)
        {
            echo '<p class="error-msg">Please choose a range</p>';
        }
        else
        {
            $date = explode("-", $_SESSION['trade_history_from']);
            //reformat to match the expectation of isInTheFuture, which is of form DD-MM-YYYY
            $from_date = array($date[2], $date[1], $date[0]);
            //We don't care about time 
            $time = "00:00:00";
            $from_time = explode(":", $time);
            $to_date = explode("-", $_SESSION['trade_history_to']);
            $time = "00:00";
            $to_time = explode(":", $time);
            if(!isInTheFuture($to_date, $from_date, $to_time, $from_time))
            {
                echo '<p class="error-msg">To date has to be later than from date</p>';
            }
            else
            {
                //Fetching arrays if shares repurchase was chosen
                if($_SESSION['trade_history_type'] == TradeHistoryType::SHARE_REPURCHASE)
                {
                    $res = searchArtistBuyBackShares($conn, $username);
                }
                //Fetching arrays if shares bought was chosen
                else if($_SESSION['trade_history_type'] == TradeHistoryType::SHARE_BOUGHT)
                {
                    $res = searchSharesBoughtFromArtist($conn, $username);
                }

                $trade_history_list = populateTradeHistory($conn, $res);

                if($trade_history_list->getListSize() > 0)
                {
                    //Price displays the highest and lowest trades of the day
                    //Volumn displays how many total shares of the artist that was traded that day
                    //Value displays total amount of siliqas that was traded of the artist that day
                    //Trades displays the total number of trades that day
                    echo '
                                <div class="py-4">
                                <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Date</th>
                                        <th scope="col">Price(HIGH/LOW)</th>
                                        <th scope="col">Volume</th>
                                        <th scope="col">Value</th>
                                        <th scope="col">Trades</th>
                                    </tr>
                                </thead>
                                <tbody>
                        </div>
                    ';

                    $trade_history_list->addListToTable();
                }
                else
                {
                    echo '<h5>No trades found</h5>';
                }
            }

            echo '
                        </tbody>
                    </table>
                    </div>
            ';
        }
    }

    //Stock Ticker temporary waiting for backend to fill out values
    function displayTicker()
    {
        $tickers = getAllArtistTickers();
        echo '
                <div class="card">
                    <div class="card-body text-dark">
                        <marquee direction="left">
        ';
        for($i = 0; $i < sizeof($tickers); $i++)
        {
            echo '
                        <strong>'.$tickers[$i]->getTag().'</strong> '.$tickers[$i]->getPPS().'
            ';
            
            if($tickers[$i]->getChange() < 0)
            {
                echo '
                        <mark class="markup-red">-'.$tickers[$i]->getChange().'%</mark>
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
        }
        echo '
                        </marquee>
                    </div>
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
?>