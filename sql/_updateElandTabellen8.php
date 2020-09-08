<?php
/**
 * 8th update eland tabellen 
 * bevat enkel de toevoegingen van version 4.0
 * voorwaarde: _createelandtables20 is uitgevoerd.
 * 
 * Voegt kolom toe aan TblUser tbv ww reset
 */

/** Copyright 2019 Gerko Weening */

include_once "../inc/base.php";

try {  
    
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // version 9.0
    // update eland tabellen
    //
    // kolom toevoegen
    $sqlTblUsers_alter1 = "alter table TblUsers
                            add temppwd varchar(128), 
                            add validuntil int(8),
                            add ema varchar(100) " ;
    
    //8.0 addition
    $db->exec($sqlTblUsers_alter1);
    
    echo "Tabellen succesvol aangemaakt en verandert.";
    echo "<br>";

}
catch(PDOException $e) {
    echo $e->getMessage();
}


$db = null;

?>
