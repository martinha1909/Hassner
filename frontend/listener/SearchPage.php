<?php
    session_start();
    include '../../APIs/control/Dependencies.php';
    include '../../APIs/listener/SearchArtistBackend.php';
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
                
                    <a id = "href-hover" class="navbar-brand heading-black" href="../../frontend/listener/listener.php" onclick=redirectToListener();>
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
                    <ul class="list-group col">
                        <?php                    
                            if($_SESSION['found'] == 0)
                            {
                                echo '<h3> There are no artists to display </h3>';
                            }
                            else
                            {
                                echo '
                                    <table class="table">
                                        <thead class="thead-orange">
                                            <tr>
                                                <th scope="col" class="bg-dark" id="href-hover";"><input type = "submit" style="border:1px transparent; background-color: transparent; color: white; font-weight: bold;" aria-pressed="true" value = "#"></th>
                                                <th scope="col" class="bg-dark" id="href-hover";"><input type = "submit" style="border:1px transparent; background-color: transparent; color: white; font-weight: bold;" aria-pressed="true" value = "Artist"></th>
                                                <th scope="col" class="bg-dark" id="href-hover";"><input type = "submit" style="border:1px transparent; background-color: transparent; color: white; font-weight: bold;" aria-pressed="true" value = "Price Per Share"></th>
                                                <th scope="col" class="bg-dark" id="href-hover";"><input type = "submit" style="border:1px transparent; background-color: transparent; color: white; font-weight: bold;" aria-pressed="true" value = "Market Cap"></th>
                                                <th scope="col" class="bg-dark" id="href-hover";"><input type = "submit" style="border:1px transparent; background-color: transparent; color: white; font-weight: bold;" aria-pressed="true" value = "Lower Bound"></th>
                                                <th scope="col" class="bg-dark" id="href-hover";"><input type = "submit" style="border:1px transparent; background-color: transparent; color: white; font-weight: bold;" aria-pressed="true" value = "Monthly Shareholders"></th>
                                            </tr>
                                        </thead>
                                    <tbody>
                                ';
                                printSearch($_SESSION['artist_found']);
                            }
                            echo '</form>';     

                            $_SESSION['btn_show'] = 0;

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