<?php
    include '../../APIs/control/Dependencies.php';

    $result = getUserBalance($conn, $_SESSION['username']);
    $balance = $result->fetch_assoc();
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
            <div class="wrapper-searchbar">
                <div class="container-searchbar">
                        <label>
                            <span class="screen-reader-text">Search for...</span>
                            <form class="form-inline" action="../../APIs/listener/SearchPageBackend.php" method="post">
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
<section class="py-7 py-md-0 bg-dark" id="login">
    <div class="py-6 container">
        <div class="row">
            <div class="col-12 mx-auto my-auto text-center">
              
              <div class="py-4 col text-center">
              <h2> Search Results for <?php echo $_SESSION['searchedArtistName'];?> </h2>
              </div>

              <!-- hyperlinks -->
              <table class="table">
              <div  style = "top: 15px;" class="col text-center">
                </div>
                    <thead>
                    <tr>
                        <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">#</th>
                        <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">Artist Name</th>
                        <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">No. of albums</th>
                        <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">No. of songs</th>
                        <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">Total no. of plays</th>
                        <th style="background-color: #ff9100; border-color: #ff9100; color: #11171a;" scope="col">Total shares bought</th>
                    </tr>
                    </thead>
                    <tbody>
              <!-- view song form -->
              <form action="../APIs/SongDisplayUser.php" method="post">
                  <?php
                    $conn = connect();
                    $id = 1;
                    $no_of_albums = sizeof($_SESSION['all_albums']);
                    $no_of_songs = sizeof($_SESSION['all_songs']);
                    $total_plays = $_SESSION['total_plays'];
                    echo '<tr><th scope="row">'.$id.'</th><td><input name = "artist_name['.$_SESSION['searchedArtistName'].']" type = "submit" id="abc" style="border:1px transparent; background-color: transparent; color: white; role="button" aria-pressed="true" value = "'.$_SESSION['searchedArtistName'].'"></td><td>'.$no_of_albums.'</td><td>'.$no_of_songs.'</td><td>'.$total_plays.'</td><td>'.$_SESSION['shares'].'</tr>';
                  ?> 
                  </form>
              </tbody>
            </table>
            </div>
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
<script src="js/scripts.js"></script>
</body>
</html>