  <?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $_SESSION['currency'] = $_POST['currency'];
    $_SESSION['coins'] = 0;
    $_SESSION['cad'] = 0;

    $_SESSION['dependencies'] = "FRONTEND";

    if($_SESSION['account_type'] == "user")
    {
      header("Location: ../../frontend/listener/listener.php");
    }
    else if($_SESSION['account_type'] == "artist")
    {
      header("Location: ../../frontend/artist/Artist.php");
    }
?>