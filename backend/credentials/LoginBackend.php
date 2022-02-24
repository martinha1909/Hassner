<?php
    session_start();
    $_SESSION['dependencies'] = "BACKEND";
    include '../shared/include/Helper.php';
    include '../control/Queries.php';
    include '../control/connection.php';
    include '../logging/logger.php';
    include '../constants/AccountTypes.php';
    include '../constants/StatusCodes.php';
    include '../constants/HX.php';

    $conn = connect();
    $username = $_POST['username'];
    $password = $_POST['password'];
    $result = null;


    if(isTestingPhase($username, $password))
    {
        if(login($conn,$username,$password, $result)) 
        {
            $_SESSION['account_type'] = $result['account_type'];
            $_SESSION['username'] = $result['username'];
            $_SESSION['dependencies'] = "FRONTEND";
            echo(json_encode(array(            
                "status"=> 1,
                "msg"=> "Success"
            )));
        }
        else
        {
            $msg = "Credentials not found";
            hx_error(HX::LOGIN, $msg);

            $_SESSION['dependencies'] = "FRONTEND";
            echo(json_encode(array(            
                "status"=> "0",
                "msg"=> "Invalid Login"
            )));
        }
    }
    else
    {
        echo(json_encode(array(            
            "status"=> "0",
            "msg"=> "Testing phase will start March 1st, 2022"
        )));
    }

?>