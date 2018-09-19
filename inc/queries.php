<?php
include_once "constants.inc.php";

function getterreinid(){
	$Terreinid="";
	if ($_SESSION['Terreinid']==0) {
		$where = "Terrein_id is null ";
	} else {
		$Terreinid = $_SESSION['Terreinid'];
		$where = "Terrein_id = ".$Terreinid." ";
	}
	return $where;
}
					 

//bepaal op basis van opgegeven jaar en kwartaal
//de controleperiode die beschouwd moet worden
function getcheckperiod($jr, $kwartaal) {
	if(isset($jr)){
		 $jaar = substr($jr, -2);
		 $i=$kwartaal;
		 switch ($i) {
		     case 1:
		         $datbegin = $jaar."-01-01";
		         $datend = date("Y-m-d", strtotime($datbegin));
		         $ChkQ = 'tob.ChkQ1';
		         break;
		     case 2:
		         $datend = date("Y-m-d", strtotime($jaar."-04-01"));
		         $ChkQ = 'tob.ChkQ2';
		         break;
		     case 3:
		         $datend = date("Y-m-d", strtotime($jaar."-07-01"));
		         $ChkQ = 'tob.ChkQ3';
		         break;
		     case 4:
		         $datend = date("Y-m-d", strtotime($jaar."-10-01"));
		         $ChkQ = 'tob.ChkQ4';
		         break;
		 }
	}
	return array($ChkQ, $datend);
}

//selecteer items te controleren uit gekozen kwartaal;
//selecteer items gecontroleerd uit voorgaande kwartalen die niet OK zijn.
//selecteer enkel hindernis; overige info via aparte queries ophalen
function getviewtobechecked($ChkQ, $datend) {
	$whereTerrein = getterreinid();

	$query1 = "select ts.Naam,tob.* ";
	$query1 .="from TblSections ts,TblObstacles tob ";
	$query1 .="where tob.Section_id = ts.Id and $ChkQ is true ";
	$query1 .="and ts.".$whereTerrein;
	$query1 .="union ";
	$query1 .="select ts.Naam,tob.* ";
	$query1 .="from TblSections ts,TblObstacles tob left join TblObstacleChecks tobc on (tob.id=tobc.obstacle_id) ";
	$query1 .="where tob.Section_id = ts.Id and $ChkQ is false ";
	$query1 .="and ts.".$whereTerrein;
	$query1 .="and DatCheck between DATE_ADD('$datend', INTERVAL -9 MONTH) and '$datend' ";
	$query1 .="and ChkSt is false ";

	return $query1;
}



?>
