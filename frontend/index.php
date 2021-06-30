<?php
  session_start();
  $_SESSION['coins'] = 0;
  $_SESSION['notify'] =  0;
  $_SESSION['display'] = 0;
  $_SESSION['sort_type'] = 0;
  $_SESSION['cad'] = 0;
  $_SESSION['edit'] = 0;
  $_SESSION['currency'] = 0;
  $_SESSION['btn_show'] = 0;
  $_SESSION['saved'] = 0;
  $_SESSION['top_rating'] = 0;
  $_SESSION['buy_sell'] = 0;
  $_SESSION['add'] = 0;
  $_SESSION['add_share'] = 0;
?>

<!doctype html>
<html lang="en">
<head>
 
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Hassner</title>
    <meta name="description"
          content="Rateify is a music service that allows users to rate songs"/>

    <!--Inter UI font-->
    <link href="https://rsms.me/inter/inter-ui.css" rel="stylesheet">

    <!-- Bootstrap CSS / Color Scheme -->
    <link rel="stylesheet" href="css/default.css" id="theme-color">
    <link rel="phpFunctions" href="../APIs/logic.php">
</head>
<body>

<!--navigation-->
<section class="smart-scroll">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark">
            <a id = "href-hover-orange" style = "background: transparent;" class="navbar-brand" href="#" onclick='window.location.reload();'>
                HASSNER
            </a>
            <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse"
                    data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span data-feather="grid"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav ml-auto">
                    <menu class="nav-item">
                        <a class="nav-link page-scroll" href="#login">Log In</a>
                    </menu>
                    <menu class="nav-item">
                        <a class="nav-link page-scroll" href="#signup">Sign Up</a>
                    </menu>
                </ul>
            </div>
        </nav>
    </div>
</section>

<!--hero header-->
<section class="py-md-0 bg-dark" id="home">
    <div class="container">
        <div class="row vh-md-100">
            <div class="col-md-8 col-sm-10 col-12 mx-auto my-auto text-center">
                <h1 style="color:#ff9100;" class="heading-black">Investing&nbspis music&nbspto&nbspour&nbspears</h1>
                <h5 class="lead">Hassner is creating new opportunities for both listeners and artists. Sign&nbspup&nbspfor&nbspfree.</h5>
                <a class= "nav-link page-scroll" href="#signup" class="btn btn-primary d-inline-flex flex-row align-items-center" role="button" aria-pressed="true">
                    <!--It loss the green background, but it scrolls to the bottom of the page now (or we can make it go to the signup page automatically)-->
                    Get started now
                </a>
            </div>
        </div>
    </div>
</section>

<!-- features section -->
<section class="pt-6 pb-7 bg-orange" id="features">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mx-auto text-center">
                <h2 class="heading-black">Hassner is the new way to invest.</h2>
                <p class="text-dark lead">Sign up as an Investor or an Artist!</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="row feature-boxes">
                    <div class="col-md-6 box">
                        <div class="icon-box box-dark">
                            <div class="icon-box-inner">
                                <span data-feather="activity" width="35" height="35"></span>
                            </div>
                        </div>
                        <h5>Investors</h5>
                        <p class="text-dark">Support your favourite artists and make returns!</p>
                    </div>
                    <div class="col-md-6 box">
                        <div class="icon-box box-dark">
                            <div class="icon-box-inner">
                                <span data-feather="bar-chart-2" width="35" height="35"></span>
                            </div>
                        </div>
                        <h5>Artists</h5>
                        <p class="text-dark">See your growth and funding!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!--signup section-->
<section class="py-5 bg-dark top-right bottom-left" id="signup">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-12 mx-auto pt-5 text-center">
                <h3 style = "color: white;">Create a Hassner account</h3>
                <form action="signup.php">
                    <input type="submit" role="button" value="Sign up!" class = "btn btn-primary" aria-pressed="true">
                </form>
            </div>
        </div>
    </div>
</section>

<!--login section-->
<section class="py-5 bg-dark top-right bottom-left" id="login">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-12 mx-auto text-center py-5">
                <h3 style = "color: white;">Sign in to your account</h3>
                <form action="login.php">
                    <input class ="btn btn-primary" role="button" type = "submit" aria-pressed="true" value="Log in">
                </form>
            </div>
        </div>
    </div>
</section>

<!--scroll to top-->
<div class="scroll-top">
    <i class="fa fa-angle-up" aria-hidden="true" data-feather="arrow-up-circle" width="30" height="40"></i>
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