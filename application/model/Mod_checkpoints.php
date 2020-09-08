<?php
        
class mod_checkpoints{

    function getCheckpoints($Terreinid, $db){
        $STH = $db->query('SELECT * from TblCheckpoints
                           where Terrein_id ='.$Terreinid.'
                           order by Id');

        $STH->setFetchMode(PDO::FETCH_ASSOC);
        return $STH;
    }

    function addCheckpoint($terreinid, $checkpoint, $db){
        $STH = $db->prepare("INSERT INTO TblCheckpoints (Omschr, Terrein_id) 
                             VALUES ('$checkpoint', $terreinid)");
        $STH->execute();
    }

    function delCheckpoints($selected, $db){
        foreach($selected as $val){
            $ids[] = (int) $val;
            //controller of voor deze sectie nog hindernissen bestaan
            $STH1=$db->prepare("SELECT distinct Checkpoint_id from TblObstacleCheckpoints");
            $STH1->execute();

            while($rows = $STH1->fetch(PDO::FETCH_ASSOC)){
                if($rows['Checkpoint_id']==$val){
                    $_SESSION['errormessage'] = "Dit controlepunt wordt gebruikt voor een hindernis.\nVerwijderen niet mogelijk!";
                    return false;
                }
            }
        }

        $ids = implode("','", $ids);
        $STH = $db->prepare("DELETE FROM TblCheckpoints WHERE Id IN ('".$ids."')");
        $STH->execute();

    }

    function editCheckpoint($sId, $checkpoint, $db){
        $STH = $db->prepare("UPDATE TblCheckpoints
                             SET Omschr = '".$checkpoint."'
                             WHERE Id = $sId");
        $STH->execute();
    }

}

?>
