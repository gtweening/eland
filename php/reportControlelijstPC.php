<?php

/**
 Gives an overview of all obstacles and some properties for a certain training location.
 Used by parcours commissie to verify safety of obstacles.
 
 copyright: 2018 Gerko Weening
 */

include_once "../inc/base.php";
include_once "../inc/functions.php";
include_once "../inc/queries.php";

sec_session_start();
if (isset($_SESSION['Terreinnaam'])){
    $Terreinnaam = $_SESSION['Terreinnaam'];
}else{
    $Terreinnaam = "";
}

//secure login
if(login_check($mysqli) == true) { 
?>

<!DOCTYPE html>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="../css/StyleSheetReportPC.css">
    <title>Controlelijst Parcours Commissie</title>
    
</head>

<body>
<div class="navbar">
   <a href="exportRptPC-RTF.php">Exporteer rapport naar RTF</a>   
</div>

<div class="main">
   <!--header-->
	<table width=100% >
		<tr>
			<td colspan=2 valign="top">	
				<h2>Controlelijst Parcours Comissie</h2>
			</td>
			<td rowspan=2 align="right">
				<img src="../img/LogoSBN.png" width="250" >
			</td>
		</tr>
		<tr>
			<td class="headerlocatie" width=15%>Trainingslocatie:</td>
			<td class="headerlocatie">
				<?php echo $Terreinnaam ?>
			</td>
		</tr>
	</table>
	<hr>
	
	<!--2nd part of header-->
	<table id="Table">
		<tr >		
			<td class="TopInfo">Plaats:</td>
			<td></td>
			<td class="TopInfo">Controleurs terrein:</td>
			<td width=40%></td>
		</tr>
		<tr>
			<td class="TopInfo">Trainingstijden:</td>
			<td></td>
			<td class="TopInfo">Controleurs SBN:</td>
			<td></td>
		</tr>
		<tr>	
			<td class="TopInfo">BHV / EHBO aanwezig tijdens trainingen:</td>
			<td class="TopInfo">Ja / Nee</td>
			<td class="TopInfo">Datum controle:	</td>
			<td></td>
		</tr>
		<tr>	
			<td></td>
			<td></td>
			<td class="TopInfo">Algemeen oordeel:	</td>
			<td></td>
		</tr>
		
	</table>
	
<?php


$whereTerrein = getterreinid();

//get materialtypes per material
$getMatType="select tm.id,concat('m_',tmt.Omschr) as MOmschr,1 as val
             from TblMaterials tm right join TblMaterialTypes tmt              
                  on tm.MaterialType_id = tmt.id 
             where tm.".$whereTerrein ;

//get materiaalomschr voor hoofdtouw en zekeringstouw
$getMainSafetyRope = "select Obstacle_id, max(if(Aantal like '%hoofdtouw%',Omschr,'')) as hoofdtouw, 
                                          max(if(Aantal like '%zekeringstouw%',Omschr,'')) as zekeringstouw  
                      from (select tom.Obstacle_id, tom.Aantal, tm.Omschr 
                            from TblObstacleMaterials tom inner join TblMaterials tm 
                              on tom.Material_id = tm.Id 
                      where tom.Aantal like '%hoofdtouw%' or tom.Aantal like 'zekeringstouw%') as t 
                      group by Obstacle_id";

//get obstacle id for all materials (outer join)
$getObstMat="select distinct tom.Obstacle_id as HId,mat.MOmschr, mat.val, tom.Aantal 
             from TblObstacleMaterials tom left join (".$getMatType.") as mat 
                  on mat.id=tom.Material_id 
             union
             select distinct tom.Obstacle_id as HId,mat.MOmschr, mat.val, tom.Aantal 
             from TblObstacleMaterials tom right join (".$getMatType.") as mat 
                  on mat.id=tom.Material_id ";


//get pivot script for materialtypes per material
$getPivotScript = "select GROUP_CONCAT(distinct 
                          concat('max(if(t.MOmschr = ''',t.MOmschr,''', t.val, NULL)) as 
                                 ',t.MOmschr)) as sconcat 
                   from (".$getObstMat.") as t ";
$STH = $db->query($getPivotScript);
$STH->setFetchMode(PDO::FETCH_ASSOC);
$row=$STH->fetch();
$sPivotScript=$row['sconcat'];

//get materialtypes per material in pivot table
$getMat="select t.HId, t.Aantal, ".$sPivotScript." 
         from (".$getObstMat.") as t 
         group by t.HId";

//join getObstMat with Obstacle info
$getObstMatInfo="select tob.Id, tob.Section_id, tob.Volgnr, tob.Omschr as HOmschr,
                        tob.DatCreate, tob.MaxH, tomt.*  
                 from TblObstacles tob left join (".$getMat.") as tomt 
                      on tob.Id=tomt.HId";

//get sectioninfo and materialtype per obstacle
$getSecObstMatInfo="select distinct concat(ts.Naam,' ',tobm.Volgnr) as SHId, tobm.* 
       from TblSections ts, (".$getObstMatInfo.") as tobm 
       where tobm.Section_id = ts.Id 
         and ts.".$whereTerrein;

//get sectioninfo, materialtype, max obstacle check per obstacle
$getObstChkInfoA="select tobm2.*, toc.DatCheck, toc.ChkSt 
       from (".$getSecObstMatInfo.") as tobm2 left join 
            (select max(Id),Obstacle_id,DatCheck,ChkSt 
             from TblObstacleChecks group by Obstacle_id) as toc 
                  on tobm2.Id=toc.Obstacle_id ";

//add main, safety rope to list
$getObstChkInfo = "select tobm3.*, msr.hoofdtouw, msr.zekeringstouw 
                   from (".$getObstChkInfoA.") as tobm3 left join 
                        (".$getMainSafetyRope.") as msr 
                        on tobm3.Id = msr.Obstacle_id ";
//echo $getObstChkInfo;

//materialnames are dynamic
//get materialnames out of columnnames
//put materialnames into variable
$i=0;
if ($result = $mysqli->query($getObstChkInfo)) {
	$finfo = $result->fetch_fields();
	$i=1;
	foreach ($finfo as $val) {
		if (strpos($val->name,"m_") !== false){
		 $slen=strlen($val->name);
       ${'m'.$i}= substr($val->name,2);
		 //count materialvariables
		 $i=$i+1;		
		}

	}
	$result->free();
}

?>

   <!--body of report-->
	<table width="100%">
		<tr>
      		<th align="center" rowspan=3>Id </th>
			<th align="center" rowspan=3>Omschrijving </th>
			<th align="center" rowspan=3>Hoofdtouw </th>
			<th align="center" rowspan=3>Zekeringstouw </th>
			<!-- -->
			<?php
			//counted one to many => correct
		    $i=$i-1;
			echo '<th align="center" colspan='.$i.'>Soort materiaal </th>';
			?>
			<th align="center" rowspan=3>Hoogte [m]</th>
			<th align="center" rowspan=3>Gecontroleerd </th>
			<th align="center" rowspan=3>status </th>
			<th align="center" colspan=5>Controle PC </th>
      	</tr>
	  	<tr >
			<!-- add dynamic materialheaders -->
			<?php
			for ($x=1; $x<=$i; $x++) {
				echo '<th class="matheader" align="left" rowspan=2><div><span>'.${'m'.$x}.'</span></div></th>';
			}
		    ?>
			<th align="center" rowspan=2>Opmerking </th>
			<th align="center" colspan=2>kleine rep. </th>
			<th align="center" colspan=2>Afgekeurd </th>
      </tr>
      <tr>

		  <th align="center">Ja </th>
		  <th align="center">Nee </th>
		  <th align="center">Ja </th>
		  <th align="center">Nee </th>
      </tr>
		<?php
		$STH = $db->query($getObstChkInfo);
		while($rows=$STH->fetch()){
		?>
      <tr>
			<td class="TableText" width="5%"><?php echo ($rows['SHId']); ?></td>
			<td class="TableText" width="17%"><?php echo ($rows['HOmschr']); ?></td>
			<td class="TableText" width="7%"><?php echo ($rows['hoofdtouw']) ; ?></td>
			<td class="TableText" width="7%"><?php echo ($rows['zekeringstouw']) ; ?></td>
			<!-- add dynamic material -->
			<?php
			for ($x=1; $x<=$i; $x++) {
                $m = "m_".${'m'.$x};
				$val = $rows[$m];
            if ($val == 1){
				   $val = "*";
            } 
				echo '<td class="matText">'.$val.'</td>';
			}
			?>
			<td class="TableText"><?php echo ($rows['MaxH']); ?></td>
			<td class="TableText"><?php echo ($rows['DatCheck']); ?></td>
			<td class="matText" width ="5%">
				 <?php if($rows['ChkSt']== FALSE){?>
                      <img src="../img/warning.jpeg" width="20" height="20"><?php
                   }else{?>
                      <img src="../img/ok.jpeg" width="20" height="20"><?php
                   }; ?></td>
            <td width="25%"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
		</tr>
		<?php
		} //end while
		
		//close connection
      $db = null;
		?>
	</table>
</div>

</body>
</html>

<?php
} else { ?>
<br>
U bent niet geautoriseerd voor toegang tot deze pagina. <a href="../index.php">Inloggen</a> alstublieft.

<?php
}
?>
