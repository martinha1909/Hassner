<?php
    define("HX_INCLUDE_DIR", dirname(dirname(dirname(__FILE__))));

    session_start();
    $_SESSION['dependencies'] = "SCRIPTS";

    include 'include/DatabaseHelpers.php';
    include HX_INCLUDE_DIR.'/backend/control/connection.php';
    include HX_INCLUDE_DIR.'/backend/object/Investor.php';
    include HX_INCLUDE_DIR.'/backend/control/Queries.php';
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Testing Phase Leaderboard</title>

  <link rel="stylesheet" href="../../frontend/css/default.css" id="theme-color">
  <link rel="stylesheet" href="../../frontend/css/menu.css" id="theme-color">
</head>

<body>
    <div class="text-center">
        <h3 class="h3-blue">Top Net worth Accounts</h3>
        <?php 
            printTopAccounts($_SESSION['leaderboard_full']); 
            if($_SESSION['leaderboard_full'] == 0)
            {
                echo '<a class="py-6" href = "operation/LeaderboardSwitcher.php">View full leaderboard ↓</a></br>';
            }
            else
            {
                echo '<a class="py-6" href = "operation/LeaderboardSwitcher.php">View top 3 only ↑</a></br>';
            }
        ?>
        
        
    </div>
    <a class="py-6" href = "scriptsMain.php">← Return</a>

    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script>window.jQuery || document.write('<script src="../js/lib/jquery-3.6.0.js"><\/script>');</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.7.3/feather.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
</body>

</html>