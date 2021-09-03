<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $_SESSION['logging_mode'] = "PERSONAL_PAGE";
    $conn = connect();
    $pwd = $_POST['verify_password'];
    $result = login($conn, $_SESSION['username'], $pwd);
    
    if($result->num_rows > 0)
    {
        $_SESSION['dependencies'] = "FRONTEND";
        header("Location: ../../frontend/listener/PersonalPage.php");
    }
    else
    {
        $_SESSION['status'] = "ERROR";
        $_SESSION['dependencies'] = "FRONTEND";
        header("Location: ../../frontend/listener/listener.php");
    }
?>