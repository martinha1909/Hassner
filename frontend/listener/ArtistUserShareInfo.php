<?php
    include '../../backend/control/dependencies.php';
    include '../../backend/shared/include/MarketplaceHelpers.php';
    include '../../backend/constants/StatusCodes.php';
    include '../../backend/constants/LoggingModes.php';
    include '../../backend/constants/ShareInteraction.php';
    include '../../backend/constants/GraphOption.php';
    include '../../backend/object/TradeHistory.php';
    include '../../backend/object/TradeHistoryList.php';
    include '../../backend/object/Node.php';
    include '../../backend/object/TickerInfo.php';
    include '../../backend/object/SellOrder.php';

    //only do actions if an artist is found
    if($_SESSION['artist_found'])
    {
        //Refreshes market cap
        calculateMarketCap($_SESSION['selected_artist']);

        $available_share = calculateArtistAvailableShares($_SESSION['selected_artist']);
        $artist_market_tag = getArtistMarketTag($_SESSION['selected_artist']);
    }
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Hassner - Share interaction</title>
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
                <a class="navbar-brand heading-black" href="listener.php">
                    HASSNER
                </a>

                <div class="wrapper-searchbar mx-auto">
                    <div class="container-searchbar">
                        <label>
                            <span class="screen-reader-text">Search for...</span>
                            <form class="form-inline" action="../../backend/listener/SearchArtistSwitcher.php" method="post">
                                <input type="search" class="search-field" placeholder="Search for Artist(s)" value="" name="artist_search" />
                            </form>
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
                <div class="mx-auto my-auto text-center col">
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
                        <h2 class="h2-blue"><?php echo $_SESSION['selected_artist']; ?></h2>
                        <h4 class="h4-blue">(<?php echo strtoupper($artist_market_tag); ?>)</h4>
                        <?php
                            if(!isAlreadyFollowed($_SESSION['username'], $_SESSION['selected_artist']))
                            {
                                echo '
                                    <p>
                                        <form action="../../backend/listener/FollowArtistBackend.php" method="post">
                                            <input name = "follow['.$_SESSION['selected_artist'].']" type = "submit" style="border:1px transparent; background-color: transparent; font-weight: bold; color: white;" aria-pressed="true" value ="Follow">
                                        </form>
                                    </p>
                                ';
                            }
                            else
                            {
                                echo '
                                    <p>
                                        <form action="../../backend/listener/UnFollowArtistBackend.php" method="post">
                                            <input name = "unfollow['.$_SESSION['selected_artist'].']" type = "submit" style="border:1px transparent; background-color: transparent; font-weight: bold; color: white;" aria-pressed="true" value ="Unfollow">
                                        </form>
                                    </p>
                                ';
                            }
                        ?>
                            </form>
                        </p>
                    </div>

                    <!-- Displaying stock graph -->
                    <div class="chart-container">
                        <?php
                            $change = getArtistDayChange($_SESSION['selected_artist']);
                            $market_cap = calculateMarketCap($_SESSION['selected_artist']);
                            $volume = getArtistShareVolume($_SESSION['selected_artist']);
                            $open = getArtistPricePerShare($_SESSION['selected_artist']);
                            $high = getHighestOrLowestPPS($_SESSION['selected_artist'], "MAX");
                            $low = getHighestOrLowestPPS($_SESSION['selected_artist'], "MIN");
                            echo '
                                    <h2>'.$_SESSION['current_pps']['price_per_share'].'</h2>
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
                                <div class="text-left">
                                    <h6>Mkt Cap: '.$market_cap.'</h6>
                                    <h6>Volume: '.$volume.'</h6>
                                    <h6>Open: '.$open.'</h6>
                                    <h6>High: '.$high.'</h6>
                                    <h6>Low: '.$low.'</h6>
                                </div>
                            ';
                        ?>
                    </div>

                    <!-- displaying current share information between current user and selected artist -->
                        <?php }?>
                </div>
                <div class="mx-auto my-auto text-center col-5 buy_sell_container">
                    <?php
                    if($_SESSION['artist_found'])
                    {
                        if(isTradingTime('America/Edmonton'))
                        {
                            echo '
                                <div class="accordion" id="buy_accordion">
                                    <h3 class="shares_header">Buy Shares</h3>
                                    <div class="slider_container">
                                        <div class="textbox_container">
                                            <label for="buy_min">Buy min:</label>
                                            <input type="text" class="slider_text" id="buy_min" style="border:0; color:#f6931f; font-weight:bold;">

                                            <label for="buy_max">Buy max:</label>
                                            <input type="text" class="slider_text" id="buy_max" style="border:0; color:#f6931f; font-weight:bold;">
                                        </div>

                                        <div class="slider_slider" id="buy_slider"></div>
                                        <div class="order_btn_container">
                                        <button id="buy_order">Buy</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="spacer"></div>
                                <div class="accordion" id="sell_accordion">
                                <h3 class="shares_header">Sell Shares</h3>
                                    <div class="slider_container">
                                    <div class="textbox_container">
                                        <label for="sell_min">Sell min:</label>
                                        <input type="text" class="slider_text" id="sell_min" style="border:0; color:#f6931f; font-weight:bold;">

                                        <label for="sell_max">Sell max:</label>
                                        <input type="text" class="slider_text" id="sell_max" style="border:0; color:#f6931f; font-weight:bold;">
                                    </div>
                                        <div class="slider_slider" id="sell_slider"></div>
                                        <div class="order_btn_container">
                                        <button id="sell_order">Sell</button>
                                        </div>
                                    </div>
                                </div>
                            ';
                        }
                        else
                        {
                            echo '
                                <h6 class="error-msg">Out of trading hours<h6>
                            ';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <section class="vh-md-100" id="Marketplace">
        <div class="container-fluid">
            <div class="row align-items-start">
                <div class="my-auto text-center col">
                    <?php
                        if($_SESSION['artist_found'])
                        {
                            if(isTradingTime('America/Edmonton'))
                            {
                                if ($_SESSION['buy_sell'] == ShareInteraction::BUY && $_SESSION['buy_options'] == "MARKET") 
                                {
                                    echo '
                                            <div class="py-4 center-text">
                                                <h3 class="h3-blue py-5">Market Price</h3>
                                            </div>
                                        ';

                                    //If the amount of artist shares has not sold out or the artist has distributed some shares, makes Buy option available 
                                    if ($_SESSION['available_shares'] > 0) {
                                        //replaces the Buy button with a slide bar ranging from 0 to the quantity that is equivalent to the maximum available share for purchase
                                        if ($_SESSION['buy_market_price'] == 0) {
                                            echo '
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Seller username</th>
                                                            <th scope="col">Price per share(q̶)</th>
                                                            <th scope="col">Quantity</th>
                                                            <th scope="col">+</th>
                                                            <th scope="col"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row">' . $_SESSION['selected_artist'] . '</th>
                                                                <td>' . $_SESSION['current_pps']['price_per_share'] . '</td>
                                                                <td>' . $_SESSION['available_shares'] . '</td>
                                            ';
                                            if (hasEnoughSiliqas($_SESSION['current_pps']['price_per_share'], $_SESSION['user_balance'])) {
                                                echo '
                                                                <form action="../../backend/listener/ToggleBuyMarketPriceBackend.php" method="post">
                                                                    <td><input name="buy_user_selling_price" role="button" type="submit" class="btn btn-primary" value="Buy"></td>
                                                                </form>
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
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            ';
                                        } else {
                                            $_SESSION['seller_toggle'] = $_SESSION['selected_artist'];
                                            $_SESSION['purchase_price'] = $_SESSION['current_pps']['price_per_share'];
                                            echo '
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Seller username</th>
                                                            <th scope="col">Price per share(q̶)</th>
                                                            <th scope="col">Quantity</th>
                                                            <th scope="col">+</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row">' . $_SESSION['selected_artist'] . '</th>
                                                                <td>' . $_SESSION['current_pps']['price_per_share'] . '</td>
                                                                <td>' . $_SESSION['available_shares'] . '</td>
                                                                <td>
                                                                    <form action="../../backend/shared/BuySharesBackend.php" method="post">
                                                                        <input name = "purchase_quantity" type="range" min="1" max=' . $_SESSION['available_shares'] . ' value="1" class="slider" id="myRange">
                                                                        <p>Quantity: <span id="demo"></span></p>
                                                                        <input name="buy_user_selling_price" type="submit" id="abc" class="menu-text" role="button" aria-pressed="true" value="->">
                                                                    </form>
                                                                    <form action="../../backend/listener/ToggleBuyMarketPriceBackend.php" method="post">
                                                                        <td><input name="buy_user_selling_price" type="submit" id="abc" class="menu-text" role="button" aria-pressed="true" value="-" onclick="window.location.reload();"></td>
                                                                    </form>
                                                                </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            ';
                                        }
                                    } else {
                                        echo '
                                            <div class="py-4 text-center">
                                                <h4>No shares are currently available from ' . $_SESSION['selected_artist'] . '</h4>
                                            </div>
                                        ';
                                    }
                                }
                            }

                            echo '
                                <div class="col-6">
                                    <h3 class="h3-blue py-5">Buy History</h3>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Seller</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">Quantity</th>
                                                <th scope="col">Date Purchased</th>
                                                <th scope="col">Time Purchased</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                </div>
                            ';

                            $sellers = array();
                            $prices = array();
                            $quantities = array();
                            $date_purchase = array();
                            $time_purchase = array();

                            buyHistoryInit($sellers, $prices, $quantities, $date_purchase, $time_purchase, $_SESSION['username']);

                            for ($i = 0; $i < sizeof($sellers); $i++) {
                                echo '
                                            <tr>
                                                <td>' . $sellers[$i] . '</td>
                                                <td>' . $prices[$i] . '</td>
                                                <td>' . $quantities[$i] . '</td>
                                                <td>' . $date_purchase[$i] . '</td>
                                                <td>' . $time_purchase[$i] . '</td>
                                            </tr>
                                ';
                            }

                            echo '
                                        </tbody>
                                    </table>
                            ';

                            tradeHistoryInit($_SESSION['selected_artist']);

                            echo '<h3 class="h3-blue py-5">Ethos Injection History</h3>';

                            injectionHistoryInit($_SESSION['selected_artist']);
                    }
                    else
                    {
                        echo '<h3>No results for "'.$_SESSION['selected_artist'].'"</h3>';
                    }
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
    <script type="text/javascript" src="../js/Chart.min.js"></script>
    <script type="text/javascript" src="../js/linegraph.js"></script>
    <script type="text/javascript" src="../js/artist_sliders.js"></script>
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