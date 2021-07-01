<?php
        
class mod_materialSuppliers{

    function getMaterialSuppliers($db){
        $STH = $db->query('SELECT * from TblMaterialSuppliers 
                           order by Id');

        $STH->setFetchMode(PDO::FETCH_ASSOC);
        return $STH;
    }

    function getMaterialTypesofSuppliers($db){
        $STH = $db->query('SELECT distinct MaterialType from TblMaterialSuppliers');

        $STH->setFetchMode(PDO::FETCH_ASSOC);
        return $STH;
    }

    function addMaterialSupplier($mattype, $supplier, $db){
        $STH = $db->prepare("INSERT INTO TblMaterialSuppliers (MaterialType, Supplier) VALUES
                            ('$mattype', '$supplier')");
        $STH->execute();
    }

    function editMaterialSupplier($Id, $mattype, $supplier, $db){
        $STH = $db->prepare("UPDATE TblMaterialSuppliers
                             SET MaterialType = '".$mattype."', Supplier = '".$supplier."' 
                             WHERE Id = $Id");
        $STH->execute();  
    }

    function delMaterialSupplier($selected, $db){
        $STH = $db->prepare("DELETE FROM TblMaterialSuppliers WHERE Id IN ('".$selected."')");
        $STH->execute();
    }

}