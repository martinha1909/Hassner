  <?php
    session_start();
    $_SESSION['currency'] = $_POST['currency'];
    $_SESSION['coins'] = 0;
    $_SESSION['cad'] = 0;
    header("Location: ../../frontend/listener/listener.php");
?>