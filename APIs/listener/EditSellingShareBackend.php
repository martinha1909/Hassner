<?php
    session_start();
    $_SESSION['artist_share_remove'] = key($_POST['remove_share_artist']);
    $_SESSION['share_price_remove'] = key($_POST['remove_share_price']);
    header("Location: ../../frontend/listener/listener.php");
?>