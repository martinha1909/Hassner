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
                                <input id="amount_raising" type="text" class="form-control" placeholder="Enter amount">
                            </div>
                            <div class="form-group">
                                <h5>How many shares are you distributing?</h5>
                                <input id="shares_dist" type="text" class="form-control" id="signupUsername" placeholder="Enter amount of share">
                            </div>

                            <div class="mx-auto pt-5 text-center">
                                <input id="ipo_btn" type = "submit" class="btn btn-primary" role="button" name = "button" value = "Continue">
                            </div>

                            <?php showJSStatusMsg(); ?>

                    <?php
                        } 
                        else 
                        {
                    ?>
                            <div class="py-4">
                                <input id="quotes_btn" type = "submit" class="btn btn-secondary" role="button" value = "<?php echo EthosOption::QUOTES; ?>"> 
                                <input id="buy_back_shares_btn" type = "submit" class="btn btn-secondary" role="button" value = "<?php echo EthosOption::BUY_BACK_SHARES; ?>"> 
                                <input id="history_btn" type = "submit" class="btn btn-secondary" role="button" value = "<?php echo EthosOption::HISTORY; ?>"> 
                            </div>

                            <div class="div-hidden" id="quotes_content">
                                <?php
                                    printArtistQuotesTab($_SESSION['username'], $account_info);
                                ?>
                            </div>

                            <div class="div-hidden" id="buy_back_shares_content">
                                <?php
                                    printArtistBuyBackSharesTab($_SESSION['username']);
                                ?>
                            </div>

                            <div class="div-hidden" id="history_content">
                                <?php
                                    printArtistHistoryTab($_SESSION['username']);
                                ?>
                            </div>
                    <?php
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
    <script src="../js/artist/EthosOptions.js"></script>
    <script src="../js/artist/InjectShares.js"></script>
    <script src="../js/shared/transaction/BuyOrSellShares.js"></script>
    <script src="../js/artist/TradeHistory.js"></script>
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