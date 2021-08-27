  <?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $_SESSION['currency'] = $_POST['currency'];
    
    $_SESSION['coins'] = 0;
    $_SESSION['cad'] = 0;

    $_SESSION['dependencies'] = "FRONTEND";

    returnToMainPage();
?>