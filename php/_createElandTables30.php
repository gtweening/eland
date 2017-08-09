<?php
/**
 * installeren eland tabellen
 */

/** Copyright 2013 Gerko Weening */

include_once "../inc/base.php";

try {  
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//drop existing tables
$sqlDropTblChe = "DROP TABLE IF EXISTS TblCheckpoints;";
$sqlDropTblMat = "DROP TABLE IF EXISTS TblMaterials";
$sqlDropTblObsCp = "DROP TABLE IF EXISTS TblObstacleCheckpoints";
$sqlDropTblObsChe = "DROP TABLE IF EXISTS TblObstacleChecks";
$sqlDropTblObsMat = "DROP TABLE IF EXISTS TblObstacleMaterials";
$sqlDropTblObs = "DROP TABLE IF EXISTS TblObstacles";
$sqlDropTblSec = "DROP TABLE IF EXISTS TblSections";
$sqlDropTblUse = "DROP TABLE IF EXISTS TblUsers";
$sqlDropTblLogin = "DROP TABLE IF EXISTS login_attempts";

//table structure
$sqlTblChe = "CREATE TABLE TblCheckpoints (
  Id int(11) NOT NULL AUTO_INCREMENT,
  Omschr char(50) DEFAULT NULL,
  PRIMARY KEY (Id),
  UNIQUE KEY Omschr (Omschr)
) ";

$sqlTblMat = "CREATE TABLE TblMaterials (
  Id int(11) NOT NULL AUTO_INCREMENT,
  Omschr char(50) DEFAULT NULL,
  PRIMARY KEY (Id),
  UNIQUE KEY Omschr (Omschr)
) ";

$sqlTblObsCp = "CREATE TABLE TblObstacleCheckpoints (
  Id int(11) NOT NULL AUTO_INCREMENT,
  Obstacle_id int(11) NOT NULL,
  Checkpoint_id int(11) NOT NULL,
  PRIMARY KEY (Id)
) ";

$sqlTblObsChe = "CREATE TABLE TblObstacleChecks (
  Id int(11) NOT NULL AUTO_INCREMENT,
  Obstacle_id int(11) NOT NULL,
  DatCheck date DEFAULT NULL,
  ChkSt tinyint(1) DEFAULT NULL,
  CheckedBy varchar(30) DEFAULT NULL,
  Note text CHARACTER SET utf8,
  PRIMARY KEY (Id)
) ";

$sqlTblObsMat = "CREATE TABLE TblObstacleMaterials (
  Id int(11) NOT NULL AUTO_INCREMENT,
  Obstacle_id int(11) NOT NULL,
  Material_id int(11) NOT NULL,
  Aantal varchar(50) DEFAULT NULL,
  PRIMARY KEY (Id)
) ";

$sqlTblObs = "CREATE TABLE TblObstacles (
  Id int(11) NOT NULL AUTO_INCREMENT,
  Section_id int(11) NOT NULL,
  Volgnr int(11) NOT NULL,
  Omschr varchar(50) DEFAULT NULL,
  ImgPath varchar(120) DEFAULT NULL,
  ChkQ1 tinyint(1) DEFAULT NULL,
  ChkQ2 tinyint(1) DEFAULT NULL,
  ChkQ3 tinyint(1) DEFAULT NULL,
  ChkQ4 tinyint(1) DEFAULT NULL,
  PRIMARY KEY (Id)
) ";

$sqlTblSec = "CREATE TABLE TblSections (
  Id int(11) NOT NULL AUTO_INCREMENT,
  Naam varchar(5) NOT NULL,
  Omschr varchar(30) DEFAULT NULL,
  PRIMARY KEY (Id)
) ";

$sqlTblUse = "CREATE TABLE TblUsers (
  Id int(11) NOT NULL AUTO_INCREMENT,
  Email varchar(100) NOT NULL,
  Password varchar(128) DEFAULT NULL,
  salt char(128) DEFAULT NULL,
  Admin tinyint(1) DEFAULT NULL,
  PRIMARY KEY (Id)
) ";

$sqlTblLogin = "CREATE TABLE login_attempts (
  user_id int(11) NOT NULL,
  time varchar(30) NOT NULL
) ";

$sqlLckTblUse = "LOCK TABLES TblUsers WRITE";
$sqlInsUse = "INSERT INTO TblUsers VALUES  (1,'beheerder@beheer.nl','3aa009f391a233625ed15df8e29c8fda492ff662ffe48965b692636286a48c1b159ee57e2487e06c725fff3ada64e6fd08d07340bb7db00e07450e9a04c7d479','51b1d5dcde9253ee271f52f09e57fe177c572bb248480ec0e8447f60409c21fc7101aca3e822990a5c4e38189fe04eda4e8fd1b6e3b7342f36d422adef2bb131',1)";
$sqlUnlckTblUse = "UNLOCK TABLES";

// version 2.0
// update eland tabellen 1
//
// Voegt tabel toe tbv Terrein
// Voegt kolommen toe tbv Terrein
// Dit maakt het mogelijk dat 1 gebruiker meerdere terreinen beheert.
// Dit maakt het mogelijk dat het programma als SAAS gaat werken.

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

$sqlTblMat_alter = "Alter TABLE TblMaterials 
  add Terrein_id int(11) 
 ";

$sqlTblSec_alter = "Alter TABLE TblSections 
  add Terrein_id int(11) 
 ";

$sqlTblChk_alter = "Alter TABLE TblCheckpoints 
  add Terrein_id int(11) 
 ";

// version 3.0
// update eland tabellen
//
// voeg tabel toe tbv materiaaltypen
// voeg kolom materiaaltype toe aan materialen
// maakt het mogelijk pc lid rapportage gemaakt kan worden

$sqlTblMaterials_alter1 = "alter table TblMaterials 
                           add MaterialType_id int(11) ";

$sqlTblMaterials_alter2 = "alter table TblMaterials 
						  drop index Omschr ";

$sqlTblMaterials_alter3 = "alter table TblMaterials 
						   add unique key Omschr (Omschr,Terrein_id) ";

$sqlTblObstacles_alter = "alter table TblObstacles 
add MaxH decimal(2,1), 
add DatCreate date ";

$sqlTblMaterialTypes = "CREATE TABLE `TblMaterialTypes` ( 
  `Id` int(11) NOT NULL AUTO_INCREMENT, 
  `Omschr` char(50) DEFAULT NULL, 
  `Terrein_id` int(11) DEFAULT NULL, 
  PRIMARY KEY (`Id`), 
  UNIQUE KEY `Omschr` (`Omschr`) 
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=latin1 ";




// use exec() because no results are returned
    $db->exec($sqlDropTblChe);
    $db->exec($sqlDropTblMat);
    $db->exec($sqlDropTblObsCp);
    $db->exec($sqlDropTblObsChe);
    $db->exec($sqlDropTblObsMat);
    $db->exec($sqlDropTblObs);
    $db->exec($sqlDropTblSec);
    $db->exec($sqlDropTblUse);
    $db->exec($sqlDropTblLogin);
    echo "Bestaande tabellen succesvol verwijdert.";
    echo "<br>";
    $db->exec($sqlTblChe);
    $db->exec($sqlTblMat);
    $db->exec($sqlTblObsCp);
    $db->exec($sqlTblObsChe);
    $db->exec($sqlTblObsMat);
    $db->exec($sqlTblObs);
    $db->exec($sqlTblSec);
    $db->exec($sqlTblUse);
    $db->exec($sqlTblLogin);
    $db->exec($sqlLckTblUse);
    $db->exec($sqlInsUse);
    $db->exec($sqlUnlckTblUse);
//2.0 addition
    $db->exec($sqlTblTer);
    $db->exec($sqlTblTUs);
    $db->exec($sqlTblChk_alter);
    $db->exec($sqlTblMat_alter);
    $db->exec($sqlTblSec_alter);
//3.0 addition
    $db->exec($sqlTblMaterials_alter1);
    $db->exec($sqlTblMaterials_alter2);
    $db->exec($sqlTblMaterials_alter3);
    $db->exec($sqlTblObstacles_alter);
    $db->exec($sqlTblMaterialTypes);

    echo "Tabellen succesvol aangemaakt.";
    echo "<br>";


}
catch(PDOException $e) {
    echo $e->getMessage();
}


$db = null;

?>
