<?php
        
class mod_obstacles{

    function getObstacle($id, $db){
        if ($id != ''){
            $sql = 'SELECT * from TblObstacles where Id = '.$id;
            $STH=$db->query($sql);
            $STH->setFetchMode(PDO::FETCH_ASSOC);
            return $STH;

        }else{
            return FALSE;
        }
    }

    function getObstacleBySectionId($vsectionid, $db){
        $STH1 = $db->query('SELECT distinct Volgnr from TblObstacles
                            where Section_id = '.$vsectionid);        
        $STH1->setFetchMode(PDO::FETCH_ASSOC);
        return $STH1;
    }
   
    function getObstaclesBySection($terreinid, $vsectionname, $db){
        $STH = $db->query('SELECT * from TblObstacles
                            where section_id = (select Id 
                                                from TblSections 
                                                where Naam = "'.$vsectionname .'" 
                                                  and Terrein_id = "'.$terreinid.'") 
                                                order by Volgnr');
        $STH->setFetchMode(PDO::FETCH_ASSOC);

        return $STH;

    }

    function addObstacle($vsectionid, $volgnr, $omschr, $maxh, $datcreate, $obsSec, $db){
        $STH = $db->prepare("INSERT INTO TblObstacles (Section_id, Volgnr, Omschr, MaxH, DatCreate, IndSecure) 
                            VALUES ('$vsectionid', '$volgnr', '$omschr', '$maxh', '$datcreate', '$obsSec' )");
		$STH->execute();
    }

    function delObstacles($ids, $db){
        $STH = $db->prepare("DELETE FROM TblObstacles WHERE Id IN ('".$ids."')");
        $STH->execute();

    }

    function editObstacle($Id, $volgnr, $omschr, $maxh, $datcreate, $obsSec, $db){
        $STH = $db->prepare("UPDATE TblObstacles
                            SET Volgnr ='$volgnr',
                                Omschr='$omschr',
                                MaxH='$maxh',
                                DatCreate='$datcreate',
                                IndSecure='$obsSec'
                            WHERE Id = $Id");
        $STH->execute(); 
    }
    
    function editObstacleCalenderOn($ids, $kwartaal, $db){
        $q = 'ChkQ'.$kwartaal;

        $STH = $db->prepare("UPDATE TblObstacles 
                            SET ".$q." = 1 WHERE Id IN ('".$ids."')");
        $STH->execute(); 
    }

    function editObstacleCalenderNotOn($ids, $kwartaal, $db){
        $q = 'ChkQ'.$kwartaal;

        $STH = $db->prepare("UPDATE TblObstacles 
                            SET ".$q." = 0 WHERE Id not IN ('".$ids."')");
        $STH->execute(); 
    }

    function editObstacleCalenderOff($ids, $kwartaal, $db){
        $q = 'ChkQ'.$kwartaal;

        $STH = $db->prepare("UPDATE TblObstacles 
                            SET ".$q." = 0 WHERE Id IN ('".$ids."')");
        $STH->execute(); 
    }


    function deleteObstacleImg($id, $obsPath, $img, $db){
        //bestandsnaam uit db verwijderen
        //enkel als er een bestand aanwezig is.
        $sqlUpdate = "Update TblObstacles Set ImgPath = '' where Id IN ('".$id."')";
        $STH1=$db->prepare($sqlUpdate);
        $STH1->execute();
    
        //bestand verwijderen van server
        $fileName = $obsPath.$img;  

        unlink($fileName);
        //echo "Bestand succesvol verwijderd.";
    }

    //#################################################################################
      
    function getObstacleMaterials($id, $db){
        //hindernismaterialen ophalen
        $STH = $db->query('SELECT tom.Id as tomId, tm.Omschr, tom.Aantal, tm.*, tmt.Omschr as tmtomschr  
                            from TblObstacleMaterials tom, TblMaterials tm, TblMaterialTypes tmt 
                            where tom.Material_Id = tm.Id 
                              and tmt.Id = tm.MaterialType_Id 
                              and tom.Obstacle_id = '.$id .' ');
        $STH->setFetchMode(PDO::FETCH_ASSOC);
     
        return $STH;
    }

    function addObstacleMaterials($vhindId, $item, $db){
        $query = "INSERT INTO TblObstacleMaterials (Obstacle_id, Material_id) VALUES " ;
        $query .= "(".$vhindId.", ".$item.")";

        $stmt = $db->prepare($query);
        $stmt->execute();
    }

    function delObstacleMaterials($ids, $db){
        $STH = $db->prepare("DELETE FROM TblObstacleMaterials WHERE Id IN ('".$ids."')");          
        $STH->execute();
    }

    function updateObstacleMaterial($sId, $sOmschr, $db){
        $STH = $db->prepare("UPDATE TblObstacleMaterials SET Aantal = '".$sOmschr."' WHERE Id = $sId");          
        $STH->execute();
    }
   
    //#################################################################################

    function getObstacleCheckpoints($id, $db){
        //hindernismaterialen ophalen
        $STH = $db->query('SELECT toc.id as tocId, tc.Omschr 
                            from TblObstacleCheckpoints toc, TblCheckpoints tc 
                            where toc.Checkpoint_id = tc.Id and toc.Obstacle_id = '.$id .' ');
        $STH->setFetchMode(PDO::FETCH_ASSOC);

        return $STH;
    }
     
    function addObstacleCheckpoints($id, $item, $db){
        $query = "INSERT INTO TblObstacleCheckpoints (Obstacle_id, Checkpoint_id) VALUES " ;           
        $query .= "(".$id.", ".$item.")";

        $stmt = $db -> prepare($query);
        $stmt->execute();
    }

    function delObstacleCheckpoints($ids, $db){
        $STH = $db->prepare("DELETE FROM TblObstacleCheckpoints WHERE Id IN ('".$ids."')");
        $STH->execute();
    }
    

    //#################################################################################

    function getObstacleChecks($id, $db){
        //hindernismaterialen ophalen
        $STH = $db->query('SELECT * 
                           FROM TblObstacleChecks 
                           WHERE Obstacle_id = '.$id.' ' );

        $STH->setFetchMode(PDO::FETCH_ASSOC);

        return $STH;
    }

    function getOneObstacleCheck($obstacleid, $id, $db){
        //hindernismaterialen ophalen
        $STH = $db->query('SELECT * 
                           FROM TblObstacleChecks 
                           WHERE Obstacle_id = '.$obstacleid.' and Id = '.$id.' ' );

        $STH->setFetchMode(PDO::FETCH_ASSOC);

        return $STH;
    }

    function getObstacleChecksPeriod($obstacleid, $datend, $db){
        $query4 = "select tob.*, tobc.DatCheck, tobc.ChkSt, tobc.CheckedBy,tobc.Note ";
        $query4 .="from TblObstacles tob left join TblObstacleChecks tobc on (tob.id=tobc.obstacle_id) ";
        $query4 .= "where tob.Id =". $obstacleid." and ";
        $query4 .= "DatCheck between '$datend' and DATE_ADD('$datend', INTERVAL +3 MONTH) ";

        $STH = $db->query($query4);

        $STH->setFetchMode(PDO::FETCH_ASSOC);
        return $STH;
    }

    function delObstacleCheck($ids, $db){
        $STH = $db->prepare("DELETE FROM TblObstacleChecks WHERE Id IN ('".$ids."')");
        $STH->execute();
    }

    function addObstacleCheck($obstacleid, $datum, $status, $controleur, $note, $db){
        $query = "INSERT INTO TblObstacleChecks (Obstacle_id, DatCheck, ChkSt, CheckedBy, Note) VALUES " ;           
        $query .= " ('$obstacleid', '$datum' , '$status', '$controleur', '$note') ";

        $stmt = $db -> prepare($query);
        $stmt->execute();
    }

    function updateObstacleCheck($id, $datum, $status, $controleur, $note, $db){
        $STH = $db->prepare("UPDATE TblObstacleChecks 
                            SET DatCheck = '$datum',
                                ChkSt =  '$status', 
                                CheckedBy = '$controleur',
                                Note = '$note'    
                            WHERE Id = $id ");          
        $STH->execute();
    }

    function delObstacleCheckForPeriod($ids, $datend, $db){
        $query5 = "DELETE FROM TblObstacleChecks ";
        $query5 .= "WHERE Obstacle_id IN ('".$ids."') and ";
        $query5 .= "DatCheck between '".$datend."' and DATE_ADD('".$datend."', INTERVAL +3 MONTH) ";
        $STH = $db->prepare($query5);
        $STH->execute();
    }
    



    //#################################################################################

    function getObstacleChecksCalender($terreinid, $db){
        //hindernis kalender ophalen
        $STH = $db->query('SELECT tos.*, tss.naam 
                        FROM TblObstacles as tos, TblSections as tss 
                        WHERE tos.section_id = tss.id 
                          and tss.Terrein_id = '.$terreinid.' 
                        ORDER BY naam,Volgnr');

        $STH->setFetchMode(PDO::FETCH_ASSOC);
        return $STH;
    }

    
    //selecteer items te controleren uit gekozen kwartaal;
    //selecteer items gecontroleerd uit voorgaande kwartalen die niet OK zijn.
    //selecteer enkel hindernis; overige info via aparte queries ophalen
    function getviewtobechecked($ChkQ, $datend, $db) {
        $Terreinid="";
        if ($_SESSION['Terreinid']==0) {
            $whereTerrein = "Terrein_id is null ";
        } else {
            $Terreinid = $_SESSION['Terreinid'];
            $whereTerrein = "Terrein_id = ".$Terreinid." ";
        }

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

        $STH = $db->query($query1);

        $STH->setFetchMode(PDO::FETCH_ASSOC);
        return $STH;
    }

    function getAllObstaclesForTerrain($db) {
        $Terreinid="";
        if ($_SESSION['Terreinid']==0) {
            $whereTerrein = "Terrein_id is null ";
        } else {
            $Terreinid = $_SESSION['Terreinid'];
            $whereTerrein = "Terrein_id = ".$Terreinid." ";
        }

        $query1 ="select distinct ts.Naam,tob.* ";
        $query1 .="from TblSections ts,TblObstacles tob left join TblObstacleChecks tobc on (tob.id=tobc.obstacle_id) ";
        $query1 .="where tob.Section_id = ts.Id ";
        $query1 .="and ts.".$whereTerrein;
        $query1 .="order by ts.Naam, tob.Volgnr ";

        $STH = $db->query($query1);

        $STH->setFetchMode(PDO::FETCH_ASSOC);
        return $STH;
    }

}
?>