<?php
    include '../../backend/control/Dependencies.php';
    include '../../backend/shared/include/MarketplaceHelpers.php';
    include '../../backend/shared/include/frontendPrintHelpers.php';
    include '../../backend/constants/StatusCodes.php';
    include '../../backend/constants/LoggingModes.php';
    include '../../backend/constants/ShareInteraction.php';
    include '../../backend/constants/GraphOption.php';
    include '../../backend/constants/CampaignType.php';
    include '../../backend/object/TradeHistory.php';
    include '../../backend/object/TradeHistoryList.php';
    include '../../backend/constants/TradeHistoryType.php';
    include '../../backend/object/Node.php';
    include '../../backend/object/TickerInfo.php';
    include '../../backend/object/SellOrder.php';
    include '../../backend/object/Campaign.php';

    //only do actions if an artist is found
    if($_SESSION['artist_found'])
    {
        //Refreshes market cap
        calculateMarketCap($_SESSION['selected_artist']);

        $available_share = calculateArtistAvailableShares($_SESSION['selected_artist']);
        $artist_market_tag = getArtistMarketTag($_SESSION['selected_artist']);
        $balance = getUserBalance($_SESSION['username']);
        $user_shares_owned = getShareInvestedInArtist($_SESSION['username'], $_SESSION['selected_artist']);
    }
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $_SESSION['selected_artist']; ?>'s Ethos</title>
    <meta name="description" content="Rateify is a music service that allows users to rate songs" />

    <!--Inter UI font-->
    <link href="https://rsms.me/inter/inter-ui.css" rel="stylesheet">

    <!-- Bootstrap CSS / Color Scheme -->
    <link rel="icon" href="../../frontend/Images/hx_tmp_2.ico" type="image/ico">
    <link rel="stylesheet" href="../css/default.css" id="theme-color">
    <link rel="stylesheet" href="../css/searchbar.css" id="theme-color">
    <link rel="stylesheet" href="../css/slidebar.css" id="theme-color">
    <link rel="stylesheet" href="../css/menu.css" id="theme-color">
    <link rel="stylesheet" href="../css/linegraph.css" id="theme-color">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="../css/slider.css">
</head>

<body class="bg-dark">

    <!--navigation-->
    <section class="smart-scroll">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-md navbar-dark bg-darkcyan">
                <a class="navbar-brand heading-black" href="Listener.php">
                    ❖ HX
                </a>

                <div class="wrapper-searchbar mx-auto">
                    <div class="container-searchbar">
                        <label>
                            <span class="screen-reader-text">Search for...</span>
                            <?php displaySearchBar(); ?>
                        </label>
                    </div>
                </div>
                    <div class="user-balance">
                        <?php
                        echo ' &nbsp;($USD): ';
                        ?>
                    </div>
            </nav>
        </div>
    </section>

    <?php
        if($_SESSION['artist_found'])
        {
            fetchMarketPrice($_SESSION['selected_artist']);
            displayTicker();
        }
    ?>

    <!-- listener functionality -->
    <section id="login">
        <div class="container-fluid">
            <div class="row py-6 align-items-start">
                <div class="mx-auto my-auto text-center col-8">
                    <div class="py-4 text-center">
                        <?php
                        if($_SESSION['artist_found'])
                        {
                            if ($_SESSION['logging_mode'] == LogModes::BUY_SHARE) {
                                if ($_SESSION['status'] == "SILIQAS_ERR") {
                                    $_SESSION['status'] = StatusCodes::ErrGeneric;
                                    getStatusMessage("Not enough siliqas", "");
                                } else {
                                    getStatusMessage("An unexpected error occured", "Shares bought successfully");
                                }
                            }
                        ?>
                        <div>
                        <h2 id="selected_artist" class="h2-blue"><?php echo $_SESSION['selected_artist']; ?></h2>
                        <h4 class="h4-blue">(<?php echo strtoupper($artist_market_tag); ?>)</h4>
                        <?php
                            if(!isAlreadyFollowed($_SESSION['username'], $_SESSION['selected_artist']))
                            {
                                echo '
                                    <p>
                                        <form action="../../backend/listener/FollowArtistBackend.php" method="post">
                                            <input name = "follow['.$_SESSION['selected_artist'].']" type = "submit" class="cursor-context" aria-pressed="true" value ="✧ Follow">
                                        </form>
                                    </p>
                                ';
                            }
                            else
                            {
                                echo '
                                    <p>
                                        <form action="../../backend/listener/UnFollowArtistBackend.php" method="post">
                                            <input name = "unfollow['.$_SESSION['selected_artist'].']" type = "submit" class="cursor-context" aria-pressed="true" value ="✦ Unfollow">
                                        </form>
                                    </p>
                                ';
                            }
                        ?>
                        </div>
                            </form>
                        </p>
                    </div>

                    <!-- Displaying stock graph -->
                    <div class="chart-container mx-auto">
                        <?php
                            $change = getArtistDayChange($_SESSION['selected_artist']);
                            $market_cap = calculateMarketCap($_SESSION['selected_artist']);
                            $volume = getArtistShareVolume($_SESSION['selected_artist']);
                            $open = getArtistPricePerShare($_SESSION['selected_artist']);
                            $high = getHighestOrLowestPPS($_SESSION['selected_artist'], "MAX");
                            $low = getHighestOrLowestPPS($_SESSION['selected_artist'], "MIN");
                            echo '
                                    <h2 id="pps">'.$_SESSION['current_pps']['price_per_share'].'</h2>
                                ';
                            if($change == 0)
                            {
                                echo '
                                    <h3>'.$change.'%</h3>
                                ';
                            }
                            else if($change > 0)
                            {
                                echo '
                                    <h3 class="suc-msg">+'.$change.'%</h3>
                                ';
                            }
                            else
                            {
                                echo '
                                    <h3 class="error-msg">'.$change.'%</h3>
                                ';
                            }

                            echo '
                                    <button id = "'.GraphOption::ONE_DAY.'" class="btn btn-secondary">'.GraphOption::ONE_DAY.'</button>
                                    <button id = "'.GraphOption::FIVE_DAY.'" class="btn btn-secondary" aria-pressed="true">'.GraphOption::FIVE_DAY.'</button>
                                    <button id = "'.GraphOption::ONE_MONTH.'" class="btn btn-secondary" aria-pressed="true">'.GraphOption::ONE_MONTH.'</button>
                                    <button id = "'.GraphOption::SIX_MONTH.'" class="btn btn-secondary" aria-pressed="true">'.GraphOption::SIX_MONTH.'</button>
                                    <button id = "'.GraphOption::YEAR_TO_DATE.'" class="btn btn-secondary" aria-pressed="true">'.GraphOption::YEAR_TO_DATE.'</button>
                                    <button id = "'.GraphOption::ONE_YEAR.'" class="btn btn-secondary" aria-pressed="true">'.GraphOption::ONE_YEAR.'</button>
                                    <button id = "'.GraphOption::FIVE_YEAR.'" class="btn btn-secondary" aria-pressed="true">'.GraphOption::FIVE_YEAR.'</button>
                            ';

                            //displaying stock graph
                            echo '
                                <canvas id="stock_graph"></canvas>
                                <div class="text-center">
                                    <a>Mkt Cap: '.$market_cap.' | </a>
                                    <a>Volume: '.$volume.' | </a>
                                    <a>Open: '.$open.' | </a>
                                    <a>High: '.$high.' | </a>
                                    <a>Low: '.$low.'</a>
                                </div>
                            ';
                        ?>
                    </div>

                    <!-- displaying current share information between current user and selected artist -->
                        <?php }?>
                </div>
                <div class="col-4 my-8 text-center">
                    <?php
                        if($_SESSION['artist_found'])
                        {
                            echo '
                            <div class="shares-owned">
                                <h3 class="h3-blue"><a style="color:white"><?php echo $user_shares_owned; ?></a> Shares Owned</h3>
                            </div>
                            ';
                        }
                    
                    ?>
                <div class="mx-auto my-auto text-center buy_sell_container">
                    <?php
                    if($_SESSION['artist_found'])
                    {
                        if(canCreateBuyOrder($_SESSION['username'], $_SESSION['selected_artist']))
                        {
                            if($balance > 0 && $balance > $_SESSION['current_pps']['price_per_share'])
                            {
                                echo '
                                    <div class="accordion" id="buy_accordion">
                                        <h3 class="shares_header">Buy Shares</h3>
                                        <div class="slider_container">
                                            <div class="textbox_container">
                                                <div class="stocktip">
                                                    <p id="buy_tip">Without limits the next available share(s) will be purchased</p>
                                                </div>
                                                <label for="buy_num_shares"># Shares:</label>
                                                <input type="text" class="slider_text" id="buy_num_shares" style="border:0; color:#f6931f; font-weight:bold;">

                                                <label for="buy_cost">Cost:</label>
                                                <input type="text" class="slider_text" id="buy_cost" style="border:0; color:#f6931f; font-weight:bold;">
                                            </div>

                                            <div class="slider_slider" id="buy_num"></div>
                                            <div class="slider_slider" id="buy_limit"></div>
                                            <div class="order_btn_container">
                                            <button class="btn btn-primary py-2" id="buy_order">Buy</button>
                                            </div>
                                        </div>
                                    </div>
                                ';
                            }
                            else
                            {
                                echo '<h6 class="error-msg">Not enough balance</h6>';
                            }
                        }
                        if(canCreateSellOrder($_SESSION['username'], $_SESSION['selected_artist']))
                        {
                            echo '
                                    <div class="spacer"></div>
                                    <div class="accordion" id="sell_accordion">
                                        <h3 class="shares_header">Sell Shares</h3>
                                        <div class="slider_container">
                                            <div class="textbox_container">
                                                <div class="stocktip">
                                                    <p id="sell_tip">Without limits your shares will be sold to the next available buyer</p>
                                                </div>
                                                <label for="sell_num_shares"># Shares:</label>
                                                <input type="text" class="slider_text" id="sell_num_shares" style="border:0; color:#f6931f; font-weight:bold;">
                                                <label for="sell_cost">$:</label>
                                                <input type="text" class="slider_text" id="sell_cost" style="border:0; color:#f6931f; font-weight:bold;">
                                            </div>
                                        <div class="slider_slider" id="sell_num"></div>
                                        <div class="slider_slider" id="sell_limit"></div>
                                        <div class="order_btn_container">
                                            <button class="btn btn-primary py-2" id="sell_order">Sell</button>
                                        </div>
                                    </div>
                                </div>
                            ';
                        }
                    }
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="vh-md-100" id="Marketplace">
        <div class="container-fluid">
            <div class="row align-items-start">
                <div class="my-auto text-center col-6">
                    <div>
                    <?php
                        if($_SESSION['artist_found'])
                        {
                            echo '
                                <h3 class="h3-blue py-5">History</h3>
                                <div class="my-4 mx-auto select-dark">
                                    <select class="select-dropdown select-dropdown-dark text-center" id="user_history_dropdown">
                                        <option selected disabled>'.TradeHistoryType::NONE.'</option>
                                        <option value="'.TradeHistoryType::BUY_HISTORY.'">'.TradeHistoryType::BUY_HISTORY.'</option>
                                        <option value="'.TradeHistoryType::TRADE_HISTORY.'">'.TradeHistoryType::TRADE_HISTORY.'</option>
                                        <option value="'.TradeHistoryType::INJECTION_HISTORY.'">'.TradeHistoryType::INJECTION_HISTORY.'</option>
                                    </select>
                                </div>

                                <div class="div-hidden" id="user_buy_history_content">
                                    '.printUserBuyHistoryTable($_SESSION['username']).'
                                </div>

                                <div class="div-hidden" id="user_trade_history_content">
                                    '.tradeHistoryInit($_SESSION['selected_artist']).'
                                </div>

                                <div class="div-hidden" id="user_inject_history_content">
                                    '.injectionHistoryInit($_SESSION['selected_artist']).'
                                </div>
                            ';
                        }
                        else
                        {
                            echo '<h3>No results for "'.$_SESSION['selected_artist'].'"</h3>';
                        }
                    ?>
                    </div>
                </div>
                <div class="col-6">
                    <?php
                        printUserCurrentArtistCampaign($_SESSION['selected_artist']);
                    ?>
                </div>
            </div>
        </div>
    </section>




    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.7.3/feather.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script
        type="text/javascript" 
        id="artist_user_share_info_script" 
        artist_tag='<?= $artist_market_tag; ?>'
    ></script>
    <script type="text/javascript" src="../js/graph/Chart.min.js"></script>
    <script type="text/javascript" src="../js/graph/linegraph.js"></script>
    <script type="text/javascript" src="../js/listener/artist_sliders.js"></script>
    <script src="../js/listener/TradeHistory.js"></script>
    <script src="../js/listener/EthosHistory.js"></script>
    <script>
        var slider = document.getElementById("myRange");
        var output = document.getElementById("demo");
        output.innerHTML = slider.value;

        slider.oninput = function() {
            output.innerHTML = this.value;
        }
    </script>
</body>

</html>