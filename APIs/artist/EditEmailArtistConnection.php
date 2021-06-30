<?php
    session_start();
    include '../logic.php';
    include '../connection.php';
    $conn = connect();
    $new_email = $_POST['email_edit'];
    if(!empty($new_email))
    {
        $_SESSION['notify'] = editEmail($conn, $_SESSION['username'], $new_email);  
    }
    else
    {
        $new_email = "";
        $_SESSION['notify'] = editEmail($conn, $_SESSION['username'], $new_email);  
    }
    $_SESSION['edit'] = 0;
    
    header("Location: ../../frontend/artist/ArtistPage.php");
?>