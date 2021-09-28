<?php
  include '../../backend/control/Dependencies.php';
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
        <div class="py-4 col-12 mx-auto my-auto text-center">
            <h4>What are you offering?</h4>
        </div>
        <section id="login" class="py-5";>
            <div class="container">
                <div class="col-12 mx-auto my-auto text-center">
                        <div class="navbar-light bg-dark" class="col-md-8 col-12 mx-auto pt-5 text-center">
                            <form action="../../backend/artist/CampaignOptionsSwitcher.php" method="post">
                                <input name = "campaign_options" type = "submit" class="btn btn-secondary" role="button" aria-pressed="true" name = "button" value = "Experience"> 
                                <input name = "campaign_options" type = "submit" class="btn btn-secondary" role="button" aria-pressed="true" name = "button" value = "Object">
                            </form>
                        </div>
                        <?php
                            if($_SESSION['campaign_option'] == "EXPERIENCE")
                            {
                                echo '
                                    <div class="col-md-8 col-12 mx-auto pt-5 text-center py-4">
                                        <h6>Offer an Experience</h6>
                                        <form action="../../backend/artist/CampaignExperienceSwitcher.php" method="post">
                                            <div>
                                                <input type="radio" name="experience_options" value="tickets" checked>
                                                <label for="huey">Tickets</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="experience_options" value="backstage">
                                                <label for="dewey">Backstage</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="experience_options" value="other">
                                                <label for="dewey"><input type="text" name = "custom" class="form-control" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Other"></label>
                                            </div>
                                            <div>
                                                <input type = "submit" class="btn btn-primary" role="button" aria-pressed="true" name = "button" value = "Continue">
                                            </div>
                                        </form>
                                    </div>
                                ';

                                for($i = 0; $i < sizeof($_SESSION['campaign_data']); $i++)
                                {
                                    echo $_SESSION['campaign_data'][$i];
                                    echo "<br>";
                                }
    
                                // $_SESSION['campaign_option'] = 0;
                            }
                            else if($_SESSION['campaign_option'] = "OBJECT")
                            {
                                array_push($_SESSION['campaign_data'], "object");
                                for($i = 0; $i < sizeof($_SESSION['campaign_data']); $i++)
                                {
                                    echo $_SESSION['campaign_data'][$i];
                                    echo "<br>";
                                }
                                $_SESSION['campaign_option'] = 0;
                            }
                        ?>
                </div>
            </div>
        </section>
        <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.7.3/feather.min.js"></script>
    </body>
</html>