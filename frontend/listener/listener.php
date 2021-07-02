<?php
    include '../../APIs/control/Dependencies.php';
    session_start();
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
                        include '../../APIs/listener/AccountInfoBackend.php';
                        $account = getAccount($_SESSION['username']);
                    ?>
                    <div class="wrapper-searchbar">
                        <div class="container-searchbar">
                            <label>
                                <span class="screen-reader-text">Search for...</span>
                                <form class="form-inline" action="../../APIs/artist/SearchArtistBackend.php" method="post">
                                    <input type="search" class="search-field" placeholder="Search for Artist(s)" value="" name="artist_name" />
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
                            if($_SESSION['display'] == 2 || $_SESSION['display'] == 0)
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
                            if($_SESSION['display'] == 6)
                            {
                                echo '
                                    <li class="list-group-item-no-hover" style="border-color: white; border-bottom: 2px solid white; border-top: 2px solid white; border-right-color: #11171a;">
                                        <form action="../../APIs/control/MenuDisplayBackend.php" method="post">
                                            <input name="display_type" type="submit" id="menu-style" style="border:1px orange; background-color: transparent; color: #ff9100;" value="Settings ->">
                                        </form>
                                    </li>
                                ';
                            }
                            else
                            {
                                echo '
                                    <li class="list-group-item-no-hover">
                                        <form action="../../APIs/control/MenuDisplayBackend.php" method="post">
                                            <input name="display_type" type="submit" id="abc-no-underline" style="font-weight: bold; border:1px orange; background-color: transparent;" value="Settings">
                                        </form>
                                    </li>
                                ';
                            }
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
                            if($_SESSION['display'] == 0 || $_SESSION['display'] == 2)
                            {
                                echo '
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col" style="color: white;" class="bg-dark">#</th>
                                                <form action="../../APIs/control/SortPortfolioArtistBackEnd.php">
                                                    <th scope="col" class="bg-dark"><input type = "submit" id="href-hover" style="border:1px transparent; background-color: transparent; color: white; font-weight: bold;" role="button" aria-pressed="true" value = "Artist" onclick="window.location.reload();">
                                ';
                                if($_SESSION['sort_type'] == 1)
                                    echo " ↑";
                                else if($_SESSION['sort_type'] == 4)
                                    echo " ↓";
                                else
                                    echo "";
                                echo '
                                                </th>
                                                </form>
                                                <form action="../../APIs/control/SortPortfolioShareBackEnd.php">
                                                    <th scope="col" class="bg-dark"><input type = "submit" id="href-hover" style="border:1px transparent; background-color: transparent; color: white; font-weight: bold;" role="button" aria-pressed="true" value = "Shares bought" onclick="window.location.reload();">';
                                if($_SESSION['sort_type'] == 2)
                                    echo " ↑";
                                else if($_SESSION['sort_type'] == 5)
                                    echo " ↓";
                                else
                                    echo "";
                                echo '
                                                </th>
                                                </form>
                                                <form action = "../../APIs/control/SortPortfolioPPSBackEnd.php">
                                                    <th scope="col" class="bg-dark"><input type = "submit" id="href-hover" style="border:1px transparent; background-color: transparent; color: white; font-weight: bold;" role="button" aria-pressed="true" value = "Price per share (q̶)" onclick="window.location.reload();">';
                                if($_SESSION['sort_type'] == 3)
                                    echo " ↑";
                                else if($_SESSION['sort_type'] == 6)
                                    echo " ↓";
                                else
                                    echo "";
    
                                echo '
                                                </th>
                                                </form>
                                                <form action = "../../APIs/control/SortPortfolioRateBackEnd.php">
                                                    <th scope="col" class="bg-dark"><input type = "submit" id="href-hover" style="border:1px transparent; background-color: transparent; color: white; font-weight: bold;" role="button" aria-pressed="true" value = "Rate" onclick="window.location.reload();">';
                                if($_SESSION['sort_type'] == 0)
                                    echo ' ↑';
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
                                include '../../APIs/listener/MyPortfolioBackend.php';
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
                                        sortArtist($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "Share", "Ascending");
                                    }
                                    else if($_SESSION['sort_type'] == 3)
                                    {
                                        sortArtist($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "PPS", "Ascending");
                                    }
                                    else if($_SESSION['sort_type'] == 4)
                                    {
                                        sortChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "Artist", "Descending");
                                    }
                                    else if($_SESSION['sort_type'] == 5)
                                    {
                                        sortArtist($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "Share", "Descending");
                                    }
                                    else if($_SESSION['sort_type'] == 6)
                                    {
                                        sortArtist($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "PPS", "Descending");
                                    }
                                    else if ($_SESSION['sort_type'] == 7)
                                    {
                                        sortChart($all_artists, $all_shares_bought, $all_rates, $all_price_per_share, "Rate", "Descending");
                                    }
                                    $id = 1;
                                    echo '<form action="../../APIs/artist/ArtistShareInfoBackend.php" method="post">';
                                    for($i=0; $i<sizeof($all_artists); $i++)
                                    {
                                        if($all_shares_bought[$i] != 0)
                                        {
                                            echo '<tr><th scope="row">'.$id.'</th><td><input name = "artist_name['.$all_artists[$i].']" type = "submit" id="abc" style="border:1px transparent; background-color: transparent;" role="button" aria-pressed="true" value = "'.$all_artists[$i].'"></td><td>'.$all_shares_bought[$i].'</td><td>'.$all_price_per_share[$i].'</td>';
                                            if($all_rates[$i] > 0)
                                                echo '<td class="increase">+'.$all_rates[$i].'%</td></tr>';
                                            else if($all_rates[$i] == 0)
                                                echo '<td>'.$all_rates[$i].'%</td></tr>';
                                            else
                                                echo '<td class="decrease">'.$all_rates[$i].'%</td></tr>';
                                            $id++;
                                        }
                                    }
                                    echo '</form>';
                                }
                                echo '</tbody>
                                    </table>';
                            }
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
                                    $id = 1;
                                    for($i=0; $i<sizeof($all_shares); $i++)
                                    {
                                        if($id == 6)
                                            break;
                                        $price_per_share = getArtistPricePerShare($users[$i]);
                                        $rate = getArtistCurrentRate($users[$i]);
                                        echo '<tr><th scope="row">'.$id.'</th>
                                                    <td><input name = "artist_name['.$users[$i].']" type = "submit" id="abc" style="border:1px transparent; background-color: transparent;" role="button" aria-pressed="true" value = "'.$users[$i].'"></td></td>
                                                    <td style="color: white">'.$all_shares[$i].'</td>
                                                    <td style="color: white">'.$price_per_share.'</td>';
                                        if($rate > 0)
                                            echo '<td class="increase">+'.$rate.'%</td></tr>';
                                        else if($rate == 0)
                                            echo '<td>'.$rate.'%</td></tr>';
                                        else
                                            echo '<td class="decrease">'.$rate.'%</td></tr>';       
                                        $id++;
                                    }
                                    
                                }
                                echo '</form>';
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
    </body>
</html>