<?php
    session_start();
    $_SESSION['dependencies'] = "CRON";
    define("HX_INCLUDE_DIR", dirname(dirname(dirname(__FILE__))));

    $_SESSION['info'] = false;
    $_SESSION['debug'] = false;
    $_SESSION['error'] = false;
    include HX_INCLUDE_DIR."/backend/constants/StatusCodes.php";
    include HX_INCLUDE_DIR."/backend/constants/HX.php";
    include HX_INCLUDE_DIR."/backend/logging/logger.php";
    include HX_INCLUDE_DIR."/backend/constants/Timezone.php";
    include HX_INCLUDE_DIR."/backend/control/connection.php";
    include HX_INCLUDE_DIR."/backend/control/Queries.php";

    date_default_timezone_set(Timezone::MST);

    $connPDO = connectPDO();
    $current_date = date('Y-m-d H:i:s');
    postSellOrder($connPDO, "JackCampbell", "Al Lure", 10, 10, -1, -1, $current_date, 0);
?>