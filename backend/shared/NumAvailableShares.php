<?php
header('Content-Type: application/json');
$_SESSION['dependencies'] = "BACKEND";
include '../control/Dependencies.php';
include '../constants/StatusCodes.php';
include '../shared/include/MarketplaceHelpers.php';

$artist = $_POST['artist'];
echo getArtistShareDistributed($artist) - getShareInvestedInArtist($_SESSION['username'], $artist);

?>