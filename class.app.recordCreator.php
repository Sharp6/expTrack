<?php
require_once('class.da.financeDA.php');

class recordCreator {
	public $myDa;

	function __construct(){
		$this->myDa = new financeDA();
	}

	function createEmptyTopCategories($maand) {
		$numDays = cal_days_in_month(CAL_GREGORIAN, $maand, 2014);
		$result = $this->myDa->query("SELECT categorieid FROM categorieen
			WHERE parentid IS NULL
			AND NOT EXISTS(
				SELECT categorieid from verrichtingen
				WHERE datum BETWEEN '2014-$maand-01' AND '2014-$maand-$numDays'
				AND categorieen.categorieid = verrichtingen.categorieid
				GROUP BY categorieid
				)");
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$result->bindColumn(1, $catId);
		$categories = array();
		while($result->fetch()){
			$categories[] = $catId;
		}

		$aantal = 0;
		foreach($categories as $cateId) {
			$aantal += $this->myDa->execute("INSERT INTO verrichtingen(bedrag,datum,categorieid,status) VALUES('0','2014-$maand-01','".$cateId."','classified');");
		}
		return $aantal;
	}

}