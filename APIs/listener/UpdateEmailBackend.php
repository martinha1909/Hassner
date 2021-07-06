<?php
    session_start();
    include '../logic.php';
    include '../connection.php';
    $conn = connect();
    $new_email = $_POST['email_edit'];
    if(!empty($new_email))
    {
        editEmail($conn, $_SESSION['username'], $new_email);  
    }
    else
    {
        $new_email = "";
        editEmail($conn, $_SESSION['username'], $new_email);  
    }
    $_SESSION['edit'] = 0;
    
    header("Location: ../../frontend/listener/PersonalPage.php");
?>