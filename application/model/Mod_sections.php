<?php
        
class mod_sections{

    function getSections($Terreinid, $sort, $db){
        $orderby = '';
        if (isset($sort)){
            if ($sort == 'a'){
                $orderby=" order by Naam asc";
            }else{
                $orderby=" order by Naam desc";
            }
        }

        $STH = $db->query('SELECT * from TblSections
                           where Terrein_id ='.$Terreinid.$orderby.'
                          ');

        $STH->setFetchMode(PDO::FETCH_ASSOC);
        return $STH;
    }

    function getSectionByName($terreinid, $sectienaam, $db){
        $STH = $db->query('SELECT * 
                       from TblSections 
                       where Naam ="'.$sectienaam.'"
                             and Terrein_id = "'.$terreinid.'" ');
        $STH->setFetchMode(PDO::FETCH_ASSOC);
        return $STH;

    }

    function getSectionById($Id, $db){
        $STH = $db->query('SELECT * 
                       from TblSections 
                       where Id ="'.$Id.'"');
        $STH->setFetchMode(PDO::FETCH_ASSOC);
        return $STH;

    }

    function getSectionByOmschr($terreinid, $sectieomschr, $db){
        $STH = $db->query('SELECT * 
                       from TblSections 
                       where Omschr ="'.$sectieomschr.'"
                             and Terrein_id = "'.$terreinid.'" ');
        $STH->setFetchMode(PDO::FETCH_ASSOC);
        return $STH;

    }

    function addSection($terreinid, $sectienaam, $sectieomschr, $db){
        $STH = $db->prepare("INSERT INTO TblSections (Naam, Omschr, Terrein_id) 
                             VALUES ('$sectienaam', '$sectieomschr', $terreinid)");
        $STH->execute();
    }

    function delSection($selected, $db){
        foreach($selected as $val){
            $ids[] = (int) $val;
            //controller of voor deze sectie nog hindernissen bestaan
            $STH1=$db->prepare("Select distinct Section_id from TblObstacles");
            $STH1->execute();

            while($rows = $STH1->fetch(PDO::FETCH_ASSOC)){
                if($rows['Section_id']==$val){
                    $_SESSION['errormessage'] = "Er zijn hindernissen in deze sectie.<br>Verwijderen niet mogelijk!";
                    return false;	
                }
            }
        }

        $ids = implode("','", $ids);
        $STH = $db->prepare("DELETE FROM TblSections WHERE Id IN ('".$ids."')");
        $STH->execute();

    }

    function editSection($Id, $sNaam, $sOmschr, $db){
        $STH = $db->prepare("UPDATE TblSections
                             SET Omschr = '".$sOmschr."' , Naam = '".$sNaam."' 
                             WHERE Id = $Id");
        $STH->execute();  
    }

}

?>