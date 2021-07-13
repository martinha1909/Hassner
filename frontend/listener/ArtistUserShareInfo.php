<?php
    include '../../APIs/control/dependencies.php';
    session_start();
    $_SESSION['conversion_rate'];
    $_SESSION['coins'] = 0;
    $_SESSION['notify'];
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Hassner - Share interaction</title>
    <meta name="description"
          content="Rateify is a music service that allows users to rate songs"/>

    <!--Inter UI font-->
    <link href="https://rsms.me/inter/inter-ui.css" rel="stylesheet">

    <!-- Bootstrap CSS / Color Scheme -->
    <link rel="stylesheet" href="../css/default.css" id="theme-color">
    <link rel="stylesheet" href="../css/searchbar.css" id="theme-color">
</head>
<body class="bg-dark">

<!--navigation-->
<section class="smart-scroll">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-md navbar-dark bg-orange justify-content-between">
            <a id="href-hover" class="navbar-brand heading-black" href="listener.php">
                HASSNER
            </a>

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
            <?php
            echo '<div style="color: #11171a; font-weight: bold; background-color:white; border-left: 4px solid #11171a; border-right: 10px solid white;">';
            echo "&nbsp;(q̶): ";
            echo round($_SESSION['user_balance']['balance'], 2);
            echo '  <br>
                    &nbsp;&nbsp;Δ%: +50.3
                </div>';
    ?>
        </nav>
    </div>
</section>

<!-- listener functionality -->
<section id="login">
    <div class="container-fluid">
        <div class="row vh-md-100 align-items-start">
            <div class="mx-auto my-auto text-center col">             
                <div class="py-4 text-center">
                    <h2>Your shares with <?php echo $_SESSION['selected_artist'];?> </h2>
                </div>

              <table class="table">
                    <thead>
                        <tr>
                            <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">Owned Shares</th>
                            <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">Artist</th>
                            <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">Current price per share (q̶)</th>
                            <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">Selling profit per share (q̶)</th>
                            <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">Available Shares</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row"><?php echo $_SESSION['shares_owned']['no_of_share_bought']; ?></th>
                                <td><?php echo $_SESSION['selected_artist']; ?></td>
                                <td>Siliqas: <?php echo round($_SESSION['current_pps']['price_per_share'],2); ?></td>
                                <td>Siliqas: <?php echo round($_SESSION['profit'],2) ?> (<?php echo round($_SESSION['profit_rate'], 2); ?>%)</td>
                                <td><?php echo $_SESSION['available_shares']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mx-auto my-auto text-center col-4">
                <?php
                    //Buy more share button is only available if there are available shares to buy
                    if($_SESSION['available_shares'] > 0)
                    {
                        echo '
                            <form action="../../APIs/listener/ToggleBuySellShareBackend.php" method="post">
                                <input name="buy_sell" type="submit" id="menu-style-invert" style=" border:1px orange; background-color: transparent;" value="+Buy more shares">
                            </form>
                        ';
                        echo "<br>";
                    }
                    //Sell shares button is only available if you own some shares
                    if($_SESSION['shares_owned']['no_of_share_bought'] > 0)
                    {
                        echo '
                            <form action="../../APIs/listener/ToggleBuySellShareBackend.php" method="post">
                                <input name="buy_sell" type="submit" id="menu-style-invert" style=" border:1px orange; background-color: transparent;" value="-Sell your shares">
                            </form>
                        ';
                        echo "<br>";
                    }
                    //displaying sell shares button if user chooses the options
                    if($_SESSION['buy_sell'] == "SELL")
                    {
                        echo '
                            <h6>How many shares are you selling?</h6>
                            <div class="wrapper-searchbar">
                                <div class="container-searchbar mx-auto">
                                    <label>
                                        <form action="../APIs/ShareSellConnection.php" method="post">
                                            <input type="search" "class="search-field" placeholder="Enter share amount" name="share" />
                                        </form>
                                    </label> 
                                </div>
                            </div>
                        ';
                        $_SESSION['buy_sell'] = 0;
                    }
                ?>
            </div>
        </div>
    </div>  
</section>

<section class="vh-md-100">
    <div class="container-fluid">
        <div class="row vh-md-100 align-items-start">
            <div class="py-4 text-center">
                <h2> 
                    <?php
                        echo $_SESSION['selected_artist'];
                    ?>
                    's Marketplace
                </h2>
            </div>
            <?php
                //displaying buy more share button if user chooses the options
                if($_SESSION['available_shares'] > 0)
                {
                    include '../../APIs/listener/ArtistShareMarketplaceBackend.php';
                    $users_sell_prices = fetchMarketplace($_SESSION['selected_artist']);
                    echo '
                        <div class="py-6 text-center">
                            <h3>From Users: </h3>
                        </div>
                    ';
                }
            ?>
        </div>
    </div>
</section>




<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.7.3/feather.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>