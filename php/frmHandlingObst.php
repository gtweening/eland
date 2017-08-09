<html>
    <head>
        <script type="text/javascript">
            function editObstacleFunction(obstacleOmschr, obstacleVolgnr, ids, sectionNaam){
                var HindernisVolgnr=prompt("Verander het volgnummer van de hindernis",obstacleVolgnr);
                var HindernisOmschr=prompt("Verander de hindernisomschrijving",obstacleOmschr);
                if (HindernisVolgnr!=null){
                    window.location.href = "obstaclesSection.php?hindVolgnr=" + HindernisVolgnr + "&hindOmschr="+HindernisOmschr + "&hindId="+ids + "&sectieNaam="+sectionNaam;
                }
            }
        </script>
    </head>
</html>

<?php
include_once "../inc/base.php";
$tbl_obstacles="TblObstacles"; // Table name
$STH="";
$vsectionid = $_POST['sectionId'];
$vsectionname = $_POST['sectionName'];

//var_dump($_POST);
// Check if button isactive, start this
if(isset($_POST['delObstacle'])){
    //var_dump($_POST);
    //$del_id = $_POST['checkbox'];
    //print_r($_POST['checkbox']);   
    if(!empty($_POST['checkbox'])){
        foreach($_POST['checkbox'] as $val){
            $ids[] = (int) $val;
        }
        $ids = implode("','", $ids);
        $STH = $db->prepare("DELETE FROM $tbl_obstacles WHERE Id IN ('".$ids."')");
        $STH->execute();
    }else{
		echo '<script> alert("Er is niets geselecteerd om te verwijderen!"); </script>';
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=obstaclesSection.php?sectie=".$vsectionname."\">";
	 }
    // if successful redirect to delete_multiple.php
    if($STH){
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=obstaclesSection.php?sectie=".$vsectionname."\">";
    }
}else if(isset($_POST['addObstacle'])){      
    if(is_numeric($_POST['volgnr'])){
        //check if volgnr already exists
		  $STH1 = $db->query('select distinct Volgnr from TblObstacles');        
		  $STH1->setFetchMode(PDO::FETCH_ASSOC);
		  while($rows=$STH1->fetch()){
            if($_POST['volgnr'] == $rows['Volgnr']){
					echo '<script> alert("Er is al een hindernis met dit volgnummer!"); </script>';
					echo "<meta http-equiv=\"refresh\" content=\"0;URL=obstaclesSection.php?sectie=".$vsectionname."\">";
				   exit;
				}
        }
		  //Volgnr does not exist => insert
        $STH = $db->prepare("INSERT INTO $tbl_obstacles (Section_id, Volgnr, MaxH, DatCreate, 
                             Omschr) VALUES
        ('$vsectionid', '$_POST[volgnr]' , '$_POST[maxH]','$_POST[datCreate]',
         '$_POST[hindernisOmschr]')");
        $STH->execute();
    }else{
		echo '<script> alert("De hindernis heeft geen nummeriek volgnummer!"); </script>';
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=obstaclesSection.php?sectie=".$vsectionname."\">";
	 }
    // if successful redirect to delete_multiple.php
    if($STH){
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=obstaclesSection.php?sectie=".$vsectionname."\">";
    }
}else if(isset($_POST['editObstacle'])){
    if(!empty($_POST['checkbox'])){
        foreach($_POST['checkbox'] as $val){
            $ids[] = (int) $val;
        }
        $ids = implode("','", $ids);
       $STH = $db->query('select * FROM '.$tbl_obstacles.' WHERE Id = '.$ids.'');
       $STH->setFetchMode(PDO::FETCH_ASSOC);
       $row=$STH->fetch();
       $obstacleOmschr=$row['Omschr'];
       $obstacleVolgnr=$row['Volgnr'];
       //call jscript
       echo '<script> editObstacleFunction("'.$obstacleOmschr.'","'.$obstacleVolgnr.'", "'.$ids.'", "'.$vsectionname.'"); </script>';   
    }else{
		echo '<script> alert("Er is niets geselecteerd om te bewerken!"); </script>';
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=obstaclesSection.php?sectie=".$vsectionname."\">";
	 }
}
?>