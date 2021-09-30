<?php
include '../../backend/control/Dependencies.php';
include '../../backend/constants/StatusCodes.php';

hassnerInit();
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Hassner - Sign Up</title>
  <meta name="description" content="Hassner is a platform to raise music for artists" />

  <!--Inter UI font-->
  <link href="https://rsms.me/inter/inter-ui.css" rel="stylesheet">

  <!-- Bootstrap CSS / Color Scheme -->
  <link rel="stylesheet" href="../css/default.css" id="theme-color">
  <link rel="stylesheet" href="../css/menu.css" id="theme-color">
</head>

<body>

  <section class="smart-scroll">
    <div class="container-fluid">
      <nav class="navbar navbar-expand-md navbar-dark bg-darkcyan">

        <a class="navbar-brand" href="index.php" onclick='window.location.reload();'>
          HASSNER
        </a>

        <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span data-feather="grid"></span>
        </button>

      </nav>
    </div>
  </section>

  <!--signup functionality-->
  <section class="py-7 py-md-0 bg-dark" id="login">
    <div class="container">
      <div class="row vh-md-100">
        <div class="col-md-8 col-sm-10 col-12 mx-auto my-auto text-center">

          <!-- header -->
          <div class="col text-center">
            <h1 class="h1-blue"> Sign up for a Hassner&nbspaccount.</h1>
          </div>

          <!-- hyperlinks -->
          <div class="col text-center">
            <a href="index.php"> Return to front page</a>
          </div>

          <div class="col text-center">
            <a href="login.php"> Already have an account?</a>
          </div>

          <div class="col text-center">
            <a href="AdminSignUp.php"> Want to become an admin?</a>
          </div>

          <!-- signup form -->
          <form action="../../backend/credentials/SignUpBackend.php" method="post">

            <!-- username field -->

            <div class="form-group">
              <h5>Email Address</h5>
              <input type="text" name="email" class="form-control" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter email address">
            </div>

            <div class="form-group">
              <h5>Username</h5>
              <input type="text" name="username" class="form-control" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter username">
            </div>

            <!-- password field -->
            <div class="form-group">
              <h5>Password</h5>
              <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
            </div>

            <?php
            $status = "";
            if ($_SESSION['status'] == StatusCodes::ErrUsername) {
              $_SESSION['status'] = StatusCodes::ErrGeneric;
              $status = getStatusMessage("Username already taken", "");
            } else if ($_SESSION['status'] == StatusCodes::ErrServer) {
              $_SESSION['status'] = StatusCodes::ErrGeneric;
              $status = getStatusMessage("Server error, cannot create account", "");
            } else if ($_SESSION['status'] == StatusCodes::ErrEmpty) {
              $_SESSION['status'] = StatusCodes::ErrGeneric;
              $status = getStatusMessage("Please fill out all fields", "");
            } else if ($_SESSION['status'] == StatusCodes::ErrEmailDuplicate) {
              $_SESSION['status'] = StatusCodes::ErrGeneric;
              $status = getStatusMessage("Email already taken", "");
            }
            ?>

            <!-- account type -->
            <h6 class="h6-tan">Account Type</h6>
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
              <input type="submit" class="btn btn-primary" role="button" aria-pressed="true" name="button" value="Register" onclick='window.location.reload();'>

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