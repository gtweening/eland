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
$sqlDropTblChe = "DROP TABLE IF EXISTS TblCheckpoints";
$sqlDropTblMat = "DROP TABLE IF EXISTS TblMaterials";
$sqlDropTblObsChep = "DROP TABLE IF EXISTS TblObstacleCheckpoints";
$sqlDropTblObsChe = "DROP TABLE IF EXISTS TblObstacleChecks";
$sqlDropTblObsMat = "DROP TABLE IF EXISTS TblObstacleMaterials";
$sqlDropTblObs = "DROP TABLE IF EXISTS TblObstacles";
$sqlDropTblSec = "DROP TABLE IF EXISTS TblSections";
$sqlDropTblUse = "DROP TABLE IF EXISTS TblUsers";
$sqlDropTbLogin = "DROP TABLE IF EXISTS login_attempts";

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

$sqlTblObsChep = "CREATE TABLE TblObstacleCheckpoints (
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


// use exec() because no results are returned
    $db->exec($sqlDropTblChe);
    $db->exec($sqlDropTblMat);
    $db->exec($sqlDropTblObsChep);
    $db->exec($sqlDropTblObsChe);
    $db->exec($sqlDropTblObsMat);
    $db->exec($sqlDropTblObs);
    $db->exec($sqlDropTblSec);
    $db->exec($sqlDropTblUse);
    $db->exec($sqlDropTbLogin);
    echo "<script language='javascript'>";
    echo "alert('Bestaande tabellen succesvol verwijdert.')";
    echo "</script>";
    $db->exec($sqlTblChe);
    $db->exec($sqlTblMat);
    $db->exec($sqlTblObsChep);
    $db->exec($sqlTblObsChe);
    $db->exec($sqlTblObsMat);
    $db->exec($sqlTblObs);
    $db->exec($sqlTblSec);
    $db->exec($sqlTblUse);
    $db->exec($sqlTblLogin);
    $db->exec($sqlLckTblUse);
    $db->exec($sqlInsUse);
    $db->exec($sqlUnlckTblUse);
    echo "<script language='javascript'>";
    echo "alert('Benodidgde tabellen succesvol aangemaakt.')";
    echo "</script>";

}
catch(PDOException $e) {
    echo $e->getMessage();
}

$db = null;

echo "<meta http-equiv=\"refresh\" content=\"0;URL=../index.php\">";

?>
