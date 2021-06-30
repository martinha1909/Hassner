<?php
    session_start();
    include 'logic.php';
    include 'connection.php';
    $conn = connect();
    $username = $_POST['admin_username'];
    $password = $_POST['admin_password'];
    $admin_id = $_POST['admin_id'];
    $account_type = "admin";
    if($admin_id == 111)
    {
        $_SESSION['notify'] = signup($conn, $username, $password, $account_type);
    }
    else
        $_SESSION['notify'] = 2;
    header("Location: ../frontend/AdminRegister.php");
?>