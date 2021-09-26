<?php
  include '../../backend/control/Dependencies.php';
  include '../../backend/artist/ArtistHelpers.php';
  include '../../backend/shared/MarketplaceHelpers.php';

  $_SESSION['selected_artist'] = $_SESSION['username'];
  $account_info = getArtistAccount($_SESSION['username'], "artist");
  $_SESSION['user_balance'] = $account_info['balance'];
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

              <!-- This line here is to prevent a bug where the account balance would move to the left -->
              <div class="col text-right"></div>

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
                      if($_SESSION['display'] == "ETHOS" || $_SESSION['display'] == 0)
                      {
                          echo '<li class="list-group-item-no-hover" style="border-color: white; border-bottom: 2px solid white; border-top: 2px #11171a; border-right-color: #11171a;">
                              <form action="../../backend/control/MenuDisplayArtistBackend.php" method="post">';
                          echo '<input name="display_type" type="submit" id="menu-style" style="border:1px white; background-color: transparent; color: #ff9100;" value="Ethos ->"';
                          echo '</form>';
                          echo '</li>';
                      }
                      else
                      {
                          echo '<li class="list-group-item-no-hover">
                              <form action="../../backend/control/MenuDisplayArtistBackend.php" method="post">';
                          echo '<input name="display_type" type="submit" id="abc-no-underline" style="font-weight: bold; border:1px transparent; background-color: transparent;" value="Ethos">';
                          echo '</form>';
                          echo '</li>';
                      }

                      if($_SESSION['display'] == "CAMPAIGN")
                      {
                          echo '<li class="list-group-item-no-hover" style="border-color: white; border-bottom: 2px solid white; border-top: 2px solid white; border-right-color: #11171a;">
                              <form action="../../backend/control/MenuDisplayArtistBackend.php" method="post">';
                          echo '<input name="display_type" type="submit" id="menu-style" style="border:1px orange; background-color: transparent; color: #ff9100;" value="Campaign ->">';
                          echo '</form>';
                          echo '</li>';
                      }
                      else
                      {
                          echo '<li class="list-group-item-no-hover">
                              <form action="../../backend/control/MenuDisplayArtistBackend.php" method="post">';
                          echo '<input name="display_type" type="submit" id="abc-no-underline" style="font-weight: bold; border:1px orange; background-color: transparent;" value="Campaign">';
                          echo '</form>';
                          echo '</li>';
                      }

                      if($_SESSION['display'] == "SILIQAS")
                      {
                          echo '<li class="list-group-item-no-hover" style="border-color: white; border-bottom: 2px solid white; border-top: 2px solid white; border-right-color: #11171a;">
                              <form action="../../backend/control/MenuDisplayArtistBackend.php" method="post">';
                          echo '<input name="display_type" type="submit" id="menu-style" style="border:1px orange; background-color: transparent; color: #ff9100;" value="Siliqas ->">';
                          echo '</form>';
                          echo '</li>';
                      }

                      else
                      {
                          echo '<li class="list-group-item-no-hover">
                              <form action="../../backend/control/MenuDisplayArtistBackend.php" method="post">';
                          echo '<input name="display_type" type="submit" id="abc-no-underline" style="font-weight: bold; border:1px orange; background-color: transparent;" value="Siliqas">';
                          echo '</form>';
                          echo '</li>';
                      }

                      if($_SESSION['display'] == "ARTISTS")
                      {
                          echo '<li class="list-group-item-no-hover" style="border-color: white; border-bottom: 2px solid white; border-top: 2px solid white; border-right-color: #11171a;">
                              <form action="../../backend/control/MenuDisplayArtistBackend.php" method="post">';
                          echo '<input name="display_type" type="submit" id="menu-style" style="border:1px orange; background-color: transparent; color: #ff9100;" value="Artists ->">';
                          echo '</form>';
                          echo '</li>';
                      }
                      else
                      {
                          echo '<li class="list-group-item-no-hover">
                              <form action="../../backend/control/MenuDisplayArtistBackend.php" method="post">';
                          echo '<input name="display_type" type="submit" id="abc-no-underline" style="font-weight: bold; border:1px orange; background-color: transparent;" value="Artists">';
                          echo '</form>';
                          echo '</li>';
                      }

                      if($_SESSION['display'] == "ACCOUNT")
                      {
                          echo '<li class="list-group-item-no-hover" style="border-color: white; border-bottom: 2px solid white; border-top: 2px solid white; border-right-color: #11171a;">
                              <form action="../../backend/control/MenuDisplayArtistBackend.php" method="post">';
                          echo '<input name="display_type" type="submit" id="menu-style" style="border:1px orange; background-color: transparent; color: #ff9100;" value="Account ->">';
                          echo '</form>';
                          echo '</li>';
                      }

                      else
                      {
                          echo '<li class="list-group-item-no-hover">
                              <form action="../../backend/control/MenuDisplayArtistBackend.php" method="post">';
                          echo '<input name="display_type" type="submit" id="abc-no-underline" style="font-weight: bold; border:1px orange; background-color: transparent;" value="Account">';
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
                        if($_SESSION['display'] == "CAMPAIGN")
                        {  
                            echo '
                                    <div class="py-4 col-12 mx-auto my-auto text-center">
                                        <a class="btn btn-primary" href="CreateCampaign.php">Start a new campaign?</a>
                                    </div>
                                    <h4>Your active campaigns</h4>
                            ';
                        }

                        //Artist's portfolio
                        else if($_SESSION['display'] == "ETHOS" || $_SESSION['display'] == 0)
                        {
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
                                echo '
                                        <h6>Price Per Share (q̶): '.$account_info['price_per_share'].'</h6>
                                        <form action="../../backend/shared/GlobalVarsSwitchBackend.php" method="post">
                                            <h6>Volumn: '.$account_info['Share_Distributed'].' <input name="display_type" type="submit" id="menu-style" style="border:1px white; background-color: transparent; color: #ff9100;" value="+">
                                        </form>
                                ';
                                if($_SESSION['share_distribute'] != 0)
                                {
                                    echo '
                                        <form action="../../backend/artist/UpdateShareDistributedBackend.php" method="post">
                                            <p>How many shares would you like to inject?</p>
                                            <input type="text" name = "share_distributing" class="form-control form-control-sm" style="border-color: white;" placeholder="Enter amount">
                                            <p>Comments</p>
                                            <input type="text" name = "inject_comment" class="form-control form-control-sm" style="border-color: white;" placeholder="Enter comment">
                                            <div class="col-md-8 col-12 mx-auto pt-5 text-center">
                                            <input type = "submit" class="btn btn-primary" role="button" aria-pressed="true" name = "button" value = "Save">  
                                            </div>
                                        </form>
                                    ';
                                }
                                echo '
                                        <h6>Current Shareholders: '.$shareholder_list->num_rows.'</h6>
                                        <h6>Market cap (q̶): '.$market_cap.'</h6>
                                        <h6>Day High (q̶): '.$high.'</h6>
                                        <h6>Day Low (q̶): '.$low.'</h6>
                                        <br>
                                        <h2>Buy Back Shares</h2>
                                ';

                                askedPriceInit();
                                echo '
                                            </tbody>
                                        </table>
                                ';

                                echo '<h3>Inject history</h3>';

                                injectionHistoryInit($_SESSION['username']);
                            }
                        }

                        //brings to Artist personal account page, where they can input their metrics, which are shown
                        //when users search for them and also on their portfolio tab
                        else if($_SESSION['display'] == "ACCOUNT")
                        {
                            echo '
                                <section id="login">
                                <div class="container">
                                    <div">
                                        <div class="col-12 mx-auto my-auto text-center">
                                            <h3 style="color: orange;padding-top:150px;">Verify your password to access personal page</h3>
                                            <form action="../../backend/artist/PersonalPageBackend.php" method="post">
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

                        //Sell siliqas to USD/CAD/EUR
                        else if($_SESSION['display'] == "SILIQAS")
                        {
                            siliqasInit();
                        }

                        else if($_SESSION['display'] == "ARTISTS")
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