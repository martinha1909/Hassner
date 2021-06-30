<?php
    session_start();
    $_SESSION['currency'] = $_POST['currency'];
    $_SESSION['coins'] = 0;
    $_SESSION['cad'] = 0;
    // echo $_SESSION['currency'];
    header("Location: ../frontend/listener.php");
?>