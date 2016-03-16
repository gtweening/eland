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
    }
    // if successful redirect to delete_multiple.php
    if($STH){
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=obstaclesSection.php?sectie=".$vsectionname."\">";
    }
}else if(isset($_POST['addObstacle'])){      
    if(!empty($_POST['volgnr'])){
        
        $STH = $db->prepare("INSERT INTO $tbl_obstacles (Section_id, Volgnr, Omschr) VALUES
        ('$vsectionid', '$_POST[volgnr]' , '$_POST[hindernisOmschr]')");
        $STH->execute();
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
    }
}
?>
