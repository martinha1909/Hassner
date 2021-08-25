<?php
    function hassnerInit()
    {   
        $_SESSION['dependencies'] = "FRONTEND";
        $_SESSION['coins'] = 0;
        $_SESSION['display'] = 0;
        $_SESSION['sort_type'] = 0;
        $_SESSION['cad'] = 0;
        $_SESSION['edit'] = 0;
        $_SESSION['currency'] = 0;
        $_SESSION['btn_show'] = 0;
        $_SESSION['saved'] = 0;
        $_SESSION['top_rating'] = 0;
        $_SESSION['buy_sell'] = 0;
        $_SESSION['add'] = 0;
        $_SESSION['add_share'] = 0;
        $_SESSION['buy_asked_price'] = 0;
        $_SESSION['buy_market_price'] = 0;
        $_SESSION['artist_share_remove'] = 0;
        $_SESSION['share_price_remove'] = 0;
        $_SESSION['current_date'] = getCurrentDate('America/Edmonton');
    }

    function getStatusMessage($err_msg, $suc_msg)
    {
        if($_SESSION['status'] == "ERROR")
        {
            echo '<p style="color: red;">'.$err_msg.'</p>';
        }
        else if($_SESSION['status'] == "SUCCESS")
        {
            echo '<p style="color: green;">'.$suc_msg.'</p>';
        }
        $_SESSION['status'] = 0;
        $_SESSION['logging_mode'] = 0;
    }

    function getAccount($username)
    {
        $conn = connect();
        $result = searchAccount($conn, $username);
        $account = $result->fetch_assoc();
         
        return $account;
    }
    
    function getUserBalance($user_username)
    {
        $conn = connect();
        $result = searchAccount($conn, $user_username);
        $balance = $result->fetch_assoc();     
        return $balance['balance'];   
    }
    
    function getHighestOrLowestPPS($artist_username, $indicator)
    {
        if($indicator == "MAX")
        {
            $conn = connect();

            $res1 = searchArtistCurrentPricePerShare($conn, $artist_username);
            $market_price = $res1->fetch_assoc();

            $res2 = searchArtistHighestPrice($conn, $artist_username);
            $highest_asked_price = $res2->fetch_assoc();

            //if market price is higher, return that as a highest value
            if($market_price['price_per_share'] > $highest_asked_price['maximum'])
            {
                return $market_price['price_per_share'];
            }

            //if somebody is selling higher than market price and higher than other sellers, 
            //return that as a highest value
            if($market_price['price_per_share'] < $highest_asked_price['maximum'])
            {
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

            $res2 = getAskedPrices($conn, $_SESSION['username']);

            //If there are no users that are selling this artist's shares other than himself, 
            //return the market price per share 
            if($res2->num_rows == 0)
            {
                return $market_price['price_per_share'];
            }

            $res3 = searchArtistLowestPrice($conn, $artist_username);
            if($res3->num_rows == 0)
            {
                return $market_price['price_per_share'];
            }
            $lowest_asked_price = $res3->fetch_assoc();

            //if market price is lower, return that as a lowest value
            if($market_price['price_per_share'] < $lowest_asked_price['minimum'])
            {
                return $market_price['price_per_share'];
            }

            //if somebody is selling lower than market price and lower than other sellers, 
            //return that as a lowest value
            if($market_price['price_per_share'] > $lowest_asked_price['minimum'])
            {
                return $lowest_asked_price['minimum'];
            }

            //if both are the same, then return one of them, in this case return market price
            return $market_price['price_per_share'];
        }
    }

    function returnToMainPage()
    {
        if($_SESSION['account_type'] == "user")
        {
            header("Location: ../../frontend/listener/listener.php");
        }
        else if($_SESSION['account_type'] == "artist")
        {
            header("Location: ../../frontend/artist/Artist.php");
        }
    }

    function sellSiliqasInit($balance)
    {
        echo '
            <section id="login" class="py-5";>
                <div class="container">
                    <div class="col-12 mx-auto my-auto text-center">
                        <form action="../../APIs/shared/CurrencyBackend.php" method="post">
        ';

        if($_SESSION['logging_mode'] == "SELL_SILIQAS")
        {
            if($_SESSION['status'] == "EMPTY_ERR")
            {
                $_SESSION['status'] = "ERROR";
                getStatusMessage("Please fill out all fields and try again", "");
            }
            else if($_SESSION['status'] == "NOT_ENOUGH_ERR")
            {
                $_SESSION['status'] = "ERROR";
                getStatusMessage("Not enough siliqas", "");
            }
            else
            {
                getStatusMessage("An error occured", "Siliqas sold successfully");
            }
        }

        if($_SESSION['currency']==0)
        {
            echo'
                    <div style="float:none;margin:auto;" class="select-dark">
                        <select name="currency" id="dark" onchange="this.form.submit()">
                            <option selected disabled>Currency</option>
                            <option value="USD">USD</option>
                            <option value="CAD">CAD</option>
                            <option value="EURO">EURO</option>
                        </select>
                    </div>
            ';
        }
        else
        {
        echo '
                <div style="float:none;margin:auto;" class="select-dark">
                    <select name="currency" id="dark" onchange="this.form.submit()">
                        <option selected disabled>'.$_SESSION['currency'].'</option>
                        <option value="USD">USD</option>
                        <option value="CAD">CAD</option>
                        <option value="EURO">EURO</option>
                    </select>
                </div>
        ';
        }
        echo "Account balance: " . $balance. "<br>";
        $conversion_rate = $_SESSION['conversion_rate'] * 100;
        if($conversion_rate < 0)
        {
            echo "↓ " .$conversion_rate. "%<br>";
        }
        else if($conversion_rate > 0)
        {
            echo "↑ " .$conversion_rate. "%<br>";
        }
        else 
        {
            echo $conversion_rate;
            echo "%<br>";
        }
        echo '
                    </form>
                    <form action = "../../APIs/shared/CheckSellConversionBackend.php" method = "post">
                        <div class="form-group">
        ';
        if($_SESSION['currency'] == 0)
        {
            echo '
                            <h5 style="padding-top:150px;"> Please choose a currency</h5>
            ';
        }
        else
        {
            echo '
                            <h5 style="padding-top:150px;">Enter Amount in Siliqas (q̶)</h5>
                            <input type="text" name = "currency" style="border-color: white;" class="form-control form-control-sm" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter amount">
                        </div>
                        <div class="navbar-light bg-dark" class="col-md-8 col-12 mx-auto pt-5 text-center">
                            <input type = "submit" class="btn btn-primary" role="button" aria-pressed="true" name = "button" value = "Check Conversion" onclick="window.location.reload();"> 
                        </div>
                    </form>
                        <p class="navbar navbar-expand-lg navbar-light bg-dark">Siliqas (q̶):
            ';
            
            if($_SESSION['coins']!=0)
            {
                //rounding to 2 decimals
                echo round($_SESSION['coins'], 2);
            }
            else
            {
                echo " ";
                echo 0;
            }
            echo '
                        </p>
                    </form>
                    <form action = "../shared/Sellout.php" method = "post">
                        <div class="navbar-light bg-dark" class="col-md-8 col-12 mx-auto pt-5 text-center">
            ';
            if($_SESSION['btn_show'] == 1)
            {
                echo '
                            <input type = "submit" class="btn btn-primary" role="button" aria-pressed="true" name = "button" value = "Buy this amount!" onclick="window.location.reload();">
                        </div>
                    </form>
                ';
            }
            echo'
                </div>
            </div>
        </div>
    </section>';
            $_SESSION['btn_show'] = 0;
        }
    }
?>