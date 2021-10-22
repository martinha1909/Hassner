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
        header("Location: ../../frontend/artist/PersonalPage.php");
    }
    else
    {
        $_SESSION['status'] = StatusCodes::ErrPassword;
        header("Location: ../../frontend/artist/Artist.php");
    }
?>