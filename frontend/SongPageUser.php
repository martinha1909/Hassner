<?php
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
    <title>Rateify - Search Songs</title>
    <meta name="description"
          content="Rateify is a music service that allows users to rate songs"/>

    <!--Inter UI font-->
    <link href="https://rsms.me/inter/inter-ui.css" rel="stylesheet">

    <!-- Bootstrap CSS / Color Scheme -->
    <link rel="stylesheet" href="css/default.css" id="theme-color">
    <link rel="stylesheet" href="css/searchbar.css" id="theme-color">
</head>
<body class="bg-dark">

<!--navigation-->
<section class="smart-scroll">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-md navbar-dark bg-orange justify-content-between">
            <a id="href-hover" class="navbar-brand heading-black" href="listener.php">
                HASSNER
            </a>
            <?php
                        include '../APIs/logic.php';
                        include '../APIs/connection.php';
                        $conn = connect();
                        $result = getUserBalance($conn, $_SESSION['username']);
                        $balance = $result->fetch_assoc();
                    ?>
                    <div class="wrapper-searchbar">
                            <div class="container-searchbar">
                                    <label>
                                        <span class="screen-reader-text">Search for...</span>
                                        <form class="form-inline" action="../APIs/SearchSongsConnection.php" method="post">
                                            <input type="search" class="search-field" placeholder="Search for Artist(s)" value="" name="artist_name" />
                                        </form>
                                    </label>
                                    <!-- <input type="submit" class="search-submit button" value="&#xf002" /> -->
                                    
                            </div>
                        </div>
            <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse"
                    data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span data-feather="grid"></span>
            </button>
            <?php
        echo ' <div style="color: #11171a; font-weight: bold; background-color:white; border-left: 4px solid #11171a; border-right: 10px solid white;">';
                            echo "&nbsp;(q̶): ";
                            echo round($balance['balance'], 2);
                            echo '<br>
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
                    <h2>Your shares with <?php echo $_SESSION['artist'];?> </h2>
                    
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
              <!-- view song form -->
              <?php
                    $result = searchArtistUserShares($conn, $_SESSION['username'], $_SESSION['artist']);
                    $shares_bought = 0;
                    if($result->num_rows > 0)
                    {
                        $_SESSION['current_no_of_shares'] = $result->fetch_assoc();
                        $shares_bought = $_SESSION['current_no_of_shares']['no_of_share_bought'];
                    }
                    else
                        $shares_bought = 0;
                    $result2 = getArtistShares($conn, $_SESSION['artist']);
                    $result3 = searchArtistShares($conn, $_SESSION['artist']);
                    $total_shares_bought = $result3->fetch_assoc();
                    $share_distributed = $result2->fetch_assoc();
                    $_SESSION['shares_available'] = $share_distributed['Share_Distributed'] - $total_shares_bought['Shares'];
                    $_SESSION['profit'] = $_SESSION['per_share_price'] * $_SESSION['rate'];
                    $_SESSION['profit'] = $_SESSION['profit'] + $_SESSION['per_share_price'];
                    $rate = $_SESSION['rate'] * 100;
                    echo '<tr><th scope="row">'.$shares_bought.'</th><td>'.$_SESSION['artist'].'</td><td>Siliqas: '.round($_SESSION['per_share_price'],2).'</td><td>Siliqas: '.round($_SESSION['profit'],2).' ('.$rate.'%)</td><td>'.$_SESSION['shares_available'].'</td></tr>';
                  ?>
              </tbody>
            </table>
                
                <a class= "py-2 nav-link page-scroll d-inline-flex" href="#New_releases" role="button" aria-pressed="true">
                        ↓ Releases ↓
                    </a>
           
            </div>
            <div class="mx-auto my-auto text-center col-4">
        <?php
                $result = searchArtistShares($conn, $_SESSION['artist']);
                $result2 = getArtistShares($conn, $_SESSION['artist']);
                $share_distributed = $result2->fetch_assoc();
                $no_shares_left = $result->fetch_assoc();
                if($no_shares_left['Shares'] != $share_distributed['Share_Distributed'])
                {
                    echo '<form action="../APIs/BuySellConnection.php" method="post">
                    <input name="buy_sell" type="submit" id="menu-style-invert" style=" border:1px orange; background-color: transparent;" value="+Buy more shares">
                    </form>';
                    // echo '<div><a href="RatingView.php"> +Buy more shares</a></div>';
                    echo "<br>";
                }
                if($shares_bought > 0)
                {
                    echo '<form action="../APIs/BuySellConnection.php" method="post">
                            <input name="buy_sell" type="submit" id="menu-style-invert" style=" border:1px orange; background-color: transparent;" value="-Sell your shares">
                        </form>';
                    echo "<br>";
                }
                if($_SESSION['buy_sell'] == "BUY")
                {
                    echo '<h6>How many shares are you buying ?</h6>
                    <div" class="wrapper-searchbar">
                                <div class="container-searchbar mx-auto">
                                        <label>
                                            <form action="../APIs/RatingConnection.php" method="post">
                                                <input type="search" "class="search-field" placeholder="Enter share amount" name="share" />
                                            </form>
                                        </label>
                                        <!-- <input type="submit" class="search-submit button" value="&#xf002" /> -->
                                        
                                </div>
                            </div>';
                    $_SESSION['buy_sell'] = 0;
                }
                else if($_SESSION['buy_sell'] == "SELL")
                {
                    
                    echo '<h6>How many shares are you selling?</h6>
                    <div class="wrapper-searchbar">
                                <div class="container-searchbar mx-auto">
                                        <label>
                                            <form action="../APIs/ShareSellConnection.php" method="post">
                                                <input type="search" "class="search-field" placeholder="Enter share amount" name="share" />
                                            </form>
                                        </label>
                                        <!-- <input type="submit" class="search-submit button" value="&#xf002" /> -->
                                        
                                </div>
                            </div>';
                    $_SESSION['buy_sell'] = 0;
                }
                ?>
        </div>
        </div>
    </div>  
           
</section>
<section class="vh-md-100" id="New_releases">
<a style="float: right;" class= "nav-link page-scroll" href="#albums" role="button" aria-pressed="true">Albums</a>
<a style="float: right;" class= "nav-link page-scroll" href="#singles" role="button" aria-pressed="true">Singles</a>
<a style="float: right;" class= "nav-link page-scroll" href="#posts" role="button" aria-pressed="true">Posts</a>
    <div>
                
            <h2 style="color: #ff9100; font-weight: bold;"><?php echo $_SESSION['artist'];?> Releases</h2>
            <h4 id="posts">Posts</h4>
            <table class="table">
                <?php
                    echo '<h6>No recent posts</h6>';
                ?>
            </table>
            <h3 id="singles">Singles</h3>
            <table class="table">
                    <?php
                        $result = searchArtistSingles($conn, $_SESSION['artist']);
                        if(sizeof($result) > 0)
                        {
                            echo '
                            <thead class="thead-light">
                            <tr>
                                <th scope="col" style="background-color: #ff9100; border-color: #ff9100; color: #11171a;">#</th>
                                <th scope="col" style="background-color: #ff9100; border-color: #ff9100; color: #11171a;">Name</th>
                                <th scope="col" style="background-color: #ff9100; border-color: #ff9100; color: #11171a;">Plays</th>
                                <th scope="col" style="background-color: #ff9100; border-color: #ff9100; color: #11171a;">Duration</th>
                                <th scope="col" style="background-color: #ff9100; border-color: #ff9100; color: #11171a;">Date</th>
                            </tr>
                            </thead>
                            <tbody>';
                            $id = 1;
                            for($i=0; $i<sizeof($result); $i++)
                            {
                                echo '<tr><th scope="row">'.$id.'</th>
                                    <td>'.$result[$i]['name'].'</td>
                                    <td>'.$result[$i]['no_of_plays'].'</td>
                                    <td>'.$result[$i]['duration'].'</td>
                                    <td>'.$result[$i]['date_created'].'</td></tr>';
                                $id++;
                            }
                            echo '</tbody>';
                        }
                        else
                            echo '<h6>No new releases</h6>';
                    ?>
            </table>

            <h4 id="albums">Albums</h4>
            <table class="table">
            <?php
                $result = searchArtistAlbum($conn, $_SESSION['artist']);
                $latest_albums = array();
                if($result->num_rows > 0)
                {
                    while($row = $result->fetch_assoc())
                    {
                        $result2 = searchAlbum($conn, $row['album_name']);
                        $album_info = $result2->fetch_assoc();
                        if($album_info['Published'] == 1)
                            array_push($latest_albums, $album_info);
                    }
                }
                if(sizeof($latest_albums) > 0)
                {
                    echo '
                        <thead class="thead-light">
                        <tr>
                            <th scope="col" style="background-color: #ff9100; border-color: #ff9100; color: #11171a;">#</th>
                            <th scope="col" style="background-color: #ff9100; border-color: #ff9100; color: #11171a;">Name</th>
                            <th scope="col" style="background-color: #ff9100; border-color: #ff9100; color: #11171a;">Songs</th>
                            <th scope="col" style="background-color: #ff9100; border-color: #ff9100; color: #11171a;">Duration</th>
                            <th scope="col" style="background-color: #ff9100; border-color: #ff9100; color: #11171a;">Date</th>
                        </tr>
                        </thead>
                        <tbody>';
                    $id = 1;
                    for($i=0; $i < sizeof($latest_albums); $i++)
                    {
                        $songs_in_album = array();
                        $result2 = searchSongsInAlbum($conn, $latest_albums[$i]['name']);
                        while($row2 = $result2->fetch_assoc())
                        {
                            $result3 = searchSong($conn, $row2['song_id']);
                            while($song_name = $result3 -> fetch_assoc())
                            {
                                if($song_name['Published'] == 1)
                                    array_push($songs_in_album, $song_name['name']);
                            }
                        }   
                        if(sizeof($songs_in_album) > 0)
                        {
                            echo '<tr><th scope="row">'.$id.'</th>
                                <td>'.$latest_albums[$i]['name'].'</td>
                                <td><ul class="list-group">';
                            for($j=0; $j < sizeof($songs_in_album); $j++)
                                echo '<li class="new-releases">'.$songs_in_album[$j].'</li>';
                            echo '</ul></td>
                                <td>'.$latest_albums[$i]['duration'].'</td>
                                <td>'.$latest_albums[$i]['date_created'].'</td></tr>';
                            $id++;
                        }
                        else
                        {
                            echo '<tr><th scope="row">'.$id.'</th>
                                    <td>'.$latest_albums[$i]['name'].'</td>
                                    <td></td>
                                    <td>'.$latest_albums[$i]['duration'].'</td>
                                    <td>'.$latest_albums[$i]['date_created'].'</td></tr>';
                                $id++;
                        }
                    }
                }
                else
                    echo '<h6>No new releases</h6>';
            ?>
            </table>
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