<?php
require_once('class.app.updater.php');

$myUpdater = new updater();

$response[] = "Setting classified records to status classified...";
$count = $myUpdater->setStatusClassified();
$response[] = "Affected $count rows.";

echo json_encode($response);
?>
