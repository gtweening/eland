<?php
/**
 * update eland tabellen 1
 *
 * Voegt tabel toe tbv Terrein
 * Voegt kolommen toe tbv Terrein
 * Dit maakt het mogelijk dat 1 gebruiker meerdere terreinen beheert.
 * Dit maakt het mogelijk dat het programma als SAAS gaat werken.
 */

/** Copyright 2016 Gerko Weening */

include_once "../inc/base.php";

try {  
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


//table structure
$sqlTblTer = "CREATE TABLE TblTerrein (
  Id int(11) NOT NULL AUTO_INCREMENT,
  Terreinnaam char(50) NOT NULL,
  PRIMARY KEY (Id),
  UNIQUE KEY Terreinnaam (Terreinnaam)
) ";

$sqlTblTUs = "CREATE TABLE TblTerreinUsers (
  Id int(11) NOT NULL AUTO_INCREMENT, 
  User_id int(11) NOT NULL, 
  Terrein_id int(11) NOT NULL, 
  PRIMARY KEY (Id) 
)";

$sqlTblMat = "Alter TABLE TblMaterials 
  add Terrein_id int(11) 
 ";

$sqlTblSec = "Alter TABLE TblSections 
  add Terrein_id int(11) 
 ";

$sqlTblChk = "Alter TABLE TblCheckpoints 
  add Terrein_id int(11) 
 ";


// use exec() because no results are returned
    $db->exec($sqlTblTer);
    $db->exec($sqlTblTUs);
    $db->exec($sqlTblChk);
    $db->exec($sqlTblMat);
    $db->exec($sqlTblSec);
    echo "Tabellen succesvol aangepast.";
    echo "<br>";


}
catch(PDOException $e) {
    echo $e->getMessage();
}


$db = null;

?>
