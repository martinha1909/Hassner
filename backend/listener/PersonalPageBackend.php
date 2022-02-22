<?php
    $_SESSION['dependencies'] = "BACKEND";

    include '../control/Dependencies.php';
    include '../constants/StatusCodes.php';
    include '../constants/LoggingModes.php';

    $_SESSION['logging_mode'] = LogModes::PERSONAL;
    $conn = connect();
    $pwd = $_POST['verify_password'];
    $result = null;
    
    if(login($conn, $_SESSION['username'], $pwd, $result))
    {
        echo(json_encode(array(            
            "status"=> StatusCodes::Success,
            "msg"=> ""
        )));

        $_SESSION['dependencies'] = "FRONTEND";
    }
    else
    {
        echo(json_encode(array(            
            "status"=> StatusCodes::ErrGeneric,
            "msg"=> "Wrong password"
        )));

        $_SESSION['dependencies'] = "FRONTEND";
    }
?>