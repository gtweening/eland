<?php
/**
 * 3th update eland tabellen 
 *
 * bevat enkel de toevoegingen van version 4.0
 * voorwaarde: _createelandtables20 is uitgevoerd.
 * 
 * Voegt kolom toe tbv materiaaltypen
 * voegt kolom materiaaltype toe aan materialen
 * voegt kolommen toe aan tblObstacle
 * maakt het mogelijk pc lid rapportage gemaakt kan worden
 */

/** Copyright 2017 Gerko Weening */

include_once "../inc/base.php";

try {  
    
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // version 4.0
    // update eland tabellen
    //
    // voeg tabel toe tbv materiaaltypen
    // voeg kolom materiaaltype toe aan materialen
    // voeg kolommen toe aan Obstacles
    // maakt het mogelijk pc lid rapportage gemaakt kan worden
    
    $sqlTblMaterials_alter3 = "alter table TblMaterials
                           add MaterialType_id int(11) ";
        
    $sqlTblObstacles_alter1 = "alter table TblObstacles
                            add MaxH decimal(2,1),
                            add DatCreate date 
                            add IndSecure int(1) ";
    
    $sqlTblMaterialTypes = "CREATE TABLE `TblMaterialTypes` (
                          `Id` int(11) NOT NULL AUTO_INCREMENT,
                          `Omschr` char(50) DEFAULT NULL,
                          `Terrein_id` int(11) DEFAULT NULL,
                          PRIMARY KEY (`Id`),
                          UNIQUE KEY `Omschr` (`Omschr`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=latin1 ";
    
    
    //4.0 addition
    $db->exec($sqlTblMaterials_alter3);
    $db->exec($sqlTblObstacles_alter1);
    $db->exec($sqlTblMaterialTypes);
    
    echo "Tabellen succesvol aangemaakt en verandert.";
    echo "<br>";


}
catch(PDOException $e) {
    echo $e->getMessage();
}


$db = null;

?>
