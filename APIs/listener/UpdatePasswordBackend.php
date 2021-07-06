<?php
    session_start();
    include '../logic.php';
    include '../connection.php';
    $conn = connect();
    $new_pwd = $_POST['pwd_edit'];
    if(!empty($new_pwd))
    {
        editPassword($conn, $_SESSION['username'], $new_pwd);  
    }
    $_SESSION['edit'] = 0;
    
    header("Location: ../../frontend/listener/PersonalPage.php");
?>