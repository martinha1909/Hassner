<?php
    include '../../APIs/control/Dependencies.php';
    include '../../APIs/listener/AccountInfoBackend.php';
    
    $_SESSION['conversion_rate'] = -0.05;
    $_SESSION['coins'];
    $_SESSION['notify'];
    $_SESSION['cad'];
    $_SESSION['btn_show'];
?>

<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>HASSNER - INVESTOR</title>
        <meta name="description"
            content="Rateify is a music service that allows users to rate songs"/>

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
                <nav class="navbar navbar-expand-md navbar-dark bg-orange justify-content-between">
                    <a id = "href-hover" class="navbar-brand heading-black" href="#" onclick='window.location.reload();'>
                        HASSNER
                    </a>

                    <?php
                        $account = getAccount($_SESSION['username']);
                        $_SESSION['user_balance'] = $account['balance'];
                    ?>
                    <div class="wrapper-searchbar">
                        <div class="container-searchbar">
                            <label>
                                <span class="screen-reader-text">Search for...</span>
                                <form class="form-inline" action="../../APIs/listener/SearchPageBackend.php" method="post">
                                    <input type="search" class="search-field" placeholder="Search for Artist(s)" value="" name="artist_search" />
                                </form>
                            </label>
                        </div>
                    </div>
                    <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse"
                            data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                            aria-label="Toggle navigation">
                        <span data-feather="grid"></span>
                    </button>
                </nav>
            </div>
        </section>
        
        <section class="py-0" id="login">
            <div class="container-fluid">
                <div class="row">
                    <ul class="list-group bg-dark">
                        <?php
                            //By default My Portfolio is selected
                            //When My Portfolio is selected
                            if($_SESSION['display'] == 0 || $_SESSION['display'] == 2)
                            {
                                echo '
                                    <li class="list-group-item-no-hover" style="border-color: white; border-bottom: 2px solid white; border-top: 2px #11171a; border-right-color: #11171a;">
                                        <form action="../../APIs/control/MenuDisplayBackend.php" method="post">
                                            <input name="display_type" type="submit" id="menu-style" style="border:1px white; background-color: transparent; color: #ff9100;" value="My Portfolio ->"
                                        </form>
                                    </li>
                                ';
                            }
                            else
                            {
                                echo '
                                    <li class="list-group-item-no-hover">
                                        <form action="../../APIs/control/MenuDisplayBackend.php" method="post">
                                            <input name="display_type" type="submit" id="abc-no-underline" style="font-weight: bold; border:1px transparent; background-color: transparent;" value="My Portfolio">
                                        </form>
                                    </li>
                                ';
                            }

                            //When Top Invested Artist is selected
                            if($_SESSION['display'] == 1)
                            {
                                echo '
                                    <li class="list-group-item-no-hover" style="border-color: white; border-bottom: 2px solid white; border-top: 2px solid white; border-right-color: #11171a;">
                                        <form action="../../APIs/control/MenuDisplayBackend.php" method="post">
                                            <input name="display_type" type="submit" id="menu-style" style="border:1px orange; background-color: transparent; color: #ff9100;" value="Top Invested Artists ->">
                                        </form>
                                    </li>
                                ';
                            }
                            else
                            {
                                echo '
                                    <li class="list-group-item-no-hover">
                                        <form action="../../APIs/control/MenuDisplayBackend.php" method="post">
                                            <input name="display_type" type="submit" id="abc-no-underline" style="font-weight: bold; border:1px transparent; background-color: transparent;" value="Top Invested Artists">
                                        </form>
                                    </li>
                                ';
                            }

                            //When Buy Siliqas is selected
                            if($_SESSION['display'] == 3)
                            {
                                echo '
                                    <li class="list-group-item-no-hover" style="border-color: white; border-bottom: 2px solid white; border-top: 2px solid white; border-right-color: #11171a;">
                                        <form action="../../APIs/control/MenuDisplayBackend.php" method="post">
                                            <input name="display_type" type="submit" id="menu-style" style="border:1px orange; background-color: transparent; color: #ff9100;" value="Buy Siliqas ->">
                                        </form>
                                    </li>
                                ';
                            }
                            else
                            {
                                echo '
                                    <li class="list-group-item-no-hover">
                                        <form action="../../APIs/control/MenuDisplayBackend.php" method="post">
                                            <input name="display_type" type="submit" id="abc-no-underline" style="font-weight: bold;border:1px orange; background-color: transparent;" value="Buy Siliqas">
                                        </form>
                                    </li>
                                ';
                            }

                            //When Sell Siliqas is selected
                            if($_SESSION['display'] == 4)
                            {
                                echo '
                                    <li class="list-group-item-no-hover" style="border-color: white; border-bottom: 2px solid white; border-top: 2px solid white; border-right-color: #11171a;">
                                        <form action="../../APIs/control/MenuDisplayBackend.php" method="post">
                                            <input name="display_type" type="submit" id="menu-style" style="border:1px orange; background-color: transparent; color: #ff9100;" value="Sell Siliqas ->">
                                        </form>
                                    </li>
                                ';
                            }
                            else
                            {
                                echo '
                                    <li class="list-group-item-no-hover">
                                        <form action="../../APIs/control/MenuDisplayBackend.php" method="post">
                                            <input name="display_type" type="submit" id="abc-no-underline" style="font-weight: bold; border:1px orange; background-color: transparent;" value="Sell Siliqas">
                                        </form>
                                    </li>
                                ';
                            }

                            //When Account is selected
                            if($_SESSION['display'] == 5)
                            {
                                echo '
                                    <li class="list-group-item-no-hover" style="border-color: white; border-bottom: 2px solid white; border-top: 2px solid white; border-right-color: #11171a;">
                                        <form action="../../APIs/control/MenuDisplayBackend.php" method="post">
                                            <input name="display_type" type="submit" id="menu-style" style="border:1px orange; background-color: transparent; color: #ff9100;" value="Account ->">
                                        </form>
                                    </li>
                                ';
                            }
                            else
                            {
                                echo '
                                    <li class="list-group-item-no-hover">
                                        <form action="../../APIs/control/MenuDisplayBackend.php" method="post">
                                            <input name="display_type" type="submit" id="abc-no-underline" style="font-weight: bold; border:1px orange; background-color: transparent;" value="Account">
                                        </form>
                                    </li>
                                ';
                            }

                            //When settings is selected
                            if($_SESSION['display'] == 6)
                            {
                                echo '
                                    <li class="list-group-item-no-hover" style="border-color: white; border-bottom: 2px solid white; border-top: 2px solid white; border-right-color: #11171a;">
                                        <form action="../../APIs/control/MenuDisplayBackend.php" method="post">
                                            <input name="display_type" type="submit" id="menu-style" style="border:1px orange; background-color: transparent; color: #ff9100;" value="Communities ->">
                                        </form>
                                    </li>
                                ';
                            }
                            else
                            {
                                echo '
                                    <li class="list-group-item-no-hover">
                                        <form action="../../APIs/control/MenuDisplayBackend.php" method="post">
                                            <input name="display_type" type="submit" id="abc-no-underline" style="font-weight: bold; border:1px orange; background-color: transparent;" value="Communities">
                                        </form>
                                    </li>
                                ';
                            }
                            
                            //Logout option
                            echo '
                                <li class="list-group-item-no-hover"></li>
                                <li class="list-group-item-no-hover"></li>
                                <li class="list-group-item-no-hover"></li>
                                <li class="list-group-item-no-hover"></li>
                                <li class="list-group-item-no-hover"></li>
                                <li class="list-group-item-no-hover"></li>
                                <li class="list-group-item-no-hover" style="padding-top: 52px;"></li>
                                <li class="list-group-item-no-hover" style="border-bottom: 2px solid white;">
                                    <a class="dropdown-item" id="dashboard-hover" style="background-color: transparent;" href="../credentials/login.php">Log out</a>
                                </li>
                            ';
                        ?>
                    </ul>
                    <ul class="list-group col">
                        <?php                    
                            //displaying My Portfolio
                            if($_SESSION['display'] == 0 || $_SESSION['display'] == 2)
                            {
                                include '../../APIs/listener/MyPortfolioBackend.php';    
                                echo '
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col" style="color: white;" class="bg-dark">#</th>
                                                <form action="../../APIs/control/SortPortfolioArtistBackEnd.php">
                                                    <th scope="col" class="bg-dark"><input type = "submit" id="href-hover" style="border:1px transparent; background-color: transparent; color: white; font-weight: bold;" role="button" aria-pressed="true" value = "Artist" onclick="window.location.reload();">
                                ';
                                //sort Artist ascending alphabetically
                                if($_SESSION['sort_type'] == 1)
                                {
                                    echo " ↑";
                                }
                                //sort Artist descending alphabetically
                                else if($_SESSION['sort_type'] == 4)
                                {
                                    echo " ↓";
                                }
                                else
                                    echo "";
                                echo '
                                                </th>
                                                </form>
                                                <form action="../../APIs/control/SortPortfolioShareBackEnd.php">
                                                    <th scope="col" class="bg-dark"><input type = "submit" id="href-hover" style="border:1px transparent; background-color: transparent; color: white; font-weight: bold;" role="button" aria-pressed="true" value = "Shares bought" onclick="window.location.reload();">';
                                //sort Shares bought ascending alphabetically
                                if($_SESSION['sort_type'] == 2)
                                    echo " ↑";
                                //sort Shares bought descending alphabetically
                                else if($_SESSION['sort_type'] == 5)
                                    echo " ↓";
                                else
                                    echo "";
                                echo '
                                                </th>
                                                </form>
                                                <form action = "../../APIs/control/SortPortfolioPPSBackEnd.php">
                                                    <th scope="col" class="bg-dark"><input type = "submit" id="href-hover" style="border:1px transparent; background-color: transparent; color: white; font-weight: bold;" role="button" aria-pressed="true" value = "Price per share (q̶)" onclick="window.location.reload();">';
                                //sort Price per share ascending alphabetically
                                if($_SESSION['sort_type'] == 3)
                                    echo " ↑";
                                //sort Price per share descending alphabetically
                                else if($_SESSION['sort_type'] == 6)
                                    echo " ↓";
                                else
                                    echo "";
    
                                echo '
                                                </th>
                                                </form>
                                                <form action = "../../APIs/control/SortPortfolioRateBackEnd.php">
                                                    <th scope="col" class="bg-dark"><input type = "submit" id="href-hover" style="border:1px transparent; background-color: transparent; color: white; font-weight: bold;" role="button" aria-pressed="true" value = "Rate" onclick="window.location.reload();">';
                                //sort Rate ascending alphabetically
                                if($_SESSION['sort_type'] == 0)
                                    echo ' ↑';
                                //sort Rate descending alphabetically
                                else if($_SESSION['sort_type'] == 7)
                                    echo " ↓";
                                else
                                    echo "";
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
                                if($my_investments->num_rows == 0)
                                {
                                    echo '<h3> No results </h3>';
                                }
                                else
                                {
                                    $artist_name = "";
                                    $rate = 0;
                                    
                                    //retrieving data from the data base to populate arrays that store information of artists that the user has invested in
                                    populateVars($all_shares_bought, $all_artists, $artist_name, $rate, $all_profits, $all_rates, $all_price_per_share, $my_investments);
                                    
                                    if($_SESSION['sort_type'] == 0)
                                    {
                                        sortChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "Rate", "Ascending");
                                    }
                                    else if($_SESSION['sort_type'] == 1)
                                    {
                                        sortChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "Artist", "Ascending");
                                    }
                                    else if($_SESSION['sort_type'] == 2)
                                    {
                                        sortChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "Share", "Ascending");
                                    }
                                    else if($_SESSION['sort_type'] == 3)
                                    {
                                        sortChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "PPS", "Ascending");
                                    }
                                    else if($_SESSION['sort_type'] == 4)
                                    {
                                        sortChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "Artist", "Descending");
                                    }
                                    else if($_SESSION['sort_type'] == 5)
                                    {
                                        sortChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "Share", "Descending");
                                    }
                                    else if($_SESSION['sort_type'] == 6)
                                    {
                                        sortChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "PPS", "Descending");
                                    }
                                    else if ($_SESSION['sort_type'] == 7)
                                    {
                                        sortChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "Rate", "Descending");
                                    }
                                    printMyPortfolioChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share);
                                }
                                echo '</tbody>
                                    </table>';
                                $artist_usernames = array();
                                //profit relative to the current price per share of the artist
                                $profits = array();
                                $selling_prices = array();
                                $share_amounts = array();
                                //update the sahres that the user is currently selling
                                fetchUserSellingShares($_SESSION['username'], $artist_usernames, $profits, $selling_prices, $share_amounts);
                                echo'    
                                    <h3>Your active shares</h3>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">Artist</th>
                                                <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">Selling for (q̶)</th>
                                                <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">Quantity</th>
                                                <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">Gain/Loss Unrealized</th>
                                                <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                                $conn = connect();
                                for($i=0; $i<sizeof($selling_prices); $i++)
                                {
                                    echo'
                                            <tr>
                                                <th scope="row">'.$artist_usernames[$i].'</th>
                                                    <td>'.$selling_prices[$i].'</td>
                                                    <td>'.$share_amounts[$i].'</td>
                                                    <td>'.$profits[$i].'%</td>';
                                    //Edits sold shares where the suer can fix the bid price and the quantity
                                    //If the user chooses the new quantity to be 0, the current selling share would be removed from the market 
                                    //and other users can't buy the current share anymore. 
                                    if((strcmp($_SESSION['artist_share_remove'], $artist_usernames[$i]) == 0) && ($_SESSION['share_price_remove'] == $selling_prices[$i]))
                                    {
                                        $max_quantity = getMaxShareQuantity($_SESSION['username'], $artist_usernames[$i]);
                                        //seding old bid prices, old quantity of shares being sold, and new bid prices and new quatity of shares being sold
                                        echo '
                                                    <form action="../../APIs/listener/ConfirmEditSellingShareBackend.php" method="post">
                                                        <td>
                                                            <input name = "new_quantity" type="range" min="0" max='.$max_quantity.' value="'.$share_amounts[$i].'" class="slider" id="myRange">
                                                            <p>New Quantity: <span id="demo"></span></p>
                                                            <p>New Asked Price: <span id="asked_value"></span></p>
                                                            <input type="text" name="new_asked_price" class="form-control" style="border-color: white;" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter new amount">
                                                            <input name="old_asked_price['.$selling_prices[$i].']" style="cursor: context-menu; border:1px transparent; background-color: transparent;">
                                                            <input name="old_quantity['.$share_amounts[$i].']" style="cursor: context-menu; border:1px transparent; background-color: transparent;">
                                                            <input name="artist_name['.$artist_usernames[$i].']"type="submit" id="abc" style="border:1px transparent; background-color: transparent;" role="button" aria-pressed="true" value="->" onclick="window.location.reload();">
                                                        </td>
                                                    </form>
                                            </tr>
                                        ';
                                    }
                                    else
                                    {
                                        echo '
                                                    <form action="../../APIs/listener/EditSellingShareBackend.php" method="post">
                                                        <td>
                                                            <input name="remove_share_artist['.$artist_usernames[$i].']" style="cursor: context-menu; border:1px transparent; background-color: transparent;">
                                                            <input name="remove_share_price['.$selling_prices[$i].']" type="submit" id="abc" style="border:1px transparent; background-color: transparent;" role="button" aria-pressed="true" value="✏" onclick="window.location.reload();">
                                                        </td>
                                                    </form>
                                            </tr>
                                        ';
                                    }
                                }
                                echo '
                                        </tbody>
                                    </table>
                                ';
                            }

                            //displaying Top Invested Artist
                            else if($_SESSION['display'] == 1)
                            {
                                echo '
                                    <table class="table">
                                        <thead class="thead-orange">
                                            <tr>
                                                <th scope="col" class="bg-dark" id="href-hover";"><input type = "submit" style="border:1px transparent; background-color: transparent; color: white; font-weight: bold;" aria-pressed="true" value = "#"></th>
                                                <th scope="col" class="bg-dark" id="href-hover";"><input type = "submit" style="border:1px transparent; background-color: transparent; color: white; font-weight: bold;" aria-pressed="true" value = "Artist"></th>
                                                <th scope="col" class="bg-dark" id="href-hover";"><input type = "submit" style="border:1px transparent; background-color: transparent; color: white; font-weight: bold;" aria-pressed="true" value = "Shares bought"></th>
                                                <th scope="col" class="bg-dark" id="href-hover";"><input type = "submit" style="border:1px transparent; background-color: transparent; color: white; font-weight: bold;" aria-pressed="true" value = "Price per share (q̶)"></th>
                                                <th scope="col" class="bg-dark" id="href-hover";"><input type = "submit" style="border:1px transparent; background-color: transparent; color: white; font-weight: bold;" aria-pressed="true" value = "Rate"></th>
                                            </tr>
                                        </thead>
                                    <tbody>
                                ';
                                echo '<form action="../../APIs/artist/ArtistShareInfoBackend.php" method="post">';
                                include "../../APIs/listener/TopInvestedArtistBackend.php";
                                $result = query_account('artist');
                                if($result->num_rows == 0)
                                {
                                    echo '<h3> There are no artists to display </h3>';
                                }
                                else
                                {
                                    $all_shares = array();
                                    $users = array();
                                    populateArray($all_shares, $users, $result);
                                    sortArrays($all_shares, $users);
                                    printTopInvestedArtistChart($users, $all_shares);
                                }
                                echo '</form>';
                                echo '</table>';
                            }

                            //displaying Buy Siliqas functionality
                            else if($_SESSION['display'] == 3)
                            {
                                $balance = getUserBalance($_SESSION['username']);

                                //notify
                                if($_SESSION['notify'] == 1)
                                    echo "<script>alert('Siliqas bought successfully');</script>";
                                if($_SESSION['notify'] == 2)
                                    echo "<script>alert('Please fill out all forms');</script>";
                                $_SESSION['notify'] = 0;

                                echo '
                                    <section id="login" class="py-5";>
                                        <div class="container">
                                            <div class="col-12 mx-auto my-auto text-center">
                                                <form action="../../APIs/shared/CurrencyBackend.php" method="post">
                                ';
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
                                            <form action = "../../APIs/listener/CheckConversionBackend.php" method = "post">
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
                                            <h5 style="padding-top:150px;">Enter Amount in '.$_SESSION['currency'].'</h5>
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
                                        <form action = "Checkout.php" method = "post">
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
                            //displaying Sell Siliqas functionality
                            else if($_SESSION['display'] == 4)
                            {
                                $balance = getUserBalance($_SESSION['username']);

                                sellSiliqasInit($balance);
                            }
                            
                            //Account page functionality
                            else if($_SESSION['display'] == 5)
                            {
                                if($_SESSION['notify'] == 3)
                                {
                                    echo "<script>alert('Incorrect Password');</script>";
                                }
                                $_SESSION['notify'] = 0;
                                echo '
                                    <section id="login">
                                    <div class="container">
                                        <div">
                                            <div class="col-12 mx-auto my-auto text-center">
                                                <h3 style="color: orange;padding-top:150px;">Verify your password to access personal page</h3>
                                                <form action="../../APIs/listener/PersonalPageBackend.php" method="post">
                                                    <div class="form-group">
                                                        <h5>Password</h5>
                                                        <input name = "verify_password" type="password" style="border-color: white;" class="form-control form-control-sm" id="exampleInputPassword1" placeholder="Password">
                                                    </div>
                                                    <div class="col-md-8 col-12 mx-auto pt-5 text-center">
                                                        <input type = "submit" class="btn btn-primary" role="button" aria-pressed="true" name = "button" value = "Verify" onclick="window.location.reload();">
                                                    </div>
                                                </form>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                ';
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </section>

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
