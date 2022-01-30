<?php
    include '../../backend/control/Dependencies.php';
    include '../../backend/shared/include/MarketplaceHelpers.php';
    include '../../backend/shared/include/CampaignHelpers.php';
    include '../../backend/shared/include/frontendPrintHelpers.php';
    include '../../backend/constants/LoggingModes.php';
    include '../../backend/constants/BalanceOption.php';
    include '../../backend/constants/CampaignType.php';
    include '../../backend/object/ParticipantList.php';
    include '../../backend/object/CampaignParticipant.php';
    include '../../backend/object/Campaign.php';
    include '../../backend/object/Node.php';
    include '../../backend/object/Artist.php';
    include '../../backend/object/TickerInfo.php';

    $_SESSION['selected_artist'] = 0;

    $account = getAccount($_SESSION['username']);
    $_SESSION['user_balance'] = $account['balance'];

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/default.css" id="theme-color">
    <link rel="stylesheet" href="../css/menu.css" id="theme-color">
    <link rel="stylesheet" href="../css/searchbar.css" id="theme-color">
</head>

<body class="bg-dark">
    <section class="smart-scroll">
        <div class="container-xxl">
            <nav class="navbar navbar-expand-md navbar-dark bg-darkcyan">
                <a class="navbar-brand heading-black" href="#" onclick='window.location.reload();'>
                    ❖ HX
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
                <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span data-feather="grid"></span>
                </button>
                <div class="cursor-pointer user-balance">
                    <i class="fas fa-user-circle"></i>
                    <?php echo $_SESSION['username']?> | $<?php echo$_SESSION['user_balance']?>
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
                        checkRaffleRoll();
                    ?>
                    <li class="selected-no-hover list-group-item-no-hover" id="li_portfolio">
                        <input name="display_type" type="submit" id="portfolio_btn" class="menu-text menu-style" value="❖ Portfolio">
                    </li>

                    <li class="list-group-item-no-hover" id="li_campaign">
                        <input name="display_type" type="submit" id="campaign_btn" class="menu-text menu-no-underline" value="Campaign">
                    </li>

                    <li class="list-group-item-no-hover" id="li_balance">
                        <input name="display_type" type="submit" id="balance_btn" class="menu-text menu-no-underline" value="Balance">
                    </li>

                    <li class="list-group-item-no-hover" id="li_artists">
                        <input name="display_type" type="submit" id="artists_btn" class="menu-text menu-no-underline" value="Artists">
                    </li>

                    <li class="list-group-item-no-hover" id="li_account">
                        <input name="display_type" type="submit" id="account_btn" class="menu-text menu-no-underline" value="Account">
                    </li>
                </ul>
                <div class="container my-auto mx-auto col-6">
                    <ul class="list-group-campaign my-4">
                        <div id="portfolio_content">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col" style="color: white;" class="bg-dark">#</th>
                                        <form action="../../backend/listener/include/SortPortfolioArtistHelpers.php">
                                            <th scope="col"><input type = "submit" class="th-dark" role="button" aria-pressed="true" value = "Artist" onclick="window.location.reload();">
                            <?php
                            //sort Artist ascending alphabetically
                            if ($_SESSION['sort_type'] == 1) 
                            {
                                echo " ↑";
                            }
                            //sort Artist descending alphabetically
                            else if ($_SESSION['sort_type'] == 4) 
                            {
                                echo " ↓";
                            } 
                            else 
                            {
                                echo "";
                            }
                            echo '
                                                </th>
                                                </form>
                                                <form action="../../backend/listener/include/SortPortfolioShareHelpers.php">
                                                    <th scope="col"><input type = "submit" class="th-dark" role="button" aria-pressed="true" value = "Shares bought" onclick="window.location.reload();">';
                            //sort Shares bought ascending alphabetically
                            if ($_SESSION['sort_type'] == 2) 
                            {
                                echo ' ↑';
                            }
                            //sort Shares bought descending alphabetically
                            else if ($_SESSION['sort_type'] == 5) 
                            {
                                echo " ↓";
                            } 
                            else 
                            {
                                echo "";
                            }
                            echo '
                                                </th>
                                                </form>
                                                <form action = "../../backend/listener/include/SortPortfolioPPSHelpers.php">
                                                    <th scope="col"><input type = "submit" class="th-dark" role="button" aria-pressed="true" value = "Price per share" onclick="window.location.reload();">';
                            //sort Price per share ascending alphabetically
                            if ($_SESSION['sort_type'] == 3) 
                            {
                                echo ' ↑';
                            }
                            //sort Price per share descending alphabetically
                            else if ($_SESSION['sort_type'] == 6) 
                            {
                                echo " ↓";
                            }
                            else 
                            {
                                echo "";
                            }

                            echo '
                                                </th>
                                                </form>
                                                <form action = "../../backend/listener/include/SortPortfolioRateHelpers.php">
                                                    <th scope="col"><input type = "submit" class="th-dark" role="button" aria-pressed="true" value = "Last 24 hours" onclick="window.location.reload();">';
                            //sort Rate ascending alphabetically
                            if ($_SESSION['sort_type'] == 0) {
                                echo ' ↑';
                            }
                            //sort Rate descending alphabetically
                            else if ($_SESSION['sort_type'] == 7) {
                                echo " ↓";
                            } else {
                                echo "";
                            }

                            echo '
                                                </th>
                                                </form>
                                            </tr>
                                        </thead>
                                    <tbody>
                            ';
                            $all_rates = array();
                            $all_price_per_share = array();
                            $all_shares_bought = array();
                            $all_artists = array();
                            $artist_name = "";
                            $rate = 0;
                            //retrieving data from the data base to populate arrays that store information of artists that the user has invested in
                            populateVars($_SESSION['username'], $all_artists, $all_shares_bought, $all_rates, $all_price_per_share);

                            if ($_SESSION['sort_type'] == 0) 
                            {
                                sortChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "Rate", "Ascending");
                            } 
                            else if ($_SESSION['sort_type'] == 1) 
                            {
                                sortChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "Artist", "Ascending");
                            } 
                            else if ($_SESSION['sort_type'] == 2) 
                            {
                                sortChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "Share", "Ascending");
                            } 
                            else if ($_SESSION['sort_type'] == 3) 
                            {
                                sortChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "PPS", "Ascending");
                            } 
                            else if ($_SESSION['sort_type'] == 4) 
                            {
                                sortChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "Artist", "Descending");
                            } 
                            else if ($_SESSION['sort_type'] == 5) 
                            {
                                sortChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "Share", "Descending");
                            } 
                            else if ($_SESSION['sort_type'] == 6) 
                            {
                                sortChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "PPS", "Descending");
                            } 
                            else if ($_SESSION['sort_type'] == 7) 
                            {
                                sortChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "Rate", "Descending");
                            }
                            printMyPortfolioChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share);
                            echo '
                                    </tbody>
                                </table>
                            ';

                            sellOrderInit();

                            //Displaying buy order section

                            //reusing some variable names since this comes after sell order
                            $artist_usernames = array();
                            $quantities_requested = array();
                            $siliqas_requested = array();
                            $date_posted = array();
                            $buy_order_ids = array();


                            fetchBuyOrders(
                                $_SESSION['username'],
                                $artist_usernames,
                                $quantities_requested,
                                $siliqas_requested,
                                $date_posted,
                                $buy_order_ids
                            );

                            if (sizeof($artist_usernames) > 0) {
                                echo '
                                        <div class="container py-6 my-auto mx-auto">    
                                        <h3>Buy orders</h3>
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="th-tan" scope="col">Order ID</th>
                                                        <th class="th-tan" scope="col">Artist</th>
                                                        <th class="th-tan" scope="col">Amount Requested</th>
                                                        <th class="th-tan" scope="col">Quantity</th>
                                                        <th class="th-tan" scope="col">Date Posted</th>
                                                        <th class="th-tan" scope="col">Remove Order</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                    ';

                                for ($i = 0; $i < sizeof($artist_usernames); $i++) {
                                    echo '
                                                <form action="../../backend/listener/RemoveBuyOrderBackend.php" method="post">
                                                    <tr>
                                                        <th scope="row"><input name="remove_id" class="cursor-context" value = "' . $buy_order_ids[$i] . '"></th>
                                                        <td>' . $artist_usernames[$i] . '</td>
                                                        <td>' . $siliqas_requested[$i] . '</td>
                                                        <td>' . $quantities_requested[$i] . '</td>
                                                        <td>' . $date_posted[$i] . '</td>
                                                        <td><input type="submit" id="abc" class="cursor-context" role="button" aria-pressed="true" value="☉" onclick="window.location.reload();"></td>
                                                    </tr>
                                                </form>
                                        ';
                                }

                                echo '
                                            </tbody>
                                        </table>
                                        </div>
                                ';
                            }
                        ?>
                        </div>

                        <div class="div-hidden" id="campaign_content">
                            <div class="py-4">
                                <h4 class="h4-blue">Participating</h4>
                                <?php
                                    printParticipatingCampaignTable($_SESSION['username']);
                                ?>
                            </div>

                            <div class="py-4">
                                <h4 class="h4-blue">Potential Participation</h4>
                                <?php
                                    printNearParticipationCampaignTable($_SESSION['username']);
                                ?>
                            </div>

                            <div class="py-4">
                                <h4 class="h4-blue">Past Participation</h4>
                                <?php
                                    printPastParticipatedCampaignTable($_SESSION['username']);
                                ?>
                            </div>
                        </div>

                        <div class="div-hidden" id="artists_content">
                            <?php
                                //displaying Top Invested Artist
                                $all_artists = getAllArtist();

                                if(sizeof($all_artists) == 0)
                                {
                                    echo "<h3>No artists to display<h3>";
                                }
                                else
                                {
                                    followedArtist($_SESSION['username']);

                                    topsAndFlops($all_artists);

                                    apex($all_artists);

                                    localArtist();
                                }
                            ?>
                        </div>

                        <div class="div-hidden" id="balance_content">
                            <?php
                                fiatInit();
                            ?>
                        </div>

                        <div class="div-hidden" id="account_content">
                            <section id="login">
                                <div class="container">
                                    <div class="text-center">
                                        <h3 class="h3-blue">Verify your password to access personal page</h3>
                                        <div class="form-group col-4 mx-auto">
                                            <input id="listener_personal_pwd" type="password" class="form-control form-control-sm" placeholder="Password">
                                            <p id="listener_personal_status"></p>
                                        </div>
                                        <div class="text-center">
                                            <input id="listener_personal_btn" type = "submit" class="btn btn-primary" role="button" value = "Verify">
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </ul>
                </div>
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
    <script src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
    <script src="js/scripts.js"></script>
    <script src="../js/shared/balance/DepositWithdraw.js"></script>
    <script src="../js/listener/MenuItem.js"></script>
    <script src="../js/shared/account/AccountPage.js"></script>
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
