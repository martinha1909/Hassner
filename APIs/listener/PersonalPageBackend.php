<?php
    $_SESSION['dependencies'] = 1;
    include '../control/Dependencies.php';
    $conn = connect();
    $pwd = $_POST['verify_password'];
    $result = login($conn, $_SESSION['username'], $pwd);

     
    
    if($result->num_rows > 0)
    {
        $_SESSION['dependencies'] = 0;;
        header("Location: ../../frontend/listener/PersonalPage.php");
    }
    else
    {
        $_SESSION['status'] = 3;
        $_SESSION['dependencies'] = 0;
        header("Location: ../../frontend/listener/listener.php");
    }
?>