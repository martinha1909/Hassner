<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    $conn = connect();
    $new_pwd = $_POST['pwd_edit'];
    if(!empty($new_pwd))
    {
        editPassword($conn, $_SESSION['username'], $new_pwd);  
    }
    $_SESSION['edit'] = 0;
    
    $_SESSION['dependencies'] = "FRONTEND";

     

    if($_SESSION['account_type'] == "user")
    {
        header("Location: ../../frontend/listener/PersonalPage.php");
    }
    else if($_SESSION['account_type'] == "artist")
    {
        header("Location: ../../frontend/artist/PersonalPage.php");
    }
?>