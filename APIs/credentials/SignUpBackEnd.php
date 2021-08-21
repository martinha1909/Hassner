<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';   

    $conn = connect();
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $account_type = $_POST['account_type'];

    if(!empty($username) && !empty($password)){
        if(empty($email))
            $email = "";
        $_SESSION['status'] = signup($conn,$username,$password,$account_type, $email);
        if($_SESSION['status'] == 1)
        {
            $_SESSION['dependencies'] = "FRONTEND";
            header("Location: ../../frontend/credentials/login.php");
        }
        else
        {
            $_SESSION['dependencies'] = "FRONTEND";
            header("Location: ../../frontend/credentials/signup.php");
        }
    }
    else
    {
        $_SESSION['status'] = 2;
        $_SESSION['dependencies'] = "FRONTEND";
        header("Location: ../../frontend/credentials/signup.php");
    }

      


?>