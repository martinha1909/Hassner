  
<?php
    session_start();
    if($_SESSION['edit'] == 1)
    {
        $_SESSION['edit'] = 0;
    }
    else if($_SESSION['edit'] == 0)
    {
        $_SESSION['edit'] = 1;
    }
    header("Location: ../../frontend/listener/PersonalPage.php");
?>