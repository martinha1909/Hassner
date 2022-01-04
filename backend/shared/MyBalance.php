<?php
header('Content-Type: application/json');
$_SESSION['dependencies'] = "BACKEND";
include '../control/Dependencies.php';
include '../constants/StatusCodes.php';
include '../shared/include/MarketplaceHelpers.php';

echo json_encode(getUserBalance($_SESSION['username']));

?>