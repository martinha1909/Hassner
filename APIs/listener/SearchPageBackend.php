<?php
    session_start();
    include '../logic.php';
    include '../connection.php';

    $conn = connect();
    $_SESSION['found'];
    $artist_name = $_POST['artist_search'];
    $result = searchAccount($conn, $artist_name);
    if($result->num_rows > 0)
    {
        $_SESSION['found'] = 1;
        $_SESSION['artist_found'] = $result->fetch_assoc();
    }
    else
    {
        $_SESSION['found'] = 0;
    }
    header("Location: ../../frontend/listener/SearchPage.php");
?>