<?php
  include '../../APIs/control/Dependencies.php';
  include '../../APIs/artist/ArtistBackend.php';
  include '../../APIs/shared/MarketplaceBackend.php';

  $_SESSION['selected_artist'] = $_SESSION['username'];
  $_SESSION['lower_bound'] = 0.5;
  $_SESSION['status'] = 0;
  $account_info = getArtistAccount($_SESSION['username'], "artist");
?> 

<!doctype html>
<html lang="en">
  <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Hassner - Artist</title>
      <meta name="description"
            content="Rateify is a music service that allows users to rate songs"/>

      <!--Inter UI font-->
      <link href="https://rsms.me/inter/inter-ui.css" rel="stylesheet">

      <!-- Bootstrap CSS / Color Scheme -->
      <link rel="stylesheet" href="../css/default.css" id="theme-color">
      <link rel="stylesheet" href="../css/menu.css" id="theme-color">
      <link rel="stylesheet" href="../css/date_picker.css" type="text/css">
      <link rel="stylesheet" href="../css/slidebar.css" type="text/css">
  </head>
  <body class="bg-dark">

  <!--navigation-->
  <section class="smart-scroll">
      <div class="container-xxl">
          <nav class="navbar navbar-expand-md navbar-dark bg-orange">
              <a id = "href-hover" class="navbar-brand heading-black" href="#">
                  HASSNER
              </a>

              <div class="col text-right">
                  <a href="../APIs/IncreaseSharesDistributed.php" onclick='window.location.reload();'>+</a>
              </div>
              <div class="col text-right">
                  <a href="../APIs/DecreaseSharesDistributed.php" onclick='window.location.reload();'>-</a>
              </div>

              <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse"
                      data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                      aria-label="Toggle navigation">
                  <span data-feather="grid"></span>
              </button>

              <div style="color: #11171a; font-weight: bold; background-color:white; border-left: 4px solid #11171a; border-right: 10px solid white;">
                <?php
                  echo ' &nbsp;(q̶): ';
                  echo round($account_info['balance'], 2);
                  $unbought = $account_info['Share_Distributed'] - $account_info['Shares'];
                  echo '<br> &nbsp;Available Shares: ';
                  echo $unbought;
                ?>
              </div>
          </nav>
      </div>
  </section>

  <!-- listener functionality -->
  <section id="login">
      <div class="container-fluid">
          <div class="row">
              <ul class="list-group bg-dark">
                  <?php
                      if($_SESSION['display'] == 2 || $_SESSION['display'] == 0)
                      {
                          echo '<li class="list-group-item-no-hover" style="border-color: white; border-bottom: 2px solid white; border-top: 2px #11171a; border-right-color: #11171a;">
                              <form action="../../APIs/control/MenuDisplayArtistBackend.php" method="post">';
                          echo '<input name="display_type" type="submit" id="menu-style" style="border:1px white; background-color: transparent; color: #ff9100;" value="My Portfolio ->"';
                          echo '</form>';
                          echo '</li>';
                      }
                      else
                      {
                          echo '<li class="list-group-item-no-hover">
                              <form action="../../APIs/control/MenuDisplayArtistBackend.php" method="post">';
                          echo '<input name="display_type" type="submit" id="abc-no-underline" style="font-weight: bold; border:1px transparent; background-color: transparent;" value="My Portfolio">';
                          echo '</form>';
                          echo '</li>';
                      }
                      if($_SESSION['display'] == 1)
                      {
                          echo '<li class="list-group-item-no-hover" style="border-color: white; border-bottom: 2px solid white; border-top: 2px solid white; border-right-color: #11171a;">
                              <form action="../../APIs/control/MenuDisplayArtistBackend.php" method="post">';
                          echo '<input name="display_type" type="submit" id="menu-style" style="border:1px orange; background-color: transparent; color: #ff9100;" value="Your Campaign ->">';
                          echo '</form>';
                          echo '</li>';
                      }
                      else
                      {
                          echo '<li class="list-group-item-no-hover">
                              <form action="../../APIs/control/MenuDisplayArtistBackend.php" method="post">';
                          echo '<input name="display_type" type="submit" id="abc-no-underline" style="font-weight: bold; border:1px orange; background-color: transparent;" value="Your Campaign">';
                          echo '</form>';
                          echo '</li>';
                      }
                      if($_SESSION['display'] == 3)
                      {
                          echo '<li class="list-group-item-no-hover" style="border-color: white; border-bottom: 2px solid white; border-top: 2px solid white; border-right-color: #11171a;">
                              <form action="../../APIs/control/MenuDisplayArtistBackend.php" method="post">';
                          echo '<input name="display_type" type="submit" id="menu-style" style="border:1px orange; background-color: transparent; color: #ff9100;" value="Account ->">';
                          echo '</form>';
                          echo '</li>';
                      }
                      else
                      {
                          echo '<li class="list-group-item-no-hover">
                              <form action="../../APIs/control/MenuDisplayArtistBackend.php" method="post">';
                          echo '<input name="display_type" type="submit" id="abc-no-underline" style="font-weight: bold; border:1px orange; background-color: transparent;" value="Account">';
                          echo '</form>';
                          echo '</li>';
                      }
                      if($_SESSION['display'] == 4)
                      {
                          echo '<li class="list-group-item-no-hover" style="border-color: white; border-bottom: 2px solid white; border-top: 2px solid white; border-right-color: #11171a;">
                              <form action="../../APIs/control/MenuDisplayArtistBackend.php" method="post">';
                          echo '<input name="display_type" type="submit" id="menu-style" style="border:1px orange; background-color: transparent; color: #ff9100;" value="Sell Siliqas ->">';
                          echo '</form>';
                          echo '</li>';
                      }
                      else
                      {
                          echo '<li class="list-group-item-no-hover">
                              <form action="../../APIs/control/MenuDisplayArtistBackend.php" method="post">';
                          echo '<input name="display_type" type="submit" id="abc-no-underline" style="font-weight: bold; border:1px orange; background-color: transparent;" value="Sell Siliqas">';
                          echo '</form>';
                          echo '</li>';
                      }
                      if($_SESSION['display'] == 5)
                      {
                          echo '<li class="list-group-item-no-hover" style="border-color: white; border-bottom: 2px solid white; border-top: 2px solid white; border-right-color: #11171a;">
                              <form action="../../APIs/control/MenuDisplayArtistBackend.php" method="post">';
                          echo '<input name="display_type" type="submit" id="menu-style" style="border:1px orange; background-color: transparent; color: #ff9100;" value="Settings ->">';
                          echo '</form>';
                          echo '</li>';
                      }
                      else
                      {
                          echo '<li class="list-group-item-no-hover">
                              <form action="../../APIs/control/MenuDisplayArtistBackend.php" method="post">';
                          echo '<input name="display_type" type="submit" id="abc-no-underline" style="font-weight: bold; border:1px orange; background-color: transparent;" value="Settings">';
                          echo '</form>';
                          echo '</li>';
                      }
                      echo '<li class="list-group-item-no-hover">';
                          echo '<li class="list-group-item-no-hover">';
                          echo '</li>';
                          echo '<li class="list-group-item-no-hover">';
                          echo '</li>';
                          echo '<li class="list-group-item-no-hover" style="padding-top: 75px;">';
                          echo '</li>';
                          echo '<li class="list-group-item-no-hover" style="border-bottom: 2px solid white;">';
                          echo    '<a class="dropdown-item" id="dashboard-hover" style="background-color: transparent;" href="../credentials/login.php">Log out</a>';
                          echo '</li>';
                          
                  ?>
                  </ul>

                  <div class="col">
                    <?php
                        //Artist campaigns, including benchmark, raffle, and give aways.
                        if($_SESSION['display'] == 1)
                        {  
                        //nothing to do now as it's not part of vital functionality
                        }

                        //Artist's portfolio
                        else if($_SESSION['display'] == 2 || $_SESSION['display'] == 0)
                        {
                            $_SESSION['add'] = 0;
                            if($account_info['Share_Distributed'] == 0)
                            {
                                echo '<h3>Get started by distributing share in the account tab</h3>';
                            }
                            else
                            {
                                $shareholder_list = fetchCurrentShareholders($_SESSION['username']);
                                $market_cap = calculateMarketCap($_SESSION['username']);
                                $high = getHighestOrLowestPPS($_SESSION['username'], "MAX");
                                $low = getHighestOrLowestPPS($_SESSION['username'], "MIN");
                                $lower_bound = getLowerBound($_SESSION['username']);
                                echo '<h6>Price Per Share: '.$account_info['price_per_share'].'</h6>';
                                echo '
                                    <form action="../../APIs/control/MenuDisplayArtistBackend.php" method="post">
                                        <h6>Volumn: '.$account_info['Share_Distributed'].' <input name="display_type" type="submit" id="menu-style" style="border:1px white; background-color: transparent; color: #ff9100;" value="+">
                                    </form>
                                    ';
                                echo '<h6>Current Shareholders: '.$shareholder_list->num_rows.'</h6>';
                                echo '<h6>Market cap (q̶): '.$market_cap.'</h6>';
                                echo '<h6>Day High (q̶): '.$high.'</h6>';
                                echo '<h6>Day Low (q̶): '.$low.'</h6>';
                                echo '<h6>Current lower bound (q̶): '.$lower_bound.'</h6>';
                                if($_SESSION['add_share'] == 1)
                                {
                                $max = $account_info['Share_Distributed'];
                                echo'  
                                    <h1>Distribute more shares</h1>
                                    <p>Drag the slider to display the current value.</p>
                                    
                                    <div class="slidecontainer">
                                        <form action="../APIs/artist/IncreaseSharesDistributed.php" method ="post">
                                        <input name="share_added" type="range" min="0" max='.$max.' value="0" class="slider" id="myRange">
                                        <input type="submit" class="btn btn-primary py-2" value="Distribute">
                                        </form>
                                        <h6>Value: <span id="demo"></span></h6>
                                    </div>
                                    <script>
                                        var slider = document.getElementById("myRange");
                                        var output = document.getElementById("demo");
                                        output.innerHTML = slider.value;
                                        
                                        slider.oninput = function() {
                                        output.innerHTML = this.value;
                                        }
                                    </script>
                                    ';
                                }
                                echo '
                                        <br>
                                        <h2>Buy Back Shares</h2>
                                ';

                                askedPriceInit();
                                echo '
                                            </tbody>
                                        </table>
                                ';
                            }
                        }

                        //brings to Artist personal account page, where they can input their metrics, which are shown
                        //when users search for them and also on their portfolio tab
                        else if($_SESSION['display'] == 3)
                        {
                            echo '
                                <section id="login">
                                <div class="container">
                                    <div">
                                        <div class="col-12 mx-auto my-auto text-center">
                                            <h3 style="color: orange;padding-top:150px;">Verify your password to access personal page</h3>
                                            <form action="../../APIs/artist/PersonalPageBackend.php" method="post">
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

                        //Sell siliqas to USD/CAD/EURO
                        else if($_SESSION['display'] == 4)
                        {
                            $balance = getUserBalance($_SESSION['username']);

                            sellSiliqasInit($balance);
                        }

                        else if($_SESSION['display'] == 5)
                        {
                        
                        }
                    ?>
                </div>
                <!-- header -->


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
  <script>
    var slider = document.getElementById("myRange");
    var output = document.getElementById("demo");
    output.innerHTML = slider.value;

    slider.oninput = function() {
        output.innerHTML = this.value;
    }
  </script>
  <script src="js/scripts.js"></script>
  </body>
</html>