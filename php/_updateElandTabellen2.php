<?php
/**
 * update eland tabellen 2
 *
 * bevat aanpassing aan materials tabel tbv vastleggen per terrein
 */

/** Copyright 2017 Gerko Weening */

include_once "../inc/base.php";

try {  
    
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    $sqlTblMaterials_alter2 = "alter table TblMaterials
   			       drop index Omschr ";
    
    $sqlTblMaterials_alter3 = "alter table TblMaterials
			       add unique key Omschr (Omschr,Terrein_id) ";
    
    $db->exec($sqlTblMaterials_alter2);
    $db->exec($sqlTblMaterials_alter3);
    

    echo "Tabellen succesvol aangemaakt en verandert.";
    echo "<br>";


}
catch(PDOException $e) {
    echo $e->getMessage();
}


$db = null;

?>
