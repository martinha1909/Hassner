<?php
    $_SESSION['dependencies'] = 1;
    include '../control/Dependencies.php';   

    $conn = connect();
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $account_type = $_POST['account_type'];

    if(!empty($username) && !empty($password)){
        if(empty($email))
            $email = "";
        $_SESSION['notify'] = signup($conn,$username,$password,$account_type, $email);
        if($_SESSION['notify'] == 1)
        {
            $_SESSION['dependencies'] = 0;
            header("Location: ../../frontend/credentials/login.php");
        }
        else
        {
            $_SESSION['dependencies'] = 0;
            header("Location: ../../frontend/credentials/signup.php");
        }
    }
    else
    {
        $_SESSION['notify'] = 2;
        $_SESSION['dependencies'] = 0;
        header("Location: ../../frontend/credentials/signup.php");
    }

      


?>