  
<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    
    if($_SESSION['edit'] == 1)
    {
        $_SESSION['edit'] = 0;
    }
    else if($_SESSION['edit'] == 0)
    {
        $_SESSION['edit'] = 1;
    }

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