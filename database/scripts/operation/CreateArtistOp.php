<?php
    include '../include/DatabaseHelpers.php';
    include '../../../backend/control/db_comms/connection.php';
    include '../../../backend/constants/AccountTypes.php';
    include '../../../backend/constants/StatusCodes.php';

    $conn = connect();
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $ticker = $_POST['market_tag'];
    $account_type = AccountType::Artist;

    if(!empty($username) && !empty($password) && !empty($email))
    {
        // // Email Verification
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
        {
            echo(json_encode(array(            
                "status"=> StatusCodes::ErrEmailFormat,
                "msg"=> "Email format not supported"
            )));
        }
        else if(!ctype_alnum($username))
        {
            echo(json_encode(array(            
                "status"=> StatusCodes::ErrUsernameFormat,
                "msg"=> "Special characters not allowed"
            )));
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

                    echo(json_encode(array(            
                        "status"=> StatusCodes::ErrTickerDuplicate,
                        "msg"=> "Ticker already taken"
                    )));
                    die;
                }
                else if(strlen($ticker) != 4 ||
                        !is_numeric($ticker[0]) || 
                        !is_numeric($ticker[1]) ||
                        !ctype_alpha($ticker[2]) ||
                        !ctype_alpha($ticker[3]))
                {

                    echo(json_encode(array(            
                        "status"=> StatusCodes::ErrTickerFormat,
                        "msg"=> "Invalid ticker format"
                    )));
                    die;
                }
            }

            if($usr_res->num_rows > 0)
            {
                echo(json_encode(array(            
                    "status"=> StatusCodes::ErrUsername,
                    "msg"=> "Username already taken"
                )));
            }
            else if($email_res->num_rows > 0)
            {
                $_SESSION['status'] = StatusCodes::ErrEmailDuplicate;
                echo(json_encode(array(            
                    "status"=> StatusCodes::ErrEmailDuplicate,
                    "msg"=> "Email already taken"
                )));
            }
            else
            {
                $connPDO = connectPDO();
                $status = signup($connPDO, $username, $password, $account_type, $email, $ticker);
                if($status == StatusCodes::Success)
                {
                    // uncomment for email
                    // $_SESSION['status'] = sendEmailService($username, $password, $email);
                    echo(json_encode(array(            
                        "status"=> StatusCodes::Success,
                        "msg"=> "Account created successfully"
                    )));
                }
                else
                {
                    echo(json_encode(array(            
                        "status"=> StatusCodes::ErrServer,
                        "msg"=> "Server error occured"
                    )));
                }
            }
        }
    }
    else
    {

        echo(json_encode(array(            
            "status"=> StatusCodes::ErrEmpty,
            "msg"=> "Empty input"
        )));  
    }
?>