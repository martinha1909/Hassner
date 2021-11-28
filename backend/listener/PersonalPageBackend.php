<?php
    $_SESSION['dependencies'] = "BACKEND";

    include '../control/Dependencies.php';
    include '../constants/StatusCodes.php';
    include '../constants/LoggingModes.php';

    $_SESSION['logging_mode'] = LogModes::PERSONAL;
    $conn = connect();
    $pwd = $_POST['verify_password'];
    $result = login($conn, $_SESSION['username'], $pwd);
    
    if($result->num_rows > 0)
    {
        $_SESSION['dependencies'] = "FRONTEND";
        // header("Location: ../../frontend/listener/PersonalPage.php");
    }
    else
    {
        $_SESSION['status'] = StatusCodes::ErrGeneric;
        $_SESSION['dependencies'] = "FRONTEND";
        // header("Location: ../../frontend/listener/listener.php");
    }
?>