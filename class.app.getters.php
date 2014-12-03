<?php
require_once('class.da.financeDA.php');

class getter {
	public $myDa;

	function __construct(){
		$this->myDa = new financeDA();
	}

	public function getCategories() {
		$result = $this->myDa->query("SELECT categorieid,naam,parentid FROM categorieen");
		$result->setFetchMode(PDO::FETCH_ASSOC);
    $result->bindColumn(1, $categorieId);
		$result->bindColumn(2, $naam);
		$result->bindColumn(3, $parentId);

		return $result->fetchAll();
	}
}
?>