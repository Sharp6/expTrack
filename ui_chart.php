<?php
require_once('class.da.financeDA.php');

class overviewApp {
	public $myDa;
	public $businessRules;


	function __construct() {
		$this->myDa = new financeDA();
		$this->businessRules[] = "AND verrichtingen.categorieid != 31";
	}

	public function querybuilder($month){
		$numDays = cal_days_in_month(CAL_GREGORIAN, $month, 2014);
		$sql = "SELECT categorieen.categorieid,naam,sum(bedrag) hoofdSom,sum(subsom.bijkomendeSom) bijSom 
		FROM verrichtingen,categorieen 
		LEFT JOIN (
		SELECT parentid,sum(bedrag) bijkomendeSom FROM verrichtingen,categorieen 
		WHERE verrichtingen.categorieid = categorieen.categorieid 
		AND parentid IS NOT NULL
		AND datum BETWEEN '01-$month-2014' AND '2014-$month-$numDays'
		GROUP BY parentid
		) AS subsom ON categorieen.categorieid = subsom.parentid
		WHERE verrichtingen.categorieid = categorieen.categorieid 
		AND datum BETWEEN '01-$month-2014' AND '2014-$month-$numDays'
		AND status = 'classified' ".
		$this->businessRules[0]
		." AND categorieen.parentid IS NULL
		GROUP BY naam,categorieen.categorieid
		ORDER BY categorieen.categorieid
		";

		return $sql;
	}

	public function getOverviewForMonth($month) {
		return $this->arrayPreparer($this->myDa->query($this->querybuilder($month)));
	}

	public function arrayPreparer($resultTable){
		$resultTable->setFetchMode(PDO::FETCH_ASSOC);
		$resultTable->bindColumn(1, $catId);
		$resultTable->bindColumn(2, $catName);
		$resultTable->bindColumn(3, $sum);
		$resultTable->bindColumn(4, $subSum);

		$labels = array();
		$data = array();
		$totalekosten = 0;
		$inkomsten = 0;

		while($resultTable->fetch()){
			if($catName != "Inkomsten") {
				$labels[] = $catName;
	    		$data[] = ($sum + $subSum) * (-1);
	    		$totalekosten += ($sum + $subSum) * (-1);
			} else {
				$inkomsten += $sum;
			}	
		}

		$returnArray = [$labels,$data,$totalekosten,$inkomsten];
		return $returnArray;
	}
}


$myOverviewApp = new overviewApp();
$aprilArray = $myOverviewApp->getOverviewForMonth(4);
$mayArray = $myOverviewApp->getOverviewForMonth(5);

?>
<html>

<head>
 <title>Finance Charts</title>
 <script src="js/Chart.js"></script>
 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

 <script>
  $(document).ready(function() {
var data = {
	labels : <?php echo json_encode($aprilArray[0]); ?>,
	datasets : [
		{
			fillColor : "rgba(220,0,0,0.5)",
			strokeColor : "rgba(220,220,220,1)",
			data : <?php echo json_encode($aprilArray[1]); ?>
		},
		{
			fillColor : "rgba(0,0,220,0.5)",
			strokeColor : "rgba(220,220,220,1)",
			data : <?php echo json_encode($mayArray[1]); ?>
		}
	]
}

var options = {

}
  //Get context with jQuery - using jQuery's .get() method.
  var ctx = $("#myChart").get(0).getContext("2d");
  //This will get the first returned node in the jQuery collection.
  var myNewChart = new Chart(ctx).Bar(data,options);

  });
 </script>
</head>

<body>
<p>
<table>
<tr><td>Maand:</td><td><b>April</b></td><td><b>Mei</b></td></tr>
<tr><td>Kosten:</td><td> <?php echo $aprilArray[2]; ?></td><td> <?php echo $mayArray[2]; ?></td></tr>
<tr><td>Inkomsten: </td><td><?php echo $aprilArray[3]; ?></td><td><?php echo $mayArray[3]; ?></td></tr>
<tr><td>Verschil: </td><td><?php echo $aprilArray[3] - $aprilArray[2]; ?></td><td><?php echo $mayArray[3] - $mayArray[2]; ?></td></tr>
</p>

<canvas id="myChart" width="1000" height="400"></canvas>


</body>
</html>