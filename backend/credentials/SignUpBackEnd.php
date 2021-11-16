<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';  
    include '../constants/StatusCodes.php';

    $conn = connect();
    $connPDO = connectPDO();
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email = trim($_POST['email']);
    $account_type = $_POST['account_type'];
    if($account_type == AccountType::Artist)
    {
        $ticker = strtoupper(trim($_POST['ticker']));
    }
    else
    {
        $ticker = NULL;
    }

    // Email Verification
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
    {
        $msg = $email." is not a supported email";
        hx_error(ErrorLogType::USER, $msg, ErrorLogPath::BACKEND);

        $_SESSION['status'] = StatusCodes::ErrEmailFormat;
        $_SESSION['dependencies'] = "FRONTEND";
        header("Location: ../../frontend/credentials/signup.php");
    }
    else if(!empty($username) && !empty($password) && !empty($email)){
        if(!ctype_alnum($username))
        {
            $_SESSION['status'] = StatusCodes::ErrUsernameFormat;
            $_SESSION['dependencies'] = "FRONTEND";
            header("Location: ../../frontend/credentials/signup.php");
        }
        else
        {
            $usr_res = searchAccount($conn, $username);
            $email_res = searchEmail($conn, $email);

            if($account_type == AccountType::Artist)
            {
                $ticker_res = searchTicker($conn, $ticker);
                // Validate ticker
                if($ticker_res->num_rows > 0)
                {
                    $_SESSION['status'] = StatusCodes::ErrTickerDuplicate;
                    $_SESSION['dependencies'] = "FRONTEND";
                    header("Location: ../../frontend/credentials/signup.php");
                }
                else if(strlen($ticker) != 4 ||
                        !is_numeric($ticker[0]) || 
                        !is_numeric($ticker[1]) ||
                        !ctype_alpha($ticker[2]) ||
                        !ctype_alpha($ticker[3]))
                {
                    $_SESSION['status'] = StatusCodes::ErrTickerFormat;
                    $_SESSION['dependencies'] = "FRONTEND";
                    header("Location: ../../frontend/credentials/signup.php");
                }
            }

            if($usr_res->num_rows > 0)
            {
                $_SESSION['status'] = StatusCodes::ErrUsername;
                $_SESSION['dependencies'] = "FRONTEND";
                header("Location: ../../frontend/credentials/signup.php");
            }
            else if($email_res->num_rows > 0)
            {
                $_SESSION['status'] = StatusCodes::ErrEmailDuplicate;
                $_SESSION['dependencies'] = "FRONTEND";
                header("Location: ../../frontend/credentials/signup.php");
            }
            else
            {
                
                $_SESSION['status'] = signup($connPDO, $username, $password, $account_type, $email, $ticker);
                if($_SESSION['status'] == StatusCodes::Success)
                {
                    $_SESSION['dependencies'] = "FRONTEND";
                    header("Location: ../../frontend/credentials/login.php");
                }
                else
                {
                    $_SESSION['status'] = StatusCodes::ErrServer;
                    $_SESSION['dependencies'] = "FRONTEND";
                    header("Location: ../../frontend/credentials/signup.php");
                }
            }
        }
    }
    else
    {
        $_SESSION['status'] = StatusCodes::ErrEmpty;
        $_SESSION['dependencies'] = "FRONTEND";
        header("Location: ../../frontend/credentials/signup.php");
    }

      


?>