<?php
require_once('class.app.updater.php');

$myUpdater = new updater();

$response[] = "Proposing new classifications...";
$count = $myUpdater->proposeClassifications();
$response[] = "Affected $count rows.";

echo json_encode($response);
?>
