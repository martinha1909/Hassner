<?php
    session_start();
    
    if($_SESSION['artist_investor_amount_invested_sort'] == 0)
    {
        $_SESSION['artist_investor_amount_invested_sort'] = 1;
    }
    else
    {
        $_SESSION['artist_investor_amount_invested_sort'] = 0;
    }

    header("Location: ../../../frontend/artist/Artist.php");
?>