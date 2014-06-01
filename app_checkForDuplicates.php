<?php
require_once('class.app.updater.php');

$myUpdater = new updater();

$response[] = "Setting duplicate records to status duplicate...";
$count = $myUpdater->setStatusForDuplicates();
$response[] = "Affected $count rows.";

echo json_encode($response);
?>
