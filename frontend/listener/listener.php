<?php
include '../../backend/control/Dependencies.php';
include '../../backend/shared/MarketplaceHelpers.php';
include '../../backend/shared/CampaignHelpers.php';
include '../../backend/constants/LoggingModes.php';
include '../../backend/constants/BalanceOption.php';
include '../../backend/object/ParticipantList.php';
include '../../backend/object/CampaignParticipant.php';
include '../../backend/object/Node.php';
include '../../backend/object/TickerInfo.php';

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
    <title>HASSNER - INVESTOR</title>
    <meta name="description" content="Rateify is a music service that allows users to rate songs" />

    <!--Inter UI font-->
    <link href="https://rsms.me/inter/inter-ui.css" rel="stylesheet">

    <!-- Bootstrap CSS / Color Scheme -->
    <link rel="icon" href="../../frontend/Images/hx_tmp_2.ico" type="image/ico">
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
                    HASSNER
                </a>

                <div class="wrapper-searchbar mx-auto">
                    <div class="container-searchbar">
                        <label>
                            <span class="screen-reader-text">Search for...</span>
                            <form class="form-inline" action="../../backend/listener/SearchPageBackend.php" method="post">
                                <input type="search" class="search-field" placeholder="Search for Artist(s)" value="" name="artist_search" />
                            </form>
                        </label>
                    </div>
                </div>
                <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span data-feather="grid"></span>
                </button>

                    <div class="user-balance">
                        <?php
                        echo ' &nbsp;($USD): ';
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
                    checkRaffleRoll();
                    //By default My Portfolio is selected
                    //When My Portfolio is selected
                    if ($_SESSION['display'] == MenuOption::None || $_SESSION['display'] == MenuOption::Portfolio) {
                        echo '
                                    <li class="selected-no-hover list-group-item-no-hover">
                                        <form action="../../backend/control/MenuDisplayListenerBackend.php" method="post">
                                            <input name="display_type" type="submit" id="menu-style" class="menu-text" value="❖ Portfolio"
                                        </form>
                                    </li>
                                ';
                    } else {
                        echo '
                                    <li class="list-group-item-no-hover">
                                        <form action="../../backend/control/MenuDisplayListenerBackend.php" method="post">
                                            <input name="display_type" type="submit" id="abc-no-underline" class="menu-text" value="Portfolio">
                                        </form>
                                    </li>
                                ';
                    }

                    //When settings is selected
                    if ($_SESSION['display'] == MenuOption::Campaign) {
                        echo '
                                    <li class="selected-no-hover list-group-item-no-hover">
                                        <form action="../../backend/control/MenuDisplayListenerBackend.php" method="post">
                                            <input name="display_type" type="submit" id="menu-style" class="menu-text" value="◔ Campaign">
                                        </form>
                                    </li>
                                ';
                    } else {
                        echo '
                                    <li class="list-group-item-no-hover">
                                        <form action="../../backend/control/MenuDisplayListenerBackend.php" method="post">
                                            <input name="display_type" type="submit" id="abc-no-underline" class="menu-text" value="Campaign">
                                        </form>
                                    </li>
                                ';
                    }

                    //When Siliqas option is selected
                    if ($_SESSION['display'] == MenuOption::Balance) {
                        echo '
                                    <li class="selected-no-hover list-group-item-no-hover">
                                        <form action="../../backend/control/MenuDisplayListenerBackend.php" method="post">
                                            <input name="display_type" type="submit" id="menu-style" class="menu-text" value="※ Balance">
                                        </form>
                                    </li>
                                ';
                    } else {
                        echo '
                                    <li class="list-group-item-no-hover">
                                        <form action="../../backend/control/MenuDisplayListenerBackend.php" method="post">
                                            <input name="display_type" type="submit" id="abc-no-underline" class="menu-text" value="Balance">
                                        </form>
                                    </li>
                                ';
                    }

                    //When Artists is selected
                    if ($_SESSION['display'] == MenuOption::Artists) {
                        echo '
                                    <li class="selected-no-hover list-group-item-no-hover">
                                        <form action="../../backend/control/MenuDisplayListenerBackend.php" method="post">
                                            <input name="display_type" type="submit" id="menu-style" class="menu-text" value="◈ Artists">
                                        </form>
                                    </li>
                                ';
                    } else {
                        echo '
                                    <li class="list-group-item-no-hover">
                                        <form action="../../backend/control/MenuDisplayListenerBackend.php" method="post">
                                            <input name="display_type" type="submit" id="abc-no-underline" class="menu-text" value="Artists">
                                        </form>
                                    </li>
                                ';
                    }

                    //When Account is selected
                    if ($_SESSION['display'] == MenuOption::Account) {
                        echo '
                                    <li class="selected-no-hover list-group-item-no-hover">
                                        <form action="../../backend/control/MenuDisplayListenerBackend.php" method="post">
                                            <input name="display_type" type="submit" id="menu-style" class="menu-text" value="▤ Account">
                                        </form>
                                    </li>
                                ';
                    } else {
                        echo '
                                    <li class="list-group-item-no-hover">
                                        <form action="../../backend/control/MenuDisplayListenerBackend.php" method="post">
                                            <input name="display_type" type="submit" id="abc-no-underline" class="menu-text" value="Account">
                                        </form>
                                    </li>
                                ';
                    }

                    ?>
                </ul>
                <div class="container my-auto mx-auto col-6">
                    <ul class="list-group">
                        <?php
                        //displaying My Portfolio
                        if ($_SESSION['display'] == MenuOption::None || $_SESSION['display'] == MenuOption::Portfolio) {
                            echo '
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col" style="color: white;" class="bg-dark">#</th>
                                                <form action="../../backend/listener/SortPortfolioArtistHelpers.php">
                                                    <th scope="col"><input type = "submit" class="th-dark" role="button" aria-pressed="true" value = "Artist" onclick="window.location.reload();">
                                ';
                            //sort Artist ascending alphabetically
                            if ($_SESSION['sort_type'] == 1) {
                                echo " ↑";
                            }
                            //sort Artist descending alphabetically
                            else if ($_SESSION['sort_type'] == 4) {
                                echo " ↓";
                            } else {
                                echo "";
                            }
                            echo '
                                                </th>
                                                </form>
                                                <form action="../../backend/listener/SortPortfolioShareHelpers.php">
                                                    <th scope="col"><input type = "submit" class="th-dark" role="button" aria-pressed="true" value = "Shares bought" onclick="window.location.reload();">';
                            //sort Shares bought ascending alphabetically
                            if ($_SESSION['sort_type'] == 2) {
                                echo ' ↑';
                            }
                            //sort Shares bought descending alphabetically
                            else if ($_SESSION['sort_type'] == 5) {
                                echo " ↓";
                            } else {
                                echo "";
                            }
                            echo '
                                                </th>
                                                </form>
                                                <form action = "../../backend/listener/SortPortfolioPPSHelpers.php">
                                                    <th scope="col"><input type = "submit" class="th-dark" role="button" aria-pressed="true" value = "Price per share (q̶)" onclick="window.location.reload();">';
                            //sort Price per share ascending alphabetically
                            if ($_SESSION['sort_type'] == 3) {
                                echo ' ↑';
                            }
                            //sort Price per share descending alphabetically
                            else if ($_SESSION['sort_type'] == 6) {
                                echo " ↓";
                            } else {
                                echo "";
                            }

                            echo '
                                                </th>
                                                </form>
                                                <form action = "../../backend/listener/SortPortfolioRateHelpers.php">
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

                            if ($_SESSION['sort_type'] == 0) {
                                sortChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "Rate", "Ascending");
                            } else if ($_SESSION['sort_type'] == 1) {
                                sortChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "Artist", "Ascending");
                            } else if ($_SESSION['sort_type'] == 2) {
                                sortChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "Share", "Ascending");
                            } else if ($_SESSION['sort_type'] == 3) {
                                sortChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "PPS", "Ascending");
                            } else if ($_SESSION['sort_type'] == 4) {
                                sortChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "Artist", "Descending");
                            } else if ($_SESSION['sort_type'] == 5) {
                                sortChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "Share", "Descending");
                            } else if ($_SESSION['sort_type'] == 6) {
                                sortChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "PPS", "Descending");
                            } else if ($_SESSION['sort_type'] == 7) {
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
                        } else if ($_SESSION['display'] == MenuOption::Campaign) {
                            $artists = array();
                            $offerings = array();
                            $progress = array();
                            $time_left = array();
                            $minimum_ethos = array();
                            $owned_ethos = array();
                            $types = array();
                            $chances = array();
                            fetchInvestedArtistCampaigns(
                                $_SESSION['username'],
                                $artists,
                                $offerings,
                                $progress,
                                $time_left,
                                $minimum_ethos,
                                $owned_ethos,
                                $types,
                                $chances
                            );

                            if (sizeof($offerings) > 0) {
                                echo '
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Artist</th>
                                                <th scope="col">Offering</th>
                                                <th scope="col">Progess</th>
                                                <th scope="col">Time left</th>
                                                <th scope="col">Minimum Ethos</th>
                                                <th scope="col">Owned Ethos</th>
                                                <th scope="col">Chance of winning</th>
                                                </form>
                                                <th scope="col">Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                ';

                                for ($i = 0; $i < sizeof($artists); $i++) {
                                    echo '
                                                <tr>
                                                    <th>' . $artists[$i] . '</th>
                                                    <td>' . $offerings[$i] . '</td>
                                                    <td>' . round($progress[$i], 2) . '%</td>
                                                    <td>' . $time_left[$i] . '</td>
                                                    <td>' . $minimum_ethos[$i] . '</td>
                                                    <td>' . $owned_ethos[$i] . '</td>
                                    ';
                                    if ($chances[$i] != -1) {
                                        echo '
                                                        <form action="../../backend/listener/IncreaseChanceBackend.php" method="post">
                                                            <td>' . $chances[$i] . '%<input name = "artist_name[' . $artists[$i] . ']" type = "submit" id="abc" class="no-background" role="button" aria-pressed="true" value = " +"></td>
                                                        </form>
                                        ';
                                    } else {
                                        echo '
                                                        <td>N/A</td>
                                        ';
                                    }

                                    echo '
                                                    <td>' . $types[$i] . '</td>
                                                </tr>
                                    ';
                                }
                                echo '
                                            </tbody>
                                        </table>
                                ';
                            }

                            $artists = array();
                            $offerings = array();
                            $minimum_ethos = array();
                            $winners = array();
                            $time_releases = array();
                            $types = array();
                            fetchParticipatedCampaigns(
                                $_SESSION['username'],
                                $artists,
                                $offerings,
                                $minimum_ethos,
                                $winners,
                                $time_releases,
                                $types
                            );

                            echo '
                                <div class="py-6">
                                    <h4>Campaign that you participated</h4>
                            ';
                            if (sizeof($offerings) > 0) {
                                echo '
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Artist</th>
                                                    <th scope="col">Offering</th>
                                                    <th scope="col">Minimum Ethos</th>
                                                    <th scope="col">Winner</th>
                                                    <th scope="col">Type</th>
                                                    <th scope="col">Date Released</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                ';

                                for ($i = 0; $i < sizeof($artists); $i++) {
                                    if ($winners[$i] == $_SESSION['username']) {
                                        echo '
                                                    <tr>
                                                        <th class="campaign_winner">' . $artists[$i] . '</th>
                                                        <td class="campaign_winner">' . $offerings[$i] . '</td>
                                                        <td class="campaign_winner">' . $minimum_ethos[$i] . '</td>
                                                        <td class="campaign_winner">' . $winners[$i] . '</td>
                                                        <td class="campaign_winner">' . $types[$i] . '</td>
                                                        <td class="campaign_winner">' . $time_releases[$i] . '</td>
                                                    </tr>
                                        ';
                                    } else {
                                        echo '
                                                    <tr>
                                                        <th>' . $artists[$i] . '</th>
                                                        <td>' . $offerings[$i] . '</td>
                                                        <td>' . $minimum_ethos[$i] . '</td>
                                                        <td>' . $winners[$i] . '</td>
                                                        <td>' . $types[$i] . '</td>
                                                        <td>' . $time_releases[$i] . '</td>
                                                    </tr>
                                        ';
                                    }
                                }
                                echo '
                                                </tbody>
                                            </table>
                                    </div>
                                ';
                            } else {
                                echo '<h5>No campaigns participated</h5>';
                            }
                        }

                        //displaying Top Invested Artist
                        else if ($_SESSION['display'] == MenuOption::Artists) {
                            echo '
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col" id="href-hover";"><input class="th-dark" type = "submit" aria-pressed="true" value = "#"></th>
                                                <th scope="col" id="href-hover";"><input class="th-dark" type = "submit" aria-pressed="true" value = "Artist"></th>
                                                <th scope="col" id="href-hover";"><input class="th-dark" type = "submit" aria-pressed="true" value = "Shares bought"></th>
                                                <th scope="col" id="href-hover";"><input class="th-dark" type = "submit" aria-pressed="true" value = "Price per share (q̶)"></th>
                                                <th scope="col" id="href-hover";"><input class="th-dark" type = "submit" aria-pressed="true" value = "Rate"></th>
                                                <th scope="col" id="href-hover";"><input class="th-dark" type = "submit" aria-pressed="true" value = "Max Price"></th>
                                                <th scope="col" id="href-hover";"><input class="th-dark" type = "submit" aria-pressed="true" value = "Min Price"></th>
                                            </tr>
                                        </thead>
                                    <tbody>
                                ';
                            echo '<form action="../../backend/artist/ArtistShareInfoBackend.php" method="post">';
                            $result = query_account('artist');
                            if ($result->num_rows == 0) {
                                echo '<h3> There are no artists to display </h3>';
                            } else {
                                $all_shares = array();
                                $users = array();
                                topInvestedArtistInit($all_shares, $users, $result);
                                sortArrays($all_shares, $users);
                                printTopInvestedArtistChart($users, $all_shares);
                            }
                            echo '</form>';
                            echo '</table>';
                        } else if ($_SESSION['display'] == MenuOption::Balance) {
                            fiatInit();
                        }

                        //Account page functionality
                        else if ($_SESSION['display'] == MenuOption::Account) {
                            echo '
                                    <section id="login">
                                        <div class="container">
                                            <div class="text-center">
                                                <h3 class="h3-blue">Verify your password to access personal page</h3>
                                                <form action="../../backend/listener/PersonalPageBackend.php" method="post">
                                                    <div class="form-group col-4 mx-auto">
                                                        <input name = "verify_password" type="password" class="form-control form-control-sm" id="exampleInputPassword1" placeholder="Password">';
                            if ($_SESSION['logging_mode'] == LogModes::PERSONAL) {
                                getStatusMessage("Incorrect Password, please try again", "");
                            }
                            echo '
                                                    </div>
                                                    <div class="text-center">
                                                        <input type = "submit" class="btn btn-primary" role="button" aria-pressed="true" name = "button" value = "Verify" onclick="window.location.reload();">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </section>
                                ';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <a class="li-bottom btn btn-success py-2" type="submit" role="button" aria-pressed="true" name="button" href="../credentials/login.php">Log out</a>

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