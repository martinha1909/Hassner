<?php
    session_start();
    $_SESSION['dependencies'] = "TEST";
    $_SESSION['info'] = false;
    $_SESSION['debug'] = false;
    $_SESSION['error'] = false;
    $_SESSION['username'] = "JackCampbell";
    include "backend/constants/StatusCodes.php";
    include "backend/constants/HX.php";
    include "backend/logging/logger.php";
    include "backend/constants/Timezone.php";
    include "backend/control/connection.php";
    include "backend/control/Queries.php";

    date_default_timezone_set(Timezone::MST);

    $connPDO = connectPDO();
    $current_date = date('Y-m-d H:i:s');
    postSellOrder($connPDO, "JackCampbell", "Al Lure", 10, 10, -1, -1, $current_date, 0);
?>