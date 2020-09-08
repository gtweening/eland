<?php
        
class mod_messages{

    function getMessages($db){
        $STH = $db->query('SELECT * from TblMessages order by Id');
        $STH->setFetchMode(PDO::FETCH_ASSOC);
        return $STH;
    }

    function addMessage($datum, $titel, $bericht, $db){
        $STH = $db->prepare("INSERT INTO TblMessages (Datum, Titel, Bericht) 
                             VALUES ('$datum','$titel','$bericht')");
        $STH->execute();
    }

    function delMessages($ids, $db){
        $STH = $db->prepare("DELETE FROM TblMessages WHERE Id IN ('".$ids."')");
        $STH->execute();
    }

    function publishMessages($ids, $db){
        $STH = $db->prepare("Update TblMessages set Gepubliceerd = 1 WHERE Id IN ('".$ids."')");
        $STH->execute();
    }
}

?>