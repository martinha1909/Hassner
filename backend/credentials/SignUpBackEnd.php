<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';  
    include '../constants/StatusCodes.php';

    $conn = connect();
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $account_type = $_POST['account_type'];

    // Email Verification
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
    {
        $_SESSION['status'] = StatusCodes::ErrEmailFormat;
    }
    else if(!empty($username) && !empty($password) && !empty($email))
    {
        if(!ctype_alnum($username))
        {
            $_SESSION['status'] = StatusCodes::ErrUsernameFormat;
            goto done;
        }
        else
        {
            $usr_res = searchAccount($conn, $username);
            $email_res = searchEmail($conn, $email);
            if($usr_res->num_rows > 0)
            {
                $_SESSION['status'] = StatusCodes::ErrUsername;
                goto done;
            }
            else if($email_res->num_rows > 0)
            {
                $_SESSION['status'] = StatusCodes::ErrEmailDuplicate;
                goto done;
            }
            else
            {
                $_SESSION['status'] = signup($conn, $username, $password, $account_type, $email);
                if($_SESSION['status'] == StatusCodes::Success)
                {
                    $_SESSION['dependencies'] = "FRONTEND";
                    header("Location: ../../frontend/credentials/login.php");
                }
                else
                {
                    $_SESSION['status'] = StatusCodes::ErrServer;
                    goto done;
                }
            }
        }
    }
    else
    {
        $_SESSION['status'] = StatusCodes::ErrEmpty;
        goto done;
    }
done:
    $_SESSION['dependencies'] = "FRONTEND";
    header("Location: ../../frontend/credentials/signup.php");
?>