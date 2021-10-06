<?php
  include '../../backend/control/Dependencies.php';
  include '../../backend/shared/CampaignHelpers.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=divice-width, initial-scale=1.0">
        <title>Create Campaign</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/checkout.css" type="text/css">
        <link rel="stylesheet" href="../css/default.css" type="text/css">
        <link rel="stylesheet" href="../css/menu.css" type="text/css">
    </head>

    <body class="bg-dark">
        <!--navigation-->
        <section class="smart-scroll">
            <div class="container-xxl">
                <nav class="navbar navbar-expand-md navbar-dark bg-orange">
                    <a id = "href-hover" class="navbar-brand heading-black" href="Artist.php">
                        HASSNER
                    </a>

                    <!-- This line here is to prevent a bug where the account balance would move to the left -->
                    <div class="col text-right"></div>
                </nav>
            </div>
        </section>
        <section id="login" class="py-5";>
            <div class="container">
                <div class="col-12 mx-auto my-auto text-center">
                    <div class="col-md-8 col-12 mx-auto pt-5 text-center py-4">
                        <?php
                            if($_SESSION['logging_mode'] == LogModes::CAMPAIGN)
                            {
                                if($_SESSION['status'] == StatusCodes::CampaignEmpty)
                                {
                                    $_SESSION['status'] = StatusCodes::ErrGeneric;
                                    getStatusMessage("Please fill out all fields", "");
                                }
                                else if($_SESSION['status'] == StatusCodes::CampaignTimeErr)
                                {
                                    $_SESSION['status'] = StatusCodes::ErrGeneric;
                                    getStatusMessage("Expiration date has to be in the future", "");
                                }
                            }
                        ?>
                        <h2>Offer</h2>
                        <form action="../../backend/artist/CreateCampaignBackend.php" method="post">
                            <div class="py-4">
                                <h4>Experience</h4>
                                <input type="radio" id="tickets" name="offer" value="tickets">
                                <label for="tickets">Tickets</label><br>
                                <input type="radio" id="backstage" name="offer" value="backstage">
                                <label for="backstage">Backstage</label><br>
                                <input type="radio" id="merchandise" name="offer" value="merchandise">
                                <label for="merchandise">Merchandise</label><br>
                                <input type="radio" id="instrument" name="offer" value="instrument">
                                <label for="instrument">instrument</label><br>  
                                <input type="radio" id="other" name="offer" value="other">
                                <label for="other"><input type="text" name = "other_offering" class="form-control" placeholder="Other"></label><br><br>
                            </div>

                            <div class="py-4">
                                <h4>Expires</h4>
                                <input type="datetime-local" name="campaign_duration">
                            </div>

                            <div class="py-4">
                                <h4>Minimum Ethos</h4>
                                <input type="text" name = "minimum_ethos" class="form-control" placeholder="Ethos required to enter">
                            </div>

                            <div class="py-4">
                                <input type="radio" id="raffle" name="raffle_or_benchmark" value="raffle">
                                <label for="raffle">Raffle</label><br>
                                <input type="radio" id="benchmark" name="raffle_or_benchmark" value="benchmark">
                                <label for="benchmark">Benchmark</label><br>
                            </div>
                            <input type = "submit" class="btn btn-primary" role="button" aria-pressed="true" value = "Commence Campaign">  
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.7.3/feather.min.js"></script>
    </body>
</html>