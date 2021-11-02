<?php
session_start();
include '../../backend/shared/include/Helper.php';
include '../../backend/shared/include/TimeUtil.php';
include '../../backend/constants/LoggingModes.php';

$_SESSION['logging_mode'] = LogModes::NONE;
$_SESSION['status'] =  0;

hassnerInit();
?>

<!doctype html>
<html lang="en">

<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Hassner</title>
    <meta name="description" content="Hassner is a service to invest in artists." />

    <!--Inter UI font-->
    <link href="https://rsms.me/inter/inter-ui.css" rel="stylesheet">

    <!-- Bootstrap CSS / Color Scheme -->
    <link rel="icon" href="../../frontend/Images/hx_tmp_2.ico" type="image/ico">
    <link rel="stylesheet" href="../css/default.css" id="theme-color">
    <link rel="stylesheet" href="../css/menu.css" id="theme-color">
</head>

<body>

    <!--navigation-->
    <section class="smart-scroll">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-md navbar-dark bg-darkblue">
                <a class="navbar-index" href="#" onclick='window.location.reload();'>
                ❖ HX
                </a>
                <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav ml-auto">
                        <menu class="nav-item">
                            <a class="nav-link-index page-scroll" href="#login">Log In</a>
                        </menu>
                        <menu class="nav-item">
                            <a class="nav-link-index page-scroll" href="#signup">Sign Up</a>
                        </menu>
                    </ul>
                </div>
            </nav>
        </div>
    </section>

    <!--hero header-->
    <section class="py-md-0 bg-dark" id="home">
        <div class="container-video">
            <video autoplay muted loop id="myVideo">
                <source src="../Images/lines.mp4" type="video/mp4">
            </video>
            <div class="video-overlay">
                <div class="text-center">
                    <h1>Investing&nbspis music&nbspto&nbspour&nbspears</h1>
                    <h5 class="lead">Hassner is creating new opportunities for both listeners and artists. Sign&nbspup&nbspfor&nbspfree.</h5>
                    <a class="page-scroll" href="#signup">
                        <!--It loss the green background, but it scrolls to the bottom of the page now (or we can make it go to the signup page automatically)-->
                        Get started now
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- features section -->
    <section class="pt-6 pb-7 bg-darkcyan" id="features">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mx-auto text-center">
                    <h2 class="h2-dark">Hassner is the new way to invest.</h2>
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
                            <h5 class="h5-dark">Investors</h5>
                            <p class="text-dark">Support your favourite artists and make returns!</p>
                        </div>
                        <div class="col-md-6 box">
                            <div class="icon-box box-dark">
                                <div class="icon-box-inner">
                                    <span data-feather="bar-chart-2" width="35" height="35"></span>
                                </div>
                            </div>
                            <h5 class="h5-dark">Artists</h5>
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
                    <h3>Create a Hassner account</h3>
                    <form action="signup.php">
                        <input type="submit" role="button" value="Sign up!" class="btn btn-primary" aria-pressed="true">
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
                    <h3>Sign in to your account</h3>
                    <form action="login.php">
                        <input class="btn btn-primary" role="button" type="submit" aria-pressed="true" value="Log in">
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
    <script src="../js/scripts.js"></script>
</body>

</html>