<?php
  session_start();
  $_SESSION['status'];
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Hassner - Sign Up</title>
    <meta name="description"
          content="Rateify is a music service that allows users to rate songs"/>

    <!--Inter UI font-->
    <link href="https://rsms.me/inter/inter-ui.css" rel="stylesheet">

    <!--vendors styles-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">

    <!-- Bootstrap CSS / Color Scheme -->
    <link rel="stylesheet" href="css/default.css" id="theme-color">
</head>
<body>

<!--navigation-->
<section class="smart-scroll">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-md navbar-dark">
            <a class="navbar-brand heading-black" href="index.php">
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
  if($_SESSION['status'] == "SUCCESS")
    echo "<script>alert('Admin ID sent successfully');</script>";
  if($_SESSION['status'] == 2)
    echo "<script>alert('Failed');</script>";
  $_SESSION['status'] = 0;
?>

<!--signup functionality-->
<section class="py-7 py-md-0 bg-hero" id="login">
    <div class="container">
        <div class="row vh-md-100">
            <div class="col-md-8 col-sm-10 col-12 mx-auto my-auto text-center">
                
              <!-- header -->
              <div class="col text-center">
                <h1> Become an admin</h1>
              </div>

              <div class="col text-center">
                <a href="login.php"> Sign in</a>
              </div>
            

                <!-- signup form -->
                <form action = "../APIs/AdminRegisterConnection.php" method = "post">

                    <!-- username field -->
                    <div class="form-group">
                    <h5>Username</h5>
                      <input type="text" name = "admin_username" class="form-control" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter username">
                    </div>

                    <div class="form-group">
                    <h5>Password</h5>
                      <input type="password" name = "admin_password" class="form-control" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter password">
                    </div>

                    <div class="form-group">
                    <h5>Admin ID</h5>
                      <input type="text" name = "admin_id" class="form-control" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter admin id">
                    </div>

                    <!-- register button -->
                    <!-- TODO: register button functionality-->
                    <div class="col-md-8 col-12 mx-auto pt-5 text-center">
                        <input type = "submit" class="btn btn-primary" role="button" aria-pressed="true" name = "button" value = "Register!" onclick='window.location.reload();'>
                        
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