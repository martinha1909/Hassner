<?php
    session_start();
    $_SESSION['saved'] = 1;
    header("Location: ../frontend/CheckoutView.php");

?>