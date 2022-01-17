<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $_SESSION['saved'] = 1;

    $msg = $_SESSION['username']." loaded saved payment info from db";
    hx_debug(HX::CURRENCY, $msg);

    header("Location: ../../frontend/shared/Checkout.php");
?>