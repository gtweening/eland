<?php
/**
 * 5th update eland tabellen 
 *
 * bevat enkel de toevoegingen van version 6.0
 * voorwaarde: _createelandtables is uitgevoerd.
 * 
 * Vergroot controle omschrijving
 */

/** Copyright 2018 Gerko Weening */

include_once "../inc/base.php";

try {  
    
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // version 6.0
    // update eland tabellen
    //
    // vergroot controle omschrijving
    
    //update existing tables
    $sqlUpdateTblChk = "Alter table TblCheckpoints
                        modify column Omschr char(75) ";
        
    //6.0 addition
    $db->exec($sqlUpdateTblChk);
    
    echo "Tabellen succesvol aangemaakt en verandert.";
    echo "<br>";


}
catch(PDOException $e) {
    echo $e->getMessage();
}


$db = null;

?>