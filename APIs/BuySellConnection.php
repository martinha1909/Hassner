<?php
    session_start();
    $post = $_POST['buy_sell'];
    if($post == "+Buy more shares")
        $_SESSION['buy_sell'] = "BUY";
    if($post == "-Sell your shares")
        $_SESSION['buy_sell'] = "SELL";
    header("Location: ../frontend/SongPageUser.php");
?>