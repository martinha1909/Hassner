<?php
    $_SESSION['dependencies'] = 1;
    include '../control/Dependencies.php';
    
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
    
    $_SESSION['dependencies'] = 0;

    closeCon($conn);

    if($_SESSION['account_type'] == "user")
    {
        header("Location: ../../frontend/listener/PersonalPage.php");
    }
    else if($_SESSION['account_type'] == "artist")
    {
        header("Location: ../../frontend/artist/PersonalPage.php");
    }
?>