<?php
  include '../../APIs/control/Dependencies.php';
  
  hassnerInit();
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Rateify - Login</title>
    <meta name="description" content="Hassner is a platform to raise music for artists"/>

  <!--Inter UI font-->
    <link href="https://rsms.me/inter/inter-ui.css" rel="stylesheet">

    <!-- Bootstrap CSS / Color Scheme -->
    <link rel="stylesheet" href="../css/default.css" id="theme-color">
  </head>
  <body>

<!--navigation-->
    <section class="smart-scroll">
      <div class="container-fluid">
        <nav class="navbar navbar-expand-md navbar-dark bg-orange">
          <a id = "href-hover" style = "background: transparent;" class="navbar-brand" href="index.php" onclick='window.location.reload();'>HASSNER</a>
          
          <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse"
          data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
          aria-label="Toggle navigation">
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
              <h1> Sign in to Hassner</h1>
            </div>

        <!-- hyperlinks -->
            <div class="col text-center">
              <a href="index.php"> Return to landing page</a>
            </div>

            <div class="col text-center">
              <a href="signup.php"> Need to register for an account?</a>
            </div>

          <!-- signup form -->
            <form action="../../APIs/credentials/LoginBackend.php" method="post">

              <!-- username field -->
              <div class="form-group">
                <h5>Username</h5>
                <input name = "username" type="text" style="border-color: white;" class="form-control" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter username">
              </div>

              <!-- password field -->
              <div class="form-group">
                <h5>Password</h5>
                <input name = "password" type="password" style="border-color: white;" class="form-control" id="exampleInputPassword1" placeholder="Password">
              </div>

              <div class="form-group">
                <p style="color: red;"><?php echo getStatusMessage("Your credentials do not match", "Account created successfully"); ?></p>
              </div>


              <!-- login button -->
              <div class="col-md-8 col-12 mx-auto pt-5 text-center">
                <input type = "submit" class="btn btn-primary" role="button" aria-pressed="true" name = "button" value = "Login">
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

    <?php
      $_SESSION['status'] = 0;
    ?>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.7.3/feather.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script src="js/scripts.js"></script>
  </body>
</html>