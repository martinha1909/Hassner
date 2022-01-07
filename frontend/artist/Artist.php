<?php
    include '../../backend/control/Dependencies.php';
    include '../../backend/artist/include/ArtistHelpers.php';
    include '../../backend/shared/include/MarketplaceHelpers.php';
    include '../../backend/shared/include/CampaignHelpers.php';
    include '../../backend/shared/include/frontendPrintHelpers.php';
    include '../../backend/constants/ShareInteraction.php';
    include '../../backend/constants/TradeHistoryType.php';
    include '../../backend/constants/EthosOption.php';
    include '../../backend/constants/GraphOption.php';
    include '../../backend/constants/CampaignDeliverProgress.php';
    include '../../backend/object/ParticipantList.php';
    include '../../backend/object/CampaignParticipant.php';
    include '../../backend/object/Campaign.php';
    include '../../backend/object/TradeHistory.php';
    include '../../backend/object/TradeHistoryList.php';
    include '../../backend/object/Node.php';
    include '../../backend/object/TickerInfo.php';
    include '../../backend/object/SellOrder.php';
    include '../../backend/object/Investor.php';

    $_SESSION['selected_artist'] = $_SESSION['username'];
    $account_info = getArtistAccount($_SESSION['username'], "artist");
    $_SESSION['user_balance'] = $account_info['balance'];

    $artist_market_tag = getArtistMarketTag($_SESSION['selected_artist']);

    checkRaffleRoll();
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Main</title>
    <meta name="description" content="Rateify is a music service that allows users to rate songs" />

    <!--Inter UI font-->
    <link href="https://rsms.me/inter/inter-ui.css" rel="stylesheet">

    <!-- Bootstrap CSS / Color Scheme -->
    <link rel="icon" href="../../frontend/Images/hx_tmp_2.ico" type="image/ico">
    <link rel="stylesheet" href="../css/default.css" id="theme-color">
    <link rel="stylesheet" href="../css/menu.css" id="theme-color">
    <link rel="stylesheet" href="../css/date_picker.css" type="text/css">
    <link rel="stylesheet" href="../css/slidebar.css" type="text/css">
    <link rel="stylesheet" href="../css/linegraph.css" id="theme-color">
</head>


<!--navigation-->

<body class="bg-dark">
    <section class="smart-scroll">
        <div class="container-xxl">
            <nav class="navbar navbar-expand-md navbar-dark bg-darkcyan">
                <a class="navbar-brand heading-black" href="#">
                    ❖ HX
                </a>

                <!-- This line here is to prevent a bug where the account balance would move to the left -->
                <div class="col text-right"></div>

                <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span data-feather="grid"></span>
                </button>

                <div class="user-balance">
                    <?php
                    echo ' &nbsp;$(USD): ';
                    echo round($account_info['balance'], 2);
                    $unbought = $account_info['Share_Distributed'] - $account_info['Shares'];
                    echo '<br> &nbsp;Available Shares: ';
                    echo $unbought;
                    ?>
                </div>
            </nav>
        </div>
    </section>

    <?php
        displayTicker();
    ?>

    <section id="login">
        <div class="container-fluid">
            <div class="row">
                <ul class="list-group bg-dark">
                    <li class="selected-no-hover list-group-item-no-hover" id="li_ethos">
                        <input name="display_type" type="submit" id="ethos_btn" class="menu-text menu-style" value="❖ Ethos">
                    </li>

                    <li class="list-group-item-no-hover" id="li_campaign">
                        <input name="display_type" type="submit" id="campaign_btn" class="menu-text menu-no-underline" value="Campaign">
                    </li>

                    <li class="list-group-item-no-hover" id="li_balance">
                        <input name="display_type" type="submit" id="balance_btn" class="menu-text menu-no-underline" value="Balance">
                    </li>

                    <li class="list-group-item-no-hover" id="li_investors">
                        <input name="display_type" type="submit" id="investors_btn" class="menu-text menu-no-underline" value="Investors">
                    </li>

                    <li class="list-group-item-no-hover" id="li_account">
                        <input name="display_type" type="submit" id="account_btn" class="menu-text menu-no-underline" value="Account">
                    </li>
                </ul>

                <div class="col">
                    <div id="ethos_content">
                    <?php
                        if ($account_info['Share_Distributed'] == 0) 
                        {
                    ?>
                            <div class="form-group">
                                <h5>How much are you raising</h5>
                                <input type="text" class="form-control" id="amount_raising" placeholder="Enter amount">
                            </div>
                            <div class="form-group">
                                <h5>How many shares are you distributing?</h5>
                                <input type="text" class="form-control" id="shares_dist" placeholder="Enter amount of share">
                            </div>

                            <?php showJSStatusMsg(); ?>

                            <div class="mx-auto pt-5 text-center">
                                <input type = "submit" class="btn btn-primary" role="button" id="ipo_btn" value = "Continue">
                            </div>
                    <?php
                        }
                        else 
                        { 
                            // if($_SESSION['ethos_dashboard_options'] == EthosOption::NONE)
                            // {
                                echo '
                                    <div id="no_tabs_selected">
                                        <div class="py-4">
                                            <div>
                                                <input id="quotes_btn" type = "submit" class="btn btn-secondary" role="button" value = "'.EthosOption::QUOTES.'"> 
                                                <input id="buy_back_shares_btn" type = "submit" class="btn btn-secondary" role="button" value = "'.EthosOption::BUY_BACK_SHARES.'"> 
                                                <input id="history_btn" type = "submit" class="btn btn-secondary" role="button" value = "'.EthosOption::HISTORY.'"> 
                                            </div>
                                        </div>
                                    </div>
                                ';
                            // }
                            if($_SESSION['ethos_dashboard_options'] == EthosOption::QUOTES)
                            {
                                echo '
                                        <div class="py-4">
                                            <form class="text-center" action="../../backend/artist/EthosDashboardOptionSwitcher.php" method="post">
                                                <div>
                                                    <input name = "ethos_options" type = "submit" class="btn btn-warning" role="button" aria-pressed="true" name = "button" value = "'.EthosOption::QUOTES.'" onclick="window.location.reload();"> 
                                                    <input name = "ethos_options" type = "submit" class="btn btn-secondary" role="button" aria-pressed="true" name = "button" value = "'.EthosOption::BUY_BACK_SHARES.'" onclick="window.location.reload();"> 
                                                    <input name = "ethos_options" type = "submit" class="btn btn-secondary" role="button" aria-pressed="true" name = "button" value = "'.EthosOption::HISTORY.'" onclick="window.location.reload();"> 
                                                </div>
                                            </form>
                                        </div>
                                ';

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
                                            <form action="../../backend/shared/GlobalVarsSwitchBackend.php" method="post">
                                                <input name="display_type" type="submit" class="btn btn-primary py-2" value="Inject More Shares">
                                            </form>
                                        </div>
                                ';

                                if ($_SESSION['share_distribute'] != 0) {
                                    if($_SESSION['logging_mode'] == LogModes::SHARE_DIST)
                                    {
                                        if($_SESSION['status'] == StatusCodes::ErrEmpty)
                                        {
                                            $_SESSION['status'] = StatusCodes::ErrGeneric;
                                            getStatusMessage("Amount cannot be empty", "");
                                        }
                                        else if($_SESSION['status'] == StatusCodes::ErrNum)
                                        {
                                            $_SESSION['status'] = StatusCodes::ErrGeneric;
                                            getStatusMessage("Amount has to be a number", "");
                                        }
                                    }
                                    echo '
                                        <div class="col-6 mx-auto">
                                            <form action="../../backend/artist/UpdateShareDistributedBackend.php" method="post">
                                                <p class="text-center">How many shares are you injecting?</p>
                                                <input type="text" name = "share_distributing" class="form-control form-control-sm col-4 mx-auto" placeholder="Enter amount">
                                                <p>Comments</p>
                                                <input type="text" name = "inject_comment" class="form-control form-control-sm py-3" placeholder="Enter comment">
                                                <div class="text-center">
                                                <input type = "submit" class="btn btn-primary my-4" role="button" aria-pressed="true" name = "button" value = "Save">  
                                                </div>
                                            </form>
                                        </div>
                                    ';
                                }
                                
                                echo '
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
                            else if($_SESSION['ethos_dashboard_options'] == EthosOption::BUY_BACK_SHARES)
                            {
                                echo '
                                        <div class="py-4">
                                            <form class="text-center" action="../../backend/artist/EthosDashboardOptionSwitcher.php" method="post">
                                                <div>
                                                    <input name = "ethos_options" type = "submit" class="btn btn-secondary" role="button" aria-pressed="true" name = "button" value = "'.EthosOption::QUOTES.'" onclick="window.location.reload();"> 
                                                    <input name = "ethos_options" type = "submit" class="btn btn-warning" role="button" aria-pressed="true" name = "button" value = "'.EthosOption::BUY_BACK_SHARES.'" onclick="window.location.reload();"> 
                                                    <input name = "ethos_options" type = "submit" class="btn btn-secondary" role="button" aria-pressed="true" name = "button" value = "'.EthosOption::HISTORY.'" onclick="window.location.reload();"> 
                                                </div>
                                            </form>
                                        </div>
                                ';

                                if (artistCanCreateSellOrder($_SESSION['username'])) {
                                    echo '
                                            <div class="text-right mx-6">
                                                <form action="../../backend/shared/ToggleBuySellShareBackend.php" method="post">
                                                    <input name="buy_sell" type="submit" id="menu-style-invert" class="cursor-context" value="-Sell your shares">
                                                </form>
                                            </div>
                                    ';
                                }

                                if ($_SESSION['buy_sell'] == ShareInteraction::SELL) {
                                    $max = artistRepurchaseShares($_SESSION['username']) - artistShareSelling($_SESSION['username']);
                                    echo '
                                        <div class="text-right mx-6">
                                            <h6>How many shares are you selling?</h6>
                                            <div class="wrapper-searchbar">
                                                <div class="container-searchbar mx-auto">
                                                    <label>
                                                        <form action="../../backend/artist/SellOrderBackend.php" method="post">
                                                            <input name = "purchase_quantity" type="range" min="1" max=' . $max . ' value="1" class="slider" id="myRange">
                                                            <p>Quantity: <span id="demo"></span></p>
                                                            <input type="text" name="asked_price" class="form-control" style="border-color: white;" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter price per share">
                                                            <input type="submit" class="btn btn-primary my-2 py-2" role="button" aria-pressed="true" value="Post" onclick="window.location.reload();">
                                                        </form>
                                                    </label> 
                                                </div>
                                            </div>
                                        </div>
                                    ';
                                    $_SESSION['buy_sell'] = 0;
                                }

                                $amount_repurchase_available = getAmountAvailableForRepurchase($_SESSION['username']);
                                $price_for_all_available_repurchase = calculatePriceForAllRepurchase($_SESSION['username']);
                                $owned_shares = getArtistShareRepurchase($_SESSION['username']);

                                //Only to be used if artist clicks the button to buy back all shares that are being sold
                                $_SESSION['repurchase_sell_orders'] = getAllRepurchaseSellOrdersInfo($_SESSION['username']);

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

                                askedPriceInit($_SESSION['username'], $_SESSION['account_type']);

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
                            else if($_SESSION['ethos_dashboard_options'] == EthosOption::HISTORY)
                            {
                                echo '
                                        <div class="py-4">
                                            <form class="text-center" action="../../backend/artist/EthosDashboardOptionSwitcher.php" method="post">
                                                <div>
                                                    <input name = "ethos_options" type = "submit" class="btn btn-secondary" role="button" aria-pressed="true" name = "button" value = "'.EthosOption::QUOTES.'" onclick="window.location.reload();"> 
                                                    <input name = "ethos_options" type = "submit" class="btn btn-secondary" role="button" aria-pressed="true" name = "button" value = "'.EthosOption::BUY_BACK_SHARES.'" onclick="window.location.reload();"> 
                                                    <input name = "ethos_options" type = "submit" class="btn btn-warning" role="button" aria-pressed="true" name = "button" value = "'.EthosOption::HISTORY.'" onclick="window.location.reload();"> 
                                                </div>
                                            </form>
                                        </div>
                                ';

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
                                    </div>
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
                                ';

                                tradeHistoryInit($_SESSION['username']);

                                echo '<h3 class="h3-blue">Inject history</h3>';

                                injectionHistoryInit($_SESSION['username']);
                            }
                        }
                    ?>
                    </div>

                    <div class = "div-hidden" id="campaign_content">
                        <div class="mx-auto py-6 text-center">
                            <a class="btn btn-primary" href="CreateCampaign.php">Start a new campaign?</a>
                        </div>

                        <div class="py-4 text-center">
                            <h4 class="h4-blue">Active Campaigns</h4>
                        </div>
                        <?php
                            printArtistCurrentCampaignTable($_SESSION['username']);
                        ?>

                        <div class="py-4 text-center">
                            <h4 class="h4-blue">Expired Campaigns</h4>
                        </div>
                        <?php
                            printArtistExpiredCampaignTable($_SESSION['username']);
                        ?>
                    </div>

                    <div class="div-hidden" id="balance_content">
                    <?php
                        fiatInit();
                    ?>
                    </div>

                    <div class="div-hidden" id="investors_content">
                        <div class="py-4 text-center">
                            <h4 class="h4-blue">Apex Investors</h4>
                        </div>
                        <?php
                            printArtistApexInvestors($_SESSION['username']);
                        ?>

                        <div class="py-4 text-center">
                            <h4 class="h4-blue">Raffle Winners</h4>
                        </div>
                        <?php
                            printArtistRaffleCampaignsWinners($_SESSION['username']);
                        ?>
                    </div>
                    
                    <div class="div-hidden" id="account_content">
                        <section id="login">
                            <div class="container">
                                <div class="text-center my-6">
                                    <h3 class="h3-blue">Verify your password to access personal page</h3>
                                    <form action="../../backend/artist/PersonalPageBackend.php" method="post">
                                        <div class="form-group col-4 mx-auto">
                        <?php
                        if($_SESSION['logging_mode'] == LogModes::PERSONAL)
                        {
                            if($_SESSION['status'] == StatusCodes::ErrPassword)
                            {
                                $_SESSION['status'] = StatusCodes::ErrGeneric;
                                getStatusMessage("Wrong password", "");
                            }
                        }
                        ?>
                                            <h5>Password</h5>
                                            <input name = "verify_password" type="password" class="form-control form-control-sm" id="exampleInputPassword1" placeholder="Password">
                                        </div>
                                        <div class="text-center">
                                            <input type = "submit" class="btn btn-primary" role="button" aria-pressed="true" name = "button" value = "Verify" onclick="window.location.reload();">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </section>
                    </div>

                </div>
                <!-- header -->


            </div>
        </div>
    </section>

    <a class="btn btn-success py-2" type="submit" role="button" aria-pressed="true" name="button" href="../credentials/login.php">Log out</a>

    <!--scroll to top-->
    <div class="scroll-top">
        <i class="fa fa-angle-up" aria-hidden="true"></i>
    </div>


    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.7.3/feather.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script src="../js/shared/balance/DepositWithdraw.js"></script>
    <script src="../js/artist/MenuItem.js"></script>
    <script src="../js/artist/IPO.js"></script>
    <script>
        var slider = document.getElementById("myRange");
        var output = document.getElementById("demo");
        output.innerHTML = slider.value;

        slider.oninput = function() {
            output.innerHTML = this.value;
        }
    </script>
    <script
        type="text/javascript" 
        id="artist_user_share_info_script" 
        artist_tag='<?= $artist_market_tag; ?>'
    ></script>
    <script type="text/javascript" src="../js/graph/Chart.min.js"></script>
    <script type="text/javascript" src="../js/graph/linegraph.js"></script>
</body>

</html>