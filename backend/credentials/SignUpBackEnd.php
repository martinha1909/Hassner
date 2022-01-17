<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';  
    include '../constants/StatusCodes.php';
    include '../constants/AccountTypes.php';

    $conn = connect();
    $connPDO = connectPDO();
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email = trim($_POST['email']);
    $account_type = AccountType::User;

    $ticker = strtoupper(trim($_POST['ticker']));

    if(!empty($ticker))
    {
        $account_type = AccountType::Artist;
    }

    // echo(json_encode(array(            
    //     "username"=> $username,
    //     "password"=> $password,
    //     "email"=> $email,
    //     "account_type"=> $account_type,
    //     "ticker"=> $ticker
    // )));

    if(!empty($username) && !empty($password) && !empty($email))
    {
        // // Email Verification
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
        {
            $msg = $email." is not a supported email";
            hx_error(HX::SIGNUP, $msg);

            $_SESSION['status'] = StatusCodes::ErrEmailFormat;

            echo(json_encode(array(            
                "status"=> StatusCodes::ErrEmailFormat,
                "msg"=> "Email format not supported"
            )));

            $_SESSION['dependencies'] = "FRONTEND";
        }
        else if(!ctype_alnum($username))
        {
            $msg = $username." contains a non-alphanumeric character";
            hx_error(HX::SIGNUP, $msg);

            echo(json_encode(array(            
                "status"=> StatusCodes::ErrUsernameFormat,
                "msg"=> "Special characters not allowed"
            )));

            $_SESSION['dependencies'] = "FRONTEND";
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
                    $msg = $ticker." is already taken";
                    hx_error(HX::SIGNUP, $msg);

                    echo(json_encode(array(            
                        "status"=> StatusCodes::ErrTickerDuplicate,
                        "msg"=> "Ticker already taken"
                    )));

                    $_SESSION['dependencies'] = "FRONTEND";
                    die;
                }
                else if(strlen($ticker) != 4 ||
                        !is_numeric($ticker[0]) || 
                        !is_numeric($ticker[1]) ||
                        !ctype_alpha($ticker[2]) ||
                        !ctype_alpha($ticker[3]))
                {
                    $msg = $ticker." does not start with 2 digits and end with 2 letters";
                    hx_error(HX::SIGNUP, $msg);

                    echo(json_encode(array(            
                        "status"=> StatusCodes::ErrTickerFormat,
                        "msg"=> "Invalid ticker format"
                    )));

                    $_SESSION['dependencies'] = "FRONTEND";
                    die;
                }
            }

            if($usr_res->num_rows > 0)
            {
                $msg = $username." is already taken";
                hx_error(HX::SIGNUP, $msg);

                echo(json_encode(array(            
                    "status"=> StatusCodes::ErrUsername,
                    "msg"=> "Username already taken"
                )));

                $_SESSION['dependencies'] = "FRONTEND";
            }
            else if($email_res->num_rows > 0)
            {
                $msg = $email." is already taken";
                hx_error(HX::SIGNUP, $msg);

                $_SESSION['status'] = StatusCodes::ErrEmailDuplicate;
                echo(json_encode(array(            
                    "status"=> StatusCodes::ErrEmailDuplicate,
                    "msg"=> "Email already taken"
                )));

                $_SESSION['dependencies'] = "FRONTEND";
            }
            else
            {
                $_SESSION['status'] = signup($connPDO, $username, $password, $account_type, $email, $ticker);
                if($_SESSION['status'] == StatusCodes::Success)
                {
                    $msg = $username." successfully signed up";
                    hx_info(HX::SIGNUP, $msg);

                    $msg = "sign up data: username: ".$username.", password: ".$password.", email: ".$email.", account type: ".$account_type.", ticker: ".$ticker;
                    hx_debug(HX::SIGNUP, $msg);

                    echo(json_encode(array(            
                        "status"=> StatusCodes::Success,
                        "msg"=> ""
                    )));

                    $_SESSION['dependencies'] = "FRONTEND";
                    // header("Location: ../../frontend/credentials/login.php");
                }
                else
                {
                    $msg = "Server error";
                    hx_error(HX::SIGNUP, $msg);

                    echo(json_encode(array(            
                        "status"=> StatusCodes::ErrServer,
                        "msg"=> "Server error occured"
                    )));

                    $_SESSION['dependencies'] = "FRONTEND";
                }
            }
        }
    }
    else
    {
        $msg = "Empty input";
        hx_error(HX::SIGNUP, $msg);

        echo(json_encode(array(            
            "status"=> StatusCodes::ErrEmpty,
            "msg"=> "Empty input"
        )));

        $_SESSION['dependencies'] = "FRONTEND";   
    }
?>