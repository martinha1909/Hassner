<?php
    session_start();
    if($_POST['email'] == "123@gmail.com")
        $_SESSION['notify'] = 1;
    else
        $_SESSION['notify'] = 2;
    header("Location: ../frontend/AdminRegister.php");
?>