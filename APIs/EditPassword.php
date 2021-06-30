<?php
    session_start();
    $_SESSION['edit'] = 1;
    header("Location: ../frontend/PersonalPage.php");
?>