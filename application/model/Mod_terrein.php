<?php
        
class mod_terrein{

    function getTerreinPic($Terreinid, $db){
        //terreinafbeelding ophalen
        $STH = $db->query('SELECT * from TblTerrein WHERE Id ="'.$Terreinid.'"');
        $STH->setFetchMode(PDO::FETCH_ASSOC);
        $row=$STH->fetch();

        return $row['ImgFile'];

    }

    //ophalen terreinnaam obv terreinid
    function getTerreinnaam($db,$terreinId){
        $STH = $db->prepare('SELECT * from TblTerrein
                            where Id = '.$terreinId.'  
                            ');
        $STH->execute();
        $row = $STH->fetch();
        return htmlentities($row['Terreinnaam']);
    }

    function editTerreinnaam($db, $terreinId, $terreinNaam){
        $sql = "UPDATE TblTerrein SET Terreinnaam = '".$terreinNaam."' ";    
        $sql.= "WHERE Id = $terreinId";  
                               
        $STH = $db->prepare($sql);
        $STH->execute();
    
    }

    function getTerreinen($db){
        $STH = $db->query('SELECT * from TblTerrein order by Id');
        $STH->setFetchMode(PDO::FETCH_ASSOC);
        return $STH;
    }


    function addTerrein($terreinnaam, $db){
        $STH = $db->prepare("INSERT INTO TblTerrein (Terreinnaam) VALUES ('$terreinnaam')");
        $STH->execute();
    }

    function getTerreinIdbyName($terreinnaam, $db){
        $STH = $db->query("SELECT Id FROM TblTerrein WHERE Terreinnaam = '".$terreinnaam."'");
        $STH->setFetchMode(PDO::FETCH_ASSOC);
        $row   = $STH->fetch();
        $value = $row['Id'];
        return $value;
    }

    function delTerreinen($ids, $db){
        $STH = $db->prepare("DELETE FROM TblTerrein WHERE Id IN ('".$ids."')");
        $STH->execute();
    }

    function getTerreinUsers($userid, $db){
        $STH = $db->query('SELECT ttu.*, tt.Terreinnaam, tu.Email
                            from TblTerreinUsers ttu, TblTerrein tt, TblUsers tu
                            where ttu.Terrein_id = tt.Id 
                            and ttu.User_id = tu.Id 
                            and ttu.User_id = "'.$userid.'" 
                            order by tu.Id');

        $STH->setFetchMode(PDO::FETCH_ASSOC);
        return $STH;
    }
    
    function getTerreinidbyId($ids, $db){
        $STH = $db->query("Select Terrein_id FROM TblTerreinUsers WHERE Id IN ('".$ids."')");
        $STH->setFetchMode(PDO::FETCH_ASSOC);

        while($rows=$STH->fetch()){
            $Terreinid = htmlentities($rows['Terrein_id']);
        }

        return $Terreinid;
    }
   
    
}

?>