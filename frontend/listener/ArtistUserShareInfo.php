<?php
    include '../../APIs/control/dependencies.php';
    include '../../APIs/shared/MarketplaceBackend.php';
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
    <link rel="stylesheet" href="../css/slidebar.css" id="theme-color">
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
            
            <!-- displaying user account info (i.e balance) -->
            <?php
            echo '<div style="color: #11171a; font-weight: bold; background-color:white; border-left: 4px solid #11171a; border-right: 10px solid white;">';
            echo "&nbsp;(q̶): ";
            echo round($_SESSION['user_balance'], 2);
            echo '  <br>
                    &nbsp;&nbsp;Δ%: +50.3
                </div>';
    ?>
        </nav>
    </div>
</section>

<?php
    fetchMarketPrice($_SESSION['selected_artist']);
?>

<!-- listener functionality -->
<section id="login">
    <div class="container-fluid">
        <div class="row vh-md-100 align-items-start">
            <div class="mx-auto my-auto text-center col">             
                <div class="py-4 text-center">
                    <h2>Your shares with <?php echo $_SESSION['selected_artist'];?> </h2>
                </div>

                <!-- displaying current share information between current user and selected artist -->
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
                            <!-- displaying Amount of shares owned, selected artist name, 
                                market price per share of artist, profit since last bought, 
                                and amount of available shares for purchase, respectively -->
                            <th scope="row"><?php 
                                echo $_SESSION['shares_owned']; ?></th>
                                <td><?php echo $_SESSION['selected_artist']; ?></td>
                                <td><?php echo round($_SESSION['current_pps']['price_per_share'],2); ?></td>
                                <td><?php echo $_SESSION['profit']; ?> (<?php echo $_SESSION['profit_rate']; ?>%)</td>
                                <td><?php echo $_SESSION['available_shares']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mx-auto my-auto text-center col-4">
                <?php
                    //Sell shares button is only available if you own some shares
                    if($_SESSION['shares_owned'] > 0)
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
                        $lower_bound = getLowerBound($_SESSION['selected_artist']);
                        //for now the user can ask for a price from the lower bound to 5 times more than the current price per share
                        $upper_bound = $_SESSION['current_pps']['price_per_share'] * 5;
                        echo '
                            <h6>How many shares are you selling?</h6>
                            <div class="wrapper-searchbar">
                                <div class="container-searchbar mx-auto">
                                    <label>
                                        <form action="../../APIs/listener/SellSharesBackend.php" method="post">
                                            <input name = "purchase_quantity" type="range" min="1" max='.$_SESSION['shares_owned'].' value="1" class="slider" id="myRange">
                                            <p>Quantity: <span id="demo"></span></p>
                                            <input type="text" name="asked_price" class="form-control" style="border-color: white;" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter siliqas">
                                            <p>Minimum: '.$lower_bound.'q̶</p>
                                            <input type="submit" class="btn btn-primary" role="button" aria-pressed="true" value="Post" onclick="window.location.reload();">
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

<section class="vh-md-100" id = "Marketplace">
    <div class="container-fluid">
        <div class="row vh-md-100 align-items-start">
            <div class="mx-auto my-auto text-center col">
                <div class="py-4 text-center">
                    <h2> 
                        <?php
                            echo $_SESSION['selected_artist'];
                        ?>
                        's Marketplace
                    </h2>
                </div>
                <?php
                   askedPriceInit();

                   echo '
                        <div class="py-4 text-left">
                            <h3>Market Price</h3>
                        </div>
                    ';
                    
                    //If the amount of artist shares has not sold out or the artist has distributed some shares, makes Buy option available 
                    if($_SESSION['available_shares'] > 0)
                    {
                        //replaces the Buy button with a slide bar ranging from 0 to the quantity that is equivalent to the maximum available share for purchase
                        if($_SESSION['buy_market_price'] == 0)
                        {
                            echo '
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">Seller username</th>
                                            <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">Price per share(q̶)</th>
                                            <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">Quantity</th>
                                            <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">+</th>
                                            <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">'.$_SESSION['selected_artist'].'</th>
                                                <td>'.$_SESSION['current_pps']['price_per_share'].'</td>
                                                <td>'.$_SESSION['available_shares'].'</td>
                                                <form action="../../APIs/listener/ToggleBuyMarketPriceBackend.php" method="post">
                                                    <td><input name="buy_user_selling_price" role="button" type="submit" class="btn btn-primary" value="Buy" onclick="window.location.reload();"></td>
                                                </form>
                                        </tr>
                                    </tbody>
                                </table>
                            ';
                        }
                        else
                        {
                            $_SESSION['seller_toggle'] = $_SESSION['selected_artist'];
                            $_SESSION['purchase_price'] = $_SESSION['current_pps']['price_per_share'];
                            echo '
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">Seller username</th>
                                            <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">Price per share(q̶)</th>
                                            <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">Quantity</th>
                                            <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">+</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">'.$_SESSION['selected_artist'].'</th>
                                                <td>'.$_SESSION['current_pps']['price_per_share'].'</td>
                                                <td>'.$_SESSION['available_shares'].'</td>
                                                <td>
                                                    <form action="../../APIs/listener/BuySharesBackend.php" method="post">
                                                        <input name = "purchase_quantity" type="range" min="1" max='.$_SESSION['available_shares'].' value="1" class="slider" id="myRange">
                                                        <p>Quantity: <span id="demo"></span></p>
                                                        <input name="buy_user_selling_price" type="submit" id="abc" style="border:1px transparent; background-color: transparent;" role="button" aria-pressed="true" value="->">
                                                    </form>
                                                    <form action="../../APIs/listener/ToggleBuyMarketPriceBackend.php" method="post">
                                                        <td><input name="buy_user_selling_price" type="submit" id="abc" style="border:1px transparent; background-color: transparent;" role="button" aria-pressed="true" value="-" onclick="window.location.reload();"></td>
                                                    </form>
                                                </td>
                                        </tr>
                                    </tbody>
                                </table>
                            ';
                        }
                    }
                    else
                    {
                        echo '
                            <div class="py-4 text-center">
                                <h4>No shares are currently available from '.$_SESSION['selected_artist'].'</h4>
                            </div>
                        ';
                    }
                ?>
            </div>
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