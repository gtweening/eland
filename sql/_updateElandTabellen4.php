<?php
/**
 * 4th update eland tabellen 
 *
 * bevat enkel de toevoegingen van version 5.0
 * voorwaarde: _createelandtables20 is uitgevoerd.
 * 
 * Voegt twee tabellen toe tbv versturen berichten
 */

/** Copyright 2018 Gerko Weening */

include_once "../inc/base.php";

try {  
    
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // version 5.0
    // update eland tabellen
    //
    // voeg twee tabellen toe tbv versturen/bekijken berichten
    
    //drop existing tables
    $sqlDropTblMes = "DROP TABLE IF EXISTS TblMessages";
    $sqlDropTblMesRd = "DROP TABLE IF EXISTS TblMessagesRead";
    
    $sqlTblMessages = "CREATE TABLE `TblMessages` (
                      `Id` int(11) NOT NULL AUTO_INCREMENT,
                      `Datum` date NOT NULL,
                      `Titel` char(50) NOT NULL,
                      `Bericht` text CHARACTER SET utf8,
                      `Gepubliceerd` tinyint(1) DEFAULT NULL,
                       PRIMARY KEY (`Id`) 
                      ) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=latin1 ";
    
    $sqlTblMessagesRd = "CREATE TABLE `TblMessagesRead` (
                      `Id` int(11) NOT NULL AUTO_INCREMENT,
                      `Message_id` int(11) NOT NULL, 
                      `User_id` int(11) NOT NULL, 
                      `Datum` date NOT NULL,
                       PRIMARY KEY (`Id`)
                      ) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=latin1 ";
    
    
    //5.0 addition
    $db->exec($sqlDropTblMes);
    $db->exec($sqlDropTblMesRd);
    $db->exec($sqlTblMessages);
    $db->exec($sqlTblMessagesRd);
    
    echo "Tabellen succesvol aangemaakt en verandert.";
    echo "<br>";


}
catch(PDOException $e) {
    echo $e->getMessage();
}


$db = null;

?>
