<?php
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
                        include '../../APIs/logic.php';
                        include '../../APIs/connection.php';
                        $conn = connect();
                        $result = searchAccount($conn, $_SESSION['username']);
                        $account = $result->fetch_assoc();
                    ?>
                    <div class="wrapper-searchbar">
                        <div class="container-searchbar">
                            <label>
                                <span class="screen-reader-text">Search for...</span>
                                <form class="form-inline" action="../APIs/SearchSongsConnection.php" method="post">
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
                                        <form action="../APIs/DisplaySwitch.php" method="post">
                                            <input name="display_type" type="submit" id="menu-style" style="border:1px white; background-color: transparent; color: #ff9100;" value="My Portfolio ->"
                                        </form>
                                    </li>
                                ';
                            }
                            else
                            {
                                echo '
                                    <li class="list-group-item-no-hover">
                                        <form action="../APIs/DisplaySwitch.php" method="post">
                                            <input name="display_type" type="submit" id="abc-no-underline" style="font-weight: bold; border:1px transparent; background-color: transparent;" value="My Portfolio">
                                        </form>
                                    </li>
                                ';
                            }
                            if($_SESSION['display'] == 1)
                            {
                                echo '
                                    <li class="list-group-item-no-hover" style="border-color: white; border-bottom: 2px solid white; border-top: 2px solid white; border-right-color: #11171a;">
                                        <form action="../APIs/DisplaySwitch.php" method="post">
                                            <input name="display_type" type="submit" id="menu-style" style="border:1px orange; background-color: transparent; color: #ff9100;" value="Top Invested Artists ->">
                                        </form>
                                    </li>
                                ';
                            }
                            else
                            {
                                echo '
                                    <li class="list-group-item-no-hover">
                                        <form action="../APIs/DisplaySwitch.php" method="post">
                                            <input name="display_type" type="submit" id="abc-no-underline" style="font-weight: bold; border:1px transparent; background-color: transparent;" value="Top Invested Artists">
                                        </form>
                                    </li>
                                ';
                            }
                            if($_SESSION['display'] == 3)
                            {
                                echo '
                                    <li class="list-group-item-no-hover" style="border-color: white; border-bottom: 2px solid white; border-top: 2px solid white; border-right-color: #11171a;">
                                        <form action="../APIs/DisplaySwitch.php" method="post">
                                            <input name="display_type" type="submit" id="menu-style" style="border:1px orange; background-color: transparent; color: #ff9100;" value="Buy Siliqas ->">
                                        </form>
                                    </li>
                                ';
                            }
                            else
                            {
                                echo '
                                    <li class="list-group-item-no-hover">
                                        <form action="../APIs/DisplaySwitch.php" method="post">
                                            <input name="display_type" type="submit" id="abc-no-underline" style="font-weight: bold;border:1px orange; background-color: transparent;" value="Buy Siliqas">
                                        </form>
                                    </li>
                                ';
                            }
                            if($_SESSION['display'] == 4)
                            {
                                echo '
                                    <li class="list-group-item-no-hover" style="border-color: white; border-bottom: 2px solid white; border-top: 2px solid white; border-right-color: #11171a;">
                                        <form action="../APIs/DisplaySwitch.php" method="post">
                                            <input name="display_type" type="submit" id="menu-style" style="border:1px orange; background-color: transparent; color: #ff9100;" value="Sell Siliqas ->">
                                        </form>
                                    </li>
                                ';
                            }
                            else
                            {
                                echo '
                                    <li class="list-group-item-no-hover">
                                        <form action="../APIs/DisplaySwitch.php" method="post">
                                            <input name="display_type" type="submit" id="abc-no-underline" style="font-weight: bold; border:1px orange; background-color: transparent;" value="Sell Siliqas">
                                        </form>
                                    </li>
                                ';
                            }
                            if($_SESSION['display'] == 5)
                            {
                                echo '
                                    <li class="list-group-item-no-hover" style="border-color: white; border-bottom: 2px solid white; border-top: 2px solid white; border-right-color: #11171a;">
                                        <form action="../APIs/DisplaySwitch.php" method="post">
                                            <input name="display_type" type="submit" id="menu-style" style="border:1px orange; background-color: transparent; color: #ff9100;" value="Account ->">
                                        </form>
                                    </li>
                                ';
                            }
                            else
                            {
                                echo '
                                    <li class="list-group-item-no-hover">
                                        <form action="../APIs/DisplaySwitch.php" method="post">
                                            <input name="display_type" type="submit" id="abc-no-underline" style="font-weight: bold; border:1px orange; background-color: transparent;" value="Account">
                                        </form>
                                    </li>
                                ';
                            }
                            if($_SESSION['display'] == 6)
                            {
                                echo '
                                    <li class="list-group-item-no-hover" style="border-color: white; border-bottom: 2px solid white; border-top: 2px solid white; border-right-color: #11171a;">
                                        <form action="../APIs/DisplaySwitch.php" method="post">
                                            <input name="display_type" type="submit" id="menu-style" style="border:1px orange; background-color: transparent; color: #ff9100;" value="Settings ->">
                                        </form>
                                    </li>
                                ';
                            }
                            else
                            {
                                echo '
                                    <li class="list-group-item-no-hover">
                                        <form action="../APIs/DisplaySwitch.php" method="post">
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
                                    <a class="dropdown-item" id="dashboard-hover" style="background-color: transparent;" href="login.php">Log out</a>
                                </li>
                            ';
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