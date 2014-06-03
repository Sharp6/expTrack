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
		$numberOfTouched = 0;

		$numberOfTouched += $this->myDa->execute("UPDATE verrichtingen A SET status = 'duplicate' 
			WHERE EXISTS (
				select B.verrekeningid FROM verrichtingen B 
				WHERE A.bedrag = B.bedrag AND A.datum = B.datum 
				AND A.verrekeningid > B.verrekeningid
				AND (A.status != 'duplicate' AND B.status != 'duplicate')
				AND A.info IS NOT NULL)
				AND status = 'imported'");

		$numberOfTouched += $this->myDa->execute("UPDATE verrichtingen A SET status = 'notDuplicate' 
			WHERE status = 'imported'");

		return $numberOfTouched;
	}

	public function proposeClassifications() {
		$numberOfClassifications = 0;
		$numberOfClassifications += $this->myDa->execute("UPDATE verrichtingen
			SET categorieid = 1, status='classificationProposed'
			WHERE (info LIKE '%DELH_HIPPODROOM%'
			OR info like '%DELH_HOPLAND%'
			OR info like '%DELH_ BOSUIL%'
			OR info like '%DELHAIZE%'
			OR info like '%PROXY_ANTHONISSE%'
			OR info like '%AH3003%' 
			OR info like '%AH 3004%'
			OR info like '%COLRUYT%'
			OR info like '%GB GROENPLAATS%'
			OR info like '%,OPVRAGING SPECIEN%'
			OR info like '%Opname Bancontact%')
			AND status = 'notDuplicate'");

		$numberOfClassifications += $this->myDa->execute("UPDATE verrichtingen
			SET categorieid = 2, status='classificationProposed'
			WHERE (info LIKE '%LUC OEYEN%'
			OR info like '%APOTH DE KROON%'
			OR info like '%APOT MUYLAERT%'
			OR info like '%APOTHEEK%'
			OR info like '%CHRISTELIJKE MUTUALITEIT%'
			OR info like '%CM ANTWERPEN%'
			OR info like '%PAUL MARCELIS%')
			AND status = 'notDuplicate'");

		// TRANSFERS
		$numberOfClassifications += $this->myDa->execute("UPDATE verrichtingen
			SET categorieid = 24, status='classificationProposed'
			WHERE (info LIKE '%GEZAMENLIJKE REKENING%'
			OR info like '%Debet ten voordele van BCC-MASTERCARD%')
			AND status = 'notDuplicate'");

		// FONCIA
		$numberOfClassifications += $this->myDa->execute("UPDATE verrichtingen
			SET categorieid = 14, status='classificationProposed'
			WHERE (info LIKE '%Uw overschrijving-VME%')
			AND status = 'notDuplicate'");

		// TELECOM
		$numberOfClassifications += $this->myDa->execute("UPDATE verrichtingen
			SET categorieid = 25, status='classificationProposed'
			WHERE info LIKE '%Telenet N V%'
			AND status = 'notDuplicate'");

		// INKOMSTEN
		$numberOfClassifications += $this->myDa->execute("UPDATE verrichtingen
			SET categorieid = 26, status='classificationProposed'
			WHERE (info LIKE 'UNIVERSITEIT ANTWERPEN,/A/%'
			)
			AND status = 'notDuplicate'");

		// BANK KOSTEN
		$numberOfClassifications += $this->myDa->execute("UPDATE verrichtingen
			SET categorieid = 33, status='classificationProposed'
			WHERE (info LIKE ',INTERESTEN%'
			OR info like ',ZEGELRECHT%'
			OR info like ',BIJDRAGE IN DE BEHEERSKOSTEN%')
			AND status = 'notDuplicate'");

		// UTILITIES
		$numberOfClassifications += $this->myDa->execute("UPDATE verrichtingen
			SET categorieid = 5, status='classificationProposed'
			WHERE (info LIKE 'ESSENT BELGIUM%'
			OR info like 'Uw overschrijving-ESSENT BELGIUM%'
			OR info like 'Uw overschrijving-AWW%')
			AND status = 'notDuplicate'");

		// MUZIEK
		$numberOfClassifications += $this->myDa->execute("UPDATE verrichtingen
			SET categorieid = 8, status='classificationProposed'
			WHERE (info LIKE 'SPOTIFY%'
			OR info like 'MUSICNOTES%')
			AND status = 'notDuplicate'");

		// HOBBY
		$numberOfClassifications += $this->myDa->execute("UPDATE verrichtingen
			SET categorieid = 9, status='classificationProposed'
			WHERE (info LIKE '%PITERFLORI%'
			OR info like 'STEAMGAMES%')
			AND status = 'notDuplicate'");

		// AFBETALING
		$numberOfClassifications += $this->myDa->execute("UPDATE verrichtingen
			SET categorieid = 13, status='classificationProposed'
			WHERE (info LIKE 'SEPA-domiciliring-AXA BANK%'
			)
			AND status = 'notDuplicate'");

		// BABY EENMALIG
		$numberOfClassifications += $this->myDa->execute("UPDATE verrichtingen
			SET categorieid = 18, status='classificationProposed'
			WHERE (info LIKE 'BABY-DUMP%'
			OR info like '%LAND VAN OOIT%')
			AND status = 'notDuplicate'");
		

		return $numberOfClassifications;
	}


}
?>