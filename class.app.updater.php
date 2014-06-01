<?php
require_once('class.da.financeDA.php');

class updater {
	public $myDa;

	function __construct(){
		$this->myDa = new financeDA();
	}

	public function setStatusClassified() {
		return $this->myDa->execute("UPDATE verrichtingen SET
			status = 'classified'
			WHERE categorieId IS NOT NULL 
			AND status != 'classificationProposed'
			AND status != 'classified'
			");
	}

	public function setStatusForDuplicates() {
		return $this->myDa->execute("UPDATE verrichtingen A SET status = 'duplicate' 
			WHERE EXISTS (
				select B.verrekeningid FROM verrichtingen B 
				WHERE A.bedrag = B.bedrag AND A.datum = B.datum 
				AND A.verrekeningid > B.verrekeningid
				AND (A.status != 'duplicate' AND B.status != 'duplicate')
				AND A.info IS NOT NULL)
			AND status = 'imported'");
	}

}
?>