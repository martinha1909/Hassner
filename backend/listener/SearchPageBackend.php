<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $conn = connect();
    $_SESSION['artist_found'];
    $artist_name = $_POST['artist_search'];
    $result = searchAccount($conn, $artist_name);
    if($result->num_rows > 0)
    {
        $_SESSION['artist_found'] = 1;
        $_SESSION['artist_found'] = $result->fetch_assoc();
    }
    else
    {
        $_SESSION['artist_found'] = 0;
    }
    $_SESSION['dependencies'] = "FRONTEND";
     
    header("Location: ../../frontend/listener/SearchPage.php");
?>