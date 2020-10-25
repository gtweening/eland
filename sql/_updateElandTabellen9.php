<?php
/**
 * 9th update eland tabellen 
 * bevat enkel de toevoegingen van version 4.0
 * voorwaarde: _createelandtables30 is uitgevoerd en updates 4 tm 8.
 * 
 * Voegt kolom toe aan TblUser tbv ww reset
 */

/** Copyright 2019 Gerko Weening */

include_once "../application/config/base.php";

try {  
    
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // version 10.0
    // update eland tabellen
    //
    // kolom toevoegen
    $sqlTblUsers_alter1 = "alter table TblUsers
                            add role varchar(3) " ;
    
    //10.0 addition
    $db->exec($sqlTblUsers_alter1);
    
    echo "Tabellen succesvol aangemaakt en verandert.";
    echo "<br>";

}
catch(PDOException $e) {
    echo $e->getMessage();
}


$db = null;

?>
