<?php
include '../../backend/control/Dependencies.php';
include '../../backend/shared/MarketplaceBackend.php';
include '../../backend/constants/LoggingModes.php';

$account = getAccount($_SESSION['username']);
$_SESSION['user_balance'] = $account['balance'];
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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/default.css" id="theme-color">
    <link rel="stylesheet" href="../css/menu.css" id="theme-color">
    <link rel="stylesheet" href="../css/searchbar.css" id="theme-color">
</head>

<body class="bg-dark">
    <section class="smart-scroll">
        <div class="container-xxl">
            <nav class="navbar navbar-expand-md navbar-dark bg-darkcyan">
                <a id="href-hover" class="navbar-brand heading-black" href="#" onclick='window.location.reload();'>
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
            </nav>
        </div>
    </section>

    <section id="login">
        <div class="container-fluid">
            <div class="row">
                <ul class="list-group">
                    <?php
                    //By default My Portfolio is selected
                    //When My Portfolio is selected
                    if ($_SESSION['display'] == 0 || $_SESSION['display'] == "PORTFOLIO") {
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
                    if ($_SESSION['display'] == "CAMPAIGN") {
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
                    if ($_SESSION['display'] == "SILIQAS") {
                        echo '
                                    <li class="selected-no-hover list-group-item-no-hover">
                                        <form action="../../backend/control/MenuDisplayListenerBackend.php" method="post">
                                            <input name="display_type" type="submit" id="menu-style" class="menu-text" value="※ Siliqas">
                                        </form>
                                    </li>
                                ';
                    } else {
                        echo '
                                    <li class="list-group-item-no-hover">
                                        <form action="../../backend/control/MenuDisplayListenerBackend.php" method="post">
                                            <input name="display_type" type="submit" id="abc-no-underline" class="menu-text" value="Siliqas">
                                        </form>
                                    </li>
                                ';
                    }

                    //When Artists is selected
                    if ($_SESSION['display'] == "ARTISTS") {
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
                    if ($_SESSION['display'] == "ACCOUNT") {
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
                        if ($_SESSION['display'] == 0 || $_SESSION['display'] == "PORTFOLIO") {
                            echo '
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col" style="color: white;" class="bg-dark">#</th>
                                                <form action="../../backend/listener/SortPortfolioArtistBackEnd.php">
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
                                                <form action="../../backend/listener/SortPortfolioShareBackEnd.php">
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
                                                <form action = "../../backend/listener/SortPortfolioPPSBackEnd.php">
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
                                                <form action = "../../backend/listener/SortPortfolioRateBackEnd.php">
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
                            $my_investments = queryInvestment($_SESSION['username']);
                            $all_profits = 0;
                            $all_rates = array();
                            $all_price_per_share = array();
                            $all_shares_bought = array();
                            $all_artists = array();
                            if ($my_investments->num_rows == 0) {
                                echo '<h3> No results </h3>';
                            } else {
                                $artist_name = "";
                                $rate = 0;

                                //retrieving data from the data base to populate arrays that store information of artists that the user has invested in
                                populateVars($all_shares_bought, $all_artists, $artist_name, $rate, $all_profits, $all_rates, $all_price_per_share, $my_investments);

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
                                combineDuplicateRows($all_artists, $all_shares_bought, $all_rates, $all_price_per_share);
                                printMyPortfolioChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share);
                            }
                            echo '</tbody>
                                    </table>';

                            //Displaying sell order section
                            $artist_usernames = array();
                            $roi = array();
                            $selling_prices = array();
                            $share_amounts = array();
                            $profits = array();
                            $date_posted = array();
                            $time_posted = array();
                            $ids = array();

                            //update the shares that the user is currently selling
                            fetchSellOrders(
                                $_SESSION['username'],
                                $artist_usernames,
                                $roi,
                                $selling_prices,
                                $share_amounts,
                                $profits,
                                $date_posted,
                                $time_posted,
                                $ids
                            );

                            if (sizeof($selling_prices) > 0) {
                                echo '    
                                    
                                    <div class="container py-6 my-auto mx-auto">    
                                    <h3>Sell orders</h3>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th class="th-tan" scope="col">Order ID</th>
                                                    <th class="th-tan" scope="col">Artist</th>
                                                    <th class="th-tan" scope="col">Selling for (q̶)</th>
                                                    <th class="th-tan" scope="col">Quantity</th>
                                                    <th class="th-tan" scope="col">ROI</th>
                                                    <th class="th-tan" scope="col">Gain/Loss (q̶)</th>
                                                    <th class="th-tan" scope="col">Date Posted</th>
                                                    <th class="th-tan" scope="col">Time Posted</th>
                                                    <th class="th-tan" scope="col">Remove Order</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
                                for ($i = 0; $i < sizeof($selling_prices); $i++) {
                                    //Allowing users to remove/cancek their share order
                                    echo '
                                                <form action="../../backend/listener/RemoveSellOrderBackend.php" method="post">
                                                    <tr>
                                                        <th scope="row"><input name="remove_id" class="cursor-context" value = "' . $ids[$i] . '"></th>
                                                        <td>' . $artist_usernames[$i] . '</th>
                                                        <td>' . $selling_prices[$i] . '</td>
                                                        <td>' . $share_amounts[$i] . '</td>
                                                        <td>' . $roi[$i] . '%</td>
                                                        <td>' . $profits[$i] . '</td>
                                                        <td>' . dateParser($date_posted[$i]) . '</td>
                                                        <td>' . timeParser($time_posted[$i]) . '</td>
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

                            //Displaying buy order section

                            //reusing some variable names since this comes after sell order
                            $artist_usernames = array();
                            $quantities_requested = array();
                            $siliqas_requested = array();
                            $date_posted = array();
                            $time_posted = array();
                            $buy_order_ids = array();


                            fetchBuyOrders(
                                $_SESSION['username'],
                                $artist_usernames,
                                $quantities_requested,
                                $siliqas_requested,
                                $date_posted,
                                $time_posted,
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
                                                        <th class="th-tan" scope="col">Siliqas Requested</th>
                                                        <th class="th-tan" scope="col">Quantity</th>
                                                        <th class="th-tan" scope="col">Date Posted</th>
                                                        <th class="th-tan" scope="col">Time Posted</th>
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
                                                        <td>' . dateParser($date_posted[$i]) . '</td>
                                                        <td>' . timeParser($time_posted[$i]) . '</td>
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
                        }

                        //displaying Top Invested Artist
                        else if ($_SESSION['display'] == "ARTISTS") {
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
                        } else if ($_SESSION['display'] == "SILIQAS") {
                            siliqasInit();
                        }

                        //Account page functionality
                        else if ($_SESSION['display'] == "ACCOUNT") {
                            echo '
                                    <section id="login">
                                        <div class="container">
                                            <div class="col-4 mx-auto my-auto text-center">
                                                <h3 class="h3-tan py-4">Verify your password to access personal page</h3>
                                                <form action="../../backend/listener/PersonalPageBackend.php" method="post">
                                                    <div class="form-group">
                                                        <h5>Password</h5>
                                                        <input name = "verify_password" type="password" class="form-control form-control-sm" id="exampleInputPassword1" placeholder="Password">';
                            if ($_SESSION['logging_mode'] == LogModes::PERSONAL) {
                                getStatusMessage("Incorrect Password, please try again", "");
                            }
                            echo '
                                                    </div>
                                                    <div class="col-md-8 col-12 mx-auto pt-5 text-center">
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

    <a class="li-bottom btn btn-secondary" type="submit" role="button" aria-pressed="true" name="button" href="../credentials/login.php">Log out</a>

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