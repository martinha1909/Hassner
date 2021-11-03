<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $_SESSION['graph_options'] = $_POST['graph_options'];

    $_SESSION['dependencies'] = "FRONTEND";
    header("Location: ../../frontend/listener/ArtistUserShareInfo.php");
?>