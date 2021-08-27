<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';   

    $conn = connect();
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $account_type = $_POST['account_type'];

    if(!empty($username) && !empty($password)){
        $res = searchAccount($conn, $username);
        if($res->num_rows > 0)
        {
            $_SESSION['status'] = "USERNAME_ERR";
            $_SESSION['dependencies'] = "FRONTEND";
            header("Location: ../../frontend/credentials/signup.php");
        }
        else
        {
            if(empty($email))
            {
                $email = "";
            }
            else
            {
                $res = searchEmail($conn, $email);
                if($res->num_rows > 0)
                {
                    $_SESSION['status'] = "EMAIL_ERR";
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
        }
    }
    else
    {
        $_SESSION['status'] = "EMPTY_ERR";
        $_SESSION['dependencies'] = "FRONTEND";
        header("Location: ../../frontend/credentials/signup.php");
    }

      


?>