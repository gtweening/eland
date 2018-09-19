<?php
/**
 * 6th update eland tabellen 
 *
 * bevat enkel de toevoegingen van version 7.0
 * voorwaarde: _updateElandTabellen3 is uitgevoerd.
 * 
 * Verwijdert tabel
 * Voegt tabel toe
 */

/** Copyright 2017 Gerko Weening */

include_once "../inc/base.php";

try {  
    
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // version 7.0
    // update eland tabellen
    //
    // unique was onjuist in TblMaterialTypes
    // deze tabel tot nog toe nog niet gebruikt
    
    $sqlDropTblMatTyp = "DROP TABLE IF EXISTS TblMaterialTypes";
        
    $sqlTblMaterialTypes = "CREATE TABLE `TblMaterialTypes` (
                          `Id` int(11) NOT NULL AUTO_INCREMENT,
                          `Omschr` char(50) DEFAULT NULL,
                          `Terrein_id` int(11) DEFAULT NULL,
                          PRIMARY KEY (`Id`),
                          UNIQUE KEY `Omschr` (`Omschr`,`Terrein_id`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=latin1 ";
    
    
    //7.0 addition
    $db->exec($sqlDropTblMatTyp);
    $db->exec($sqlTblMaterialTypes);
    
    echo "Tabellen succesvol aangemaakt en verandert.";
    echo "<br>";


}
catch(PDOException $e) {
    echo $e->getMessage();
}


$db = null;

?>
