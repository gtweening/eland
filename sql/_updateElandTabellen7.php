<?php
/**
 * 7th update eland tabellen 
 * bevat enkel de toevoegingen van version 4.0
 * voorwaarde: _createelandtables30 is uitgevoerd.
 * 
 * Voegt kolom toe aan TblTerrein tbv vastleggen overzichtsplaatje
 */

/** Copyright 2019 Gerko Weening */

include_once "../inc/base.php";

try {  
    
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // version 8.0
    // update eland tabellen
    //
    // kolom toevoegen
    $sqlTblTerrein_alter1 = "alter table TblTerrein
                            add ImgFile varchar(120) " ;
    
    //8.0 addition
    $db->exec($sqlTblTerrein_alter1);
    
    echo "Tabellen succesvol aangemaakt en verandert.";
    echo "<br>";

}
catch(PDOException $e) {
    echo $e->getMessage();
}


$db = null;

?>
