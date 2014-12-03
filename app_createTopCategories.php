<?php
require_once('class.app.recordCreator.php');

$myCreator = new recordCreator();

$response[] = "Adding new records for empty top categories...";
$count = $myCreator->createEmptyTopCategories('06');
$response[] = "Added $count records.";

echo json_encode($response);
?>
