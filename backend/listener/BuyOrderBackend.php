<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include 'ListenerBackend.php';

    $conn = connect();

    $request_quantity = $_POST['request_quantity'];
    $request_price = $_POST['request_price'];

    $current_date = getCurrentDate("America/Edmonton");
    $date_parser = dayAndTimeSplitter($current_date);

    postBuyOrder($conn, 
                 $_SESSION['username'], 
                 $_SESSION['selected_artist'], 
                 $request_quantity, 
                 $request_price, 
                 $date_parser[0], 
                 $date_parser[1]);

    $_SESSION['display'] == "PORTFOLIO";
    $_SESSION['dependencies'] = "FRONTEND";
    returnToMainPage();
?>