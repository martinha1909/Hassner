  <?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $_SESSION['currency'] = $_POST['currency'];
  
    $msg = $_SESSION['currency']." has been selected";
    hx_debug(HX::HELPER, $msg);
    
    $_SESSION['usd'] = 0;

    echo json_encode(array(
      "currency" => $_SESSION['currency']
    ));

    $_SESSION['dependencies'] = "FRONTEND";
?>