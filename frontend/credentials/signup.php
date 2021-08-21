<?php
  $_SESSION['index'] = 0;
  include '../../APIs/control/Dependencies.php';
  
  $_SESSION['coins'] = 0;
  $_SESSION['display'] = 0;
  $_SESSION['sort_type'] = 0;
  $_SESSION['cad'] = 0;
  $_SESSION['edit'] = 0;
  $_SESSION['currency'] = 0;
  $_SESSION['btn_show'] = 0;
  $_SESSION['saved'] = 0;
  $_SESSION['buy_sell'] = 0;
  $_SESSION['add'] = 0;
  $_SESSION['add_share'] = 0;
  $_SESSION['buy_asked_price'] = 0;
  $_SESSION['buy_market_price'] = 0;
  $_SESSION['artist_share_remove'] = 0;
  $_SESSION['share_price_remove'] = 0;
  $_SESSION['dependencies'] = 0;
  $_SESSION['current_date'] = getCurrentDate('America/Edmonton');
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Hassner - Sign Up</title>
    <meta name="description" content="Hassner is a platform to raise music for artists"/>

    <!--Inter UI font-->
    <link href="https://rsms.me/inter/inter-ui.css" rel="stylesheet">

    <!-- Bootstrap CSS / Color Scheme -->
    <link rel="stylesheet" href="../css/default.css" id="theme-color">
  </head>
  <body>

    <section class="smart-scroll">
      <div class="container-fluid">
        <nav class="navbar navbar-expand-md navbar-dark bg-orange">

          <a id = "href-hover" style = "background: transparent;" class="navbar-brand" href="index.php" onclick='window.location.reload();'>
          HASSNER
          </a>

          <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse"
          data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
          aria-label="Toggle navigation">
            <span data-feather="grid"></span>
          </button>

        </nav>
      </div>
    </section>

    <?php
    if($_SESSION['status'] == 1)
    echo "<script>alert('Account created sucessfully');</script>";
    if($_SESSION['status'] == 2)
    echo "<script>alert('Failed to create account');</script>";
    $_SESSION['status'] = 0;
    ?>

<!--signup functionality-->
    <section class="py-7 py-md-0 bg-dark" id="login">
      <div class="container">
        <div class="row vh-md-100">
          <div class="col-md-8 col-sm-10 col-12 mx-auto my-auto text-center">

          <!-- header -->
            <div class="col text-center">
              <h1> Sign up for a Hassner&nbspaccount.</h1>
            </div>

            <!-- hyperlinks -->
            <div class="col text-center">
              <a  href="index.php"> Return to front page</a>
            </div>

            <div class="col text-center">
              <a href="login.php"> Already have an account?</a>
            </div>

            <div class="col text-center">
              <a href="AdminSignUp.php"> Want to become an admin?</a>
            </div>

            <!-- signup form -->
            <form action = "../../APIs/credentials/SignUpBackend.php" method = "post">

            <!-- username field -->

              <div class="form-group">
                <h5>Email Address</h5>
                <input type="text" name = "email" class="form-control" style="border-color: white;" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter email address">
              </div>

              <div class="form-group">
                <h5>Username</h5>
                <input type="text" name = "username" class="form-control" style="border-color: white;" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter username">
              </div>

              <!-- password field -->
              <div class="form-group">
                <h5>Password</h5>
                <input type="password" name = "password" class="form-control" style="border-color: white;" id="exampleInputPassword1" placeholder="Password">
              </div>

              <!-- account type -->
              <h6 style = "color: orange;">Account Type</h6>
              <div>
                <input type="radio" name="account_type" value="user" checked>
                <label for="huey">Listener</label>
              </div>

              <div>
                <input type="radio" name="account_type" value="artist">
                <label for="dewey">Artist</label>
              </div>

              <!-- register button -->
              <!-- TODO: register button functionality-->
              <div class="col-md-8 col-12 mx-auto pt-5 text-center">
                <input type = "submit" class="btn btn-primary" role="button" aria-pressed="true" name = "button" value = "Register" onclick='window.location.reload();'>

              </div>
            </form>

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