<?php
    session_start();
    $_SESSION['edit'] = 2;
    header("Location: ../../frontend/artist/ArtistPage.php");
?>