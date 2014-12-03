<?php 
require_once("class.app.getters.php");

$myGetter = new getter();

$categories = $myGetter->getCategories();

echo json_encode($categories);

?>