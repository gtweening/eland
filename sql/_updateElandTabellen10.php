<?php
/**
 * update eland tabellen 10
 *
 * Voegt tabel toe tbv materiaal leveranciers
 * Dit maakt het mogelijk specifieke leveranciers toe te wijzen voor materialen.
 * een leverancier wordt toegekend aan een materiaaltype.
 * het is mogelijk om hetzelfde materiaaltype te gebruiken van verschillende leveranciers.
 */

/** Copyright 2021 Gerko Weening */

include_once "../inc/base.php";

try {  
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //drop existing tables
    $sqlDropTblMatLev = "DROP TABLE IF EXISTS TblMaterialSuppliers";

    //table structure
    $sqlTblMatLev = "CREATE TABLE TblMaterialSuppliers (
    Id int(11) NOT NULL AUTO_INCREMENT,
    MaterialType char(50) NOT NULL,
    Supplier char(25) NOT NULL,
    PRIMARY KEY (Id),
    UNIQUE KEY Supplier (Supplier)
    ) ";

    $sqlTblMaterials_alter4 = "ALTER TABLE TblMaterials
        add Supplier_id int(11) NULL";

    // use exec() because no results are returned
    $db->exec($sqlDropTblMatLev);
    $db->exec($sqlTblMatLev);
    $db->exec($sqlTblMaterials_alter4);
    echo "Tabellen succesvol aangepast.";
    echo "<br>";


}
catch(PDOException $e) {
    echo $e->getMessage();
}


$db = null;

?>
