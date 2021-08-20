<?php
    $_SESSION['dependencies'] = 1;
    include '../control/Dependencies.php';

    $conn = connect();
    $pwd = $_POST['verify_password'];
    $result = login($conn, $_SESSION['username'], $pwd);

    $_SESSION['dependencies'] = 0;

     
    
    if($result->num_rows > 0)
    {
        header("Location: ../../frontend/artist/PersonalPage.php");
    }
    else
    {
        $_SESSION['notify'] = 3;
        header("Location: ../../frontend/artist/PersonalPage.php");
    }
?>