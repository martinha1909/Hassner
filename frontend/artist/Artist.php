<?php
    include '../../backend/control/Dependencies.php';
    include '../../backend/artist/ArtistHelpers.php';
    include '../../backend/shared/MarketplaceHelpers.php';
    include '../../backend/shared/CampaignHelpers.php';
    include '../../backend/constants/ShareInteraction.php';
    include '../../backend/constants/TradeHistoryType.php';
    include '../../backend/constants/EthosOption.php';
    include '../../backend/object/ParticipantList.php';
    include '../../backend/object/CampaignParticipant.php';
    include '../../backend/object/TradeHistory.php';
    include '../../backend/object/TradeHistoryList.php';
    include '../../backend/object/Node.php';
    include '../../backend/object/TickerInfo.php';
    include '../../backend/object/SellOrder.php';

    $_SESSION['selected_artist'] = $_SESSION['username'];
    $account_info = getArtistAccount($_SESSION['username'], "artist");
    $_SESSION['user_balance'] = $account_info['balance'];

    checkRaffleRoll();
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Hassner - Artist</title>
    <meta name="description" content="Rateify is a music service that allows users to rate songs" />

    <!--Inter UI font-->
    <link href="https://rsms.me/inter/inter-ui.css" rel="stylesheet">

    <!-- Bootstrap CSS / Color Scheme -->
    <link rel="icon" href="../../frontend/Images/hx_tmp_2.ico" type="image/ico">
    <link rel="stylesheet" href="../css/default.css" id="theme-color">
    <link rel="stylesheet" href="../css/menu.css" id="theme-color">
    <link rel="stylesheet" href="../css/date_picker.css" type="text/css">
    <link rel="stylesheet" href="../css/slidebar.css" type="text/css">
</head>


<!--navigation-->

<body class="bg-dark">
    <section class="smart-scroll">
        <div class="container-xxl">
            <nav class="navbar navbar-expand-md navbar-dark bg-darkcyan">
                <a class="navbar-brand heading-black" href="#">
                    HASSNER
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
                    <?php
                    if ($_SESSION['display'] == MenuOption::Ethos || $_SESSION['display'] == MenuOption::None) {
                        echo '
                            <li class="selected-no-hover list-group-item-no-hover">
                                <form action="../../backend/control/MenuDisplayArtistBackend.php" method="post">
                                <input name="display_type" type="submit" id="menu-style" class="menu-text" value="❖ Ethos"
                                </form>
                            </li>
                        ';
                    } else {
                        echo '
                            <li class="list-group-item-no-hover">
                                <form action="../../backend/control/MenuDisplayArtistBackend.php" method="post">
                                <input name="display_type" type="submit" id="abc-no-underline" class="menu-text" value="Ethos">
                                </form>
                            </li>
                        ';
                    }

                    if ($_SESSION['display'] == MenuOption::Campaign) {
                        echo '
                            <li class="selected-no-hover list-group-item-no-hover">
                                <form action="../../backend/control/MenuDisplayArtistBackend.php" method="post">
                                <input name="display_type" type="submit" id="menu-style" class="menu-text" value="◔ Campaign">
                                </form>
                            </li>
                        ';
                    } else {
                        echo '
                            <li class="list-group-item-no-hover">
                                <form action="../../backend/control/MenuDisplayArtistBackend.php" method="post">
                                <input name="display_type" type="submit" id="abc-no-underline" class="menu-text" value="Campaign">
                                </form>
                            </li>
                        ';
                    }

                    if ($_SESSION['display'] == MenuOption::Balance) {
                        echo '
                            <li class="selected-no-hover list-group-item-no-hover">
                                <form action="../../backend/control/MenuDisplayArtistBackend.php" method="post">
                                <input name="display_type" type="submit" id="menu-style" class="menu-text" value="※ Balance">
                                </form>
                            </li>
                        ';
                    } else {
                        echo '
                            <li class="list-group-item-no-hover">
                                <form action="../../backend/control/MenuDisplayArtistBackend.php" method="post">
                                <input name="display_type" type="submit" id="abc-no-underline" class="menu-text" value="Balance">
                                </form>
                            </li>
                        ';
                    }

                    if ($_SESSION['display'] == MenuOption::Investors) {
                        echo '
                            <li class="selected-no-hover list-group-item-no-hover">
                                <form action="../../backend/control/MenuDisplayArtistBackend.php" method="post">
                                <input name="display_type" type="submit" id="menu-style" class="menu-text" value="◈ Investors">
                                </form>
                            </li>
                        ';
                    } else {
                        echo '
                            <li class="list-group-item-no-hover">
                                <form action="../../backend/control/MenuDisplayArtistBackend.php" method="post">
                                <input name="display_type" type="submit" id="abc-no-underline" class="menu-text" value="Investors">
                                </form>
                            </li>
                        ';
                    }

                    if ($_SESSION['display'] == MenuOption::Account) {
                        echo '
                            <li class="selected-no-hover list-group-item-no-hover">
                                <form action="../../backend/control/MenuDisplayArtistBackend.php" method="post">
                                <input name="display_type" type="submit" id="menu-style" class="menu-text" value="▤ Account">
                                </form>
                            </li>
                        ';
                    } else {
                        echo '
                            <li class="list-group-item-no-hover">
                                <form action="../../backend/control/MenuDisplayArtistBackend.php" method="post">
                                <input name="display_type" type="submit" id="abc-no-underline" class="menu-text" value="Account">
                                </form>
                            </li>
                        ';
                    }
                    ?>
                </ul>

                <div class="col">
                    <?php
                    //Artist campaigns, including benchmark, raffle, and give aways.
                    if ($_SESSION['display'] == MenuOption::Campaign) {
                        $offerings = array();
                        $time_left = array();
                        $eligible_participants = array();
                        $min_ethos = array();
                        $types = array();
                        $time_releases = array();
                        $roll_results = array();
                        fetchCampaigns(
                            $_SESSION['username'],
                            $offerings,
                            $time_left,
                            $eligible_participants,
                            $min_ethos,
                            $types,
                            $time_releases,
                            $roll_results
                        );
                        echo '
                                    <div class="mx-auto py-6 text-center">
                                        <a class="btn btn-primary" href="CreateCampaign.php">Start a new campaign?</a>
                                    </div>
                            ';
                        if (sizeof($offerings) > 0) {
                            echo '
                                        <h4>Your other campaigns</h4>
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

                            for ($i = 0; $i < sizeof($offerings); $i++) {
                                echo '
                                                <tr>
                                                    <th>' . $offerings[$i] . '</th>
                                                    <td>' . $types[$i] . '</td>
                                                    <td>' . $eligible_participants[$i] . '</td>
                                                    <td>' . $min_ethos[$i] . '</td>
                                                    <td>' . $time_left[$i] . '</td>
                                                    <td>' . $roll_results[$i] . '</td>
                                                    <td>' . $time_releases[$i] . '</td>
                                                </tr>
                                    ';
                            }
                            echo '
                                            </tbody>
                                        </table>
                                ';
                        }

                        $offerings = array();
                        $eligible_participants = array();
                        $min_ethos = array();
                        $types = array();
                        $time_releases = array();
                        $roll_results = array();
                        fetchExpiredCampaigns(
                            $_SESSION['username'],
                            $offerings,
                            $eligible_participants,
                            $min_ethos,
                            $types,
                            $time_releases,
                            $roll_results
                        );
                        echo '
                                    <div class="py-6 text-center">
                                        <h4 class="h4-blue">Expired Campaigns</h4>
                                    </div>
                            ';

                        if (sizeof($offerings) > 0) {
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
                                                <tbody>';

                            for ($i = 0; $i < sizeof($offerings); $i++) {
                                echo '
                                                    <tr>
                                                        <th>' . $offerings[$i] . '</th>
                                                        <td>' . $types[$i] . '</td>
                                                        <td>' . $eligible_participants[$i] . '</td>
                                                        <td>' . $min_ethos[$i] . '</td>
                                                        <td>' . $roll_results[$i] . '</td>
                                                        <td>' . $time_releases[$i] . '</td>
                                                    </tr>
                                    ';
                            }
                            echo '
                                                </tbody>
                                            </table>
                                        </div>
                                ';
                        }
                    }

                    //Artists portfolio
                    else if ($_SESSION['display'] == MenuOption::Ethos || $_SESSION['display'] == MenuOption::None) {
                        if ($account_info['Share_Distributed'] == 0) {
                            echo '
                                        <form action="../../backend/artist/DistributeShareBackend.php" method="post">
                                        <div class="form-group">
                                            <h5>How much are you raising</h5>
                                            <input name = "siliqas_raising" type="text" class="form-control" id="exampleInputPassword1" placeholder="Enter amount">
                                        </div>
                                        <div class="form-group">
                                            <h5>How many shares are you distributing?</h5>
                                            <input name = "distribute_share" type="text" class="form-control" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter amount of share">
                                        </div>';

                            if ($_SESSION['logging_mode'] == LogModes::SHARE_DIST) {
                                if ($_SESSION['status'] == "NUM_ERR") {
                                    $_SESSION['status'] = StatusCodes::ErrGeneric;
                                    getStatusMessage("Please enter in number format", "");
                                } else if ($_SESSION['status'] == "EMPTY_ERR") {
                                    $_SESSION['status'] = StatusCodes::ErrGeneric;
                                    getStatusMessage("Please fill out all fields", "");
                                }
                            }

                            echo '

                                        <div class="col-md-8 col-12 mx-auto pt-5 text-center">
                                            <input type = "submit" class="btn btn-primary" role="button" aria-pressed="true" name = "button" value = "Continue">
                                        </div>

                                    </form>
                            ';
                        } 
                        else 
                        {
                            if($_SESSION['ethos_dashboard_options'] == EthosOption::NONE)
                            {
                                echo '
                                        <div class="py-4">
                                            <form action="../../backend/artist/EthosDashboardOptionSwitcher.php" method="post">
                                                <div class="navbar-light bg-dark" class="col-md-8 col-12 mx-auto pt-5 text-center">
                                                    <input name = "ethos_options" type = "submit" class="btn btn-secondary" role="button" aria-pressed="true" name = "button" value = "'.EthosOption::QUOTES.'" onclick="window.location.reload();"> 
                                                    <input name = "ethos_options" type = "submit" class="btn btn-secondary" role="button" aria-pressed="true" name = "button" value = "'.EthosOption::BUY_BACK_SHARES.'" onclick="window.location.reload();"> 
                                                    <input name = "ethos_options" type = "submit" class="btn btn-secondary" role="button" aria-pressed="true" name = "button" value = "'.EthosOption::HISTORY.'" onclick="window.location.reload();"> 
                                                </div>
                                            </form>
                                        </div>
                                ';
                            }
                            else if($_SESSION['ethos_dashboard_options'] == EthosOption::QUOTES)
                            {
                                echo '
                                        <div class="py-4">
                                            <form action="../../backend/artist/EthosDashboardOptionSwitcher.php" method="post">
                                                <div class="navbar-light bg-dark" class="col-md-8 col-12 mx-auto pt-5 text-center">
                                                    <input name = "ethos_options" type = "submit" class="btn btn-primary" role="button" aria-pressed="true" name = "button" value = "'.EthosOption::QUOTES.'" onclick="window.location.reload();"> 
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
                                            <h6>Price Per Share: $' . $account_info['price_per_share'] . '</h6>
                                            <h6>Volumn: $' . $account_info['Share_Distributed'] . '</h6>
                                            <h6>Current Shareholders: ' . $shareholder_list->num_rows . '</h6>
                                            <h6>Market cap: $' . $market_cap . '</h6>
                                            <h6>Day High: $' . $high . '</h6>
                                            <h6>Day Low: $' . $low . '</h6>
                                            <br>
                                            <form action="../../backend/shared/GlobalVarsSwitchBackend.php" method="post">
                                                <input name="display_type" type="submit" class="btn btn-primary" value="Inject More Shares">
                                            </form>
                                ';

                                if ($_SESSION['share_distribute'] != 0) {
                                    echo '
                                            <form action="../../backend/artist/UpdateShareDistributedBackend.php" method="post">
                                                <p>How many shares would you like to inject?</p>
                                                <input type="text" name = "share_distributing" class="form-control form-control-sm" style="border-color: white;" placeholder="Enter amount">
                                                <p>Comments</p>
                                                <input type="text" name = "inject_comment" class="form-control form-control-sm" style="border-color: white;" placeholder="Enter comment">
                                                <div class="col-md-8 col-12 mx-auto pt-5 text-center">
                                                <input type = "submit" class="btn btn-primary" role="button" aria-pressed="true" name = "button" value = "Save">  
                                                </div>
                                            </form>
                                    ';
                                }
                                
                            }
                            else if($_SESSION['ethos_dashboard_options'] == EthosOption::BUY_BACK_SHARES)
                            {
                                echo '
                                        <div class="py-4">
                                            <form action="../../backend/artist/EthosDashboardOptionSwitcher.php" method="post">
                                                <div class="navbar-light bg-dark" class="col-md-8 col-12 mx-auto pt-5 text-center">
                                                    <input name = "ethos_options" type = "submit" class="btn btn-secondary" role="button" aria-pressed="true" name = "button" value = "'.EthosOption::QUOTES.'" onclick="window.location.reload();"> 
                                                    <input name = "ethos_options" type = "submit" class="btn btn-primary" role="button" aria-pressed="true" name = "button" value = "'.EthosOption::BUY_BACK_SHARES.'" onclick="window.location.reload();"> 
                                                    <input name = "ethos_options" type = "submit" class="btn btn-secondary" role="button" aria-pressed="true" name = "button" value = "'.EthosOption::HISTORY.'" onclick="window.location.reload();"> 
                                                </div>
                                            </form>
                                        </div>
                                ';

                                if (artistCanCreateSellOrder($_SESSION['username'])) {
                                    echo '
                                                <form action="../../backend/shared/ToggleBuySellShareBackend.php" method="post">
                                                    <input name="buy_sell" type="submit" id="menu-style-invert" style=" border:1px orange; background-color: transparent;" value="-Sell your shares">
                                                </form>
                                    ';
                                }

                                if ($_SESSION['buy_sell'] == ShareInteraction::SELL) {
                                    $max = artistRepurchaseShares($_SESSION['username']) - artistShareSelling($_SESSION['username']);
                                    echo '
                                            <h6>How many shares are you selling?</h6>
                                            <div class="wrapper-searchbar">
                                                <div class="container-searchbar mx-auto">
                                                    <label>
                                                        <form action="../../backend/shared/SellOrderBackend.php" method="post">
                                                            <input name = "purchase_quantity" type="range" min="1" max=' . $max . ' value="1" class="slider" id="myRange">
                                                            <p>Quantity: <span id="demo"></span></p>
                                                            <input type="text" name="asked_price" class="form-control" style="border-color: white;" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter siliqas">
                                                            <input type="submit" class="btn btn-primary" role="button" aria-pressed="true" value="Post" onclick="window.location.reload();">
                                                        </form>
                                                    </label> 
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
                                    <h6>Your own shares: '.$owned_shares.'</h6>
                                    <h6>Shares available for repurchase: '.$amount_repurchase_available.'</h6>
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
                                            <form action="../../backend/artist/RepurchaseAllSharesBackend.php" method="post">
                                                <input type="submit" class="btn btn-primary" value="Purchase all '.$amount_repurchase_available.' at $'.$price_for_all_available_repurchase.'">
                                            </form>
                                    ';
                                }
                            }
                            else if($_SESSION['ethos_dashboard_options'] == EthosOption::HISTORY)
                            {
                                echo '
                                        <div class="py-4">
                                            <form action="../../backend/artist/EthosDashboardOptionSwitcher.php" method="post">
                                                <div class="navbar-light bg-dark" class="col-md-8 col-12 mx-auto pt-5 text-center">
                                                    <input name = "ethos_options" type = "submit" class="btn btn-secondary" role="button" aria-pressed="true" name = "button" value = "'.EthosOption::QUOTES.'" onclick="window.location.reload();"> 
                                                    <input name = "ethos_options" type = "submit" class="btn btn-secondary" role="button" aria-pressed="true" name = "button" value = "'.EthosOption::BUY_BACK_SHARES.'" onclick="window.location.reload();"> 
                                                    <input name = "ethos_options" type = "submit" class="btn btn-primary" role="button" aria-pressed="true" name = "button" value = "'.EthosOption::HISTORY.'" onclick="window.location.reload();"> 
                                                </div>
                                            </form>
                                        </div>
                                ';

                                //Buy Back shares history 
                                echo '
                                    <div class="col-6">
                                        <h3 class="h3-blue py-2">Buy Back History</h3>
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
                                $time_purchase = array();
            
                                buyHistoryInit($sellers, $prices, $quantities, $date_purchase, $time_purchase, $_SESSION['username']);
            
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
                    }

                    //brings to Artist personal account page, where they can input their metrics, which are shown
                    //when users search for them and also on their portfolio tab
                    else if ($_SESSION['display'] == MenuOption::Account) 
                    {
                        echo '
                                <section id="login">
                                <div class="container">
                                    <div">
                                        <div class="text-center">
                                            <h3 class="h3-blue">Verify your password to access personal page</h3>
                                            <form action="../../backend/artist/PersonalPageBackend.php" method="post">
                                                <div class="form-group col-4 mx-auto">';
                        if($_SESSION['logging_mode'] == LogModes::PERSONAL)
                        {
                            if($_SESSION['status'] == StatusCodes::ErrPassword)
                            {
                                $_SESSION['status'] = StatusCodes::ErrGeneric;
                                getStatusMessage("Wrong password", "");
                            }
                        }
                        echo '
                                                    <h5>Password</h5>
                                                    <input name = "verify_password" type="password" class="form-control form-control-sm" id="exampleInputPassword1" placeholder="Password">
                                                </div>
                                                <div class="text-center">
                                                    <input type = "submit" class="btn btn-primary" role="button" aria-pressed="true" name = "button" value = "Verify" onclick="window.location.reload();">
                                                </div>
                                            </form>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            ';
                    }
                    //Sell siliqas to USD/CAD/EUR
                    else if ($_SESSION['display'] == MenuOption::Balance) 
                    {
                        fiatInit();
                    } 
                    else if ($_SESSION['display'] == MenuOption::Investors) 
                    {
                    }
                    ?>
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
    <script>
        var slider = document.getElementById("myRange");
        var output = document.getElementById("demo");
        output.innerHTML = slider.value;

        slider.oninput = function() {
            output.innerHTML = this.value;
        }
    </script>
    <script src="js/scripts.js"></script>
</body>

</html>