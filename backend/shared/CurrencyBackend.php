  <?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $_SESSION['currency'] = $_POST['currency'];
    $msg = $_SESSION['currency']." has been selected";
    hx_debug(ErrorLogType::HELPER, $msg);
    
    $_SESSION['usd'] = 0;

    $_SESSION['dependencies'] = "FRONTEND";

    returnToMainPage();
?>