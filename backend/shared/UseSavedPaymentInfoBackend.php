<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/dependencies.php';

    $_SESSION['saved'] = 1;

    $msg = $_SESSION['username']." loaded saved payment info from db";
    hx_debug(ErrorLogType::CURRENCY, $msg);

    header("Location: ../../frontend/shared/Checkout.php");
?>