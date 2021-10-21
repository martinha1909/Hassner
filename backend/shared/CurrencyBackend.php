  <?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $_SESSION['currency'] = $_POST['currency'];
    
    $_SESSION['usd'] = 0;

    $_SESSION['dependencies'] = "FRONTEND";

    returnToMainPage();
?>