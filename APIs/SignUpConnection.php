<?php
    session_start();
    include 'connection.php';
    include 'logic.php';    

    $conn = connect();
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $account_type = $_POST['account_type'];

    if(!empty($username) && !empty($password)){
        if(empty($email))
            $email = "";
        $_SESSION['notify'] = signup($conn,$username,$password,$account_type, $email);
        // echo $_SESSION['notify'];
        if($_SESSION['notify'] == 1)
            header("Location: ../frontend/login.php");
        else
            header("Location: ../frontend/signup.php");
    }
    else
    {
        $_SESSION['notify'] = 2;
        echo $_SESSION['notify'];
        header("Location: ../frontend/signup.php");
    }

    closeCon($conn); 


?>