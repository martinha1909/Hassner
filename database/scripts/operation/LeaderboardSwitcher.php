<?php
    session_start();

    if($_SESSION['leaderboard_full'] == 0)
    {
        $_SESSION['leaderboard_full'] = 1;
    }
    else
    {
        $_SESSION['leaderboard_full'] = 0;
    }

    header('Location: ../Leaderboard.php');
?>