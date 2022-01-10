<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $_SESSION['logging_mode'] = LogModes::PERSONAL;

    $conn = connect();
    $pwd = $_POST['verify_password'];
    $result = login($conn, $_SESSION['username'], $pwd);

    $_SESSION['dependencies'] = "FRONTEND";

     
    
    if($result->num_rows > 0)
    {
        echo(json_encode(array(            
            "status"=> StatusCodes::Success,
            "msg"=> ""
        )));
        // header("Location: ../../frontend/artist/PersonalPage.php");
    }
    else
    {
        echo(json_encode(array(            
            "status"=> StatusCodes::ErrPassword,
            "msg"=> "Wrong password"
        )));
    }
?>