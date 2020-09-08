<?php

class mod_header{

    public function __construct()
    {
        include_once("application/controller/Controller.php"); 
    }


    function getUserName($userid,$mysqli){
        $sql = "SELECT Email 
                FROM TblUsers 
                WHERE Id = '".$userid."' LIMIT 1";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();
        $stmt->bind_result($Email);
        $stmt->fetch();
        return $Email;
    }

    function getTerreinNaam($userid, $mysqli){
        //Get terreinnaam
        $qry2 = "SELECT tt.Terreinnaam 
                 from TblTerreinUsers ttu, TblTerrein tt 
                 where ttu.Terrein_id = tt.Id and
                       ttu.User_id = '$userid'
                ";
        $stmt2 = $mysqli->prepare($qry2);
        $stmt2->execute();    // Execute the prepared query.
        $stmt2->store_result();
        // get variables from result.
        $stmt2->bind_result($Terreinnaam);
        $stmt2->fetch();
        return $Terreinnaam;
    }

    function getNrOpenMessages($userid, $db){
        //get aantal openstaande berichten voor deze gebruiker
        $sqlaantal = "SELECT count(tm.Id) as aantal 
                      from TblMessages tm 
                      where tm.Id not in (select distinct Message_id from TblMessagesRead 
                                          where User_id = ".$userid.") 
                        and tm.Gepubliceerd = 1;";
        $STH = $db->query($sqlaantal);
        $STH->setFetchMode(PDO::FETCH_ASSOC);
        $row=$STH->fetch();
        return $row['aantal'];
    }

    function getOldestMessage($userid,$db){
        //haal oudste bericht op
        $sqloudste = "SELECT * 
                      from TblMessages tm 
                      where tm.Id not in (select Message_id from TblMessagesRead 
                                          where User_id = ".$userid.") 
                        and tm.Gepubliceerd = 1 
                      order by tm.Datum asc 
                      LIMIT 1;";
        $STH = $db->query($sqloudste);
        $STH->setFetchMode(PDO::FETCH_ASSOC);
        $row=$STH->fetch();

        $message = array();
        $message['Titel'] = $row['Titel'];
        $message['Datum'] = $row['Datum'];
        $message['Bericht'] = $row['Bericht'];
        $message['Messageid'] = $row['Id'];
        return $message;
    }
}

?>