<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $_SESSION['logging_mode'] = LogModes::PERSONAL;

    $conn = connect();
    $pwd = $_POST['verify_password'];
    $result = null;

    $_SESSION['dependencies'] = "FRONTEND";

     
    
    if(login($conn, $_SESSION['username'], $pwd, $result))
    {
        echo(json_encode(array(            
            "status"=> StatusCodes::Success,
            "msg"=> ""
        )));
    }
    else
    {
        echo(json_encode(array(            
            "status"=> StatusCodes::ErrPassword,
            "msg"=> "Wrong password"
        )));
    }
?>