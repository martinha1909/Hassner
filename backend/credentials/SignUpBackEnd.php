<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';   

    $conn = connect();
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $account_type = $_POST['account_type'];

    // Email Verification
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
    {
        $_SESSION['status'] = "EMAIL_FORMAT_ERR";
    }
    else if(!empty($username) && !empty($password) && !empty($email)){
        $usr_res = searchAccount($conn, $username);
        $email_res = searchEmail($conn, $email);
        if($usr_res->num_rows > 0)
        {
            $_SESSION['status'] = "USERNAME_ERR";
            $_SESSION['dependencies'] = "FRONTEND";
            header("Location: ../../frontend/credentials/signup.php");
        }
        else if($email_res->num_rows > 0)
        {
            $_SESSION['status'] = "DUPL_EMAIL_ERR";
            $_SESSION['dependencies'] = "FRONTEND";
            header("Location: ../../frontend/credentials/signup.php");
        }
        else
        {
            $_SESSION['status'] = signup($conn, $username, $password, $account_type, $email);
            if($_SESSION['status'] == "SUCCESS")
            {
                $_SESSION['dependencies'] = "FRONTEND";
                header("Location: ../../frontend/credentials/login.php");
            }
            else
            {
                $_SESSION['status'] = "SERVER_ERR";
                $_SESSION['dependencies'] = "FRONTEND";
                header("Location: ../../frontend/credentials/signup.php");
            }
        }
    }
    else
    {
        $_SESSION['status'] = "EMPTY_ERR";
        $_SESSION['dependencies'] = "FRONTEND";
        header("Location: ../../frontend/credentials/signup.php");
    }

      


?>