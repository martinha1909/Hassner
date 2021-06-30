<?php
    session_start();
    include '../logic.php';
    include '../connection.php';
    $conn = connect();
    $pwd = $_POST['verify_password'];
    $result = login($conn, $_SESSION['username'], $pwd);
    if($result->num_rows > 0)
    {
        header("Location: ../../frontend/artist/ArtistPage.php");
    }
    else
    {
        $_SESSION['notify'] = 3;
        header("Location: ../frontend/artist.php");
    }
?>