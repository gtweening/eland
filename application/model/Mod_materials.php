<?php
        
class mod_materials{

    function getmaterials($Terreinid, $db){
        $STH = $db->query('SELECT * from TblMaterialTypes 
                           where Terrein_id ='.$Terreinid.'
                           order by Id');

        $STH->setFetchMode(PDO::FETCH_ASSOC);
        return $STH;
    }

    function getAllMaterialTypes($db){
        $STH = $db->query('SELECT distinct Omschr from TblMaterialTypes' );

        $STH->setFetchMode(PDO::FETCH_ASSOC);
        return $STH;
    }

    function addMaterialType($terreinid, $sOmschr, $db){
        $STH = $db->prepare("INSERT INTO TblMaterialTypes (Omschr, Terrein_id) VALUES
                            ('$sOmschr', '$terreinid')");
        $STH->execute();
    }

    function delMaterialType($selected, $db){
        foreach($selected as $val){
            $ids[] = (int) $val;
            //controller of voor deze sectie nog hindernissen bestaan
            $STH1=$db->prepare("Select distinct MaterialType_id from TblMaterials");
            $STH1->execute();

            while($rows = $STH1->fetch(PDO::FETCH_ASSOC)){
                if($rows['MaterialType_id']==$val){
                    $_SESSION['errormessage'] = "Er zijn materiaaldetails van dit materiaalsoort.<br>Verwijderen niet mogelijk!";
                    return false;	
                }
            }
        }

        $ids = implode("','", $ids);
        $STH = $db->prepare("DELETE FROM TblMaterialTypes WHERE Id IN ('".$ids."')");
        $STH->execute();

    }

    function editMaterialType($Id, $sOmschr, $db){
        $STH = $db->prepare("UPDATE TblMaterialTypes
                             SET Omschr = '".$sOmschr."'
                             WHERE Id = $Id");
        $STH->execute();  
    }

    function getAmountMaterials($terreinid, $db){
        $STH = $db->prepare('SELECT count(*) as amount from TblMaterialTypes 
                            where Terrein_id ='.$terreinid.'
                            order by Id');
        $STH->execute();
        $result = $STH->fetch(PDO::FETCH_ASSOC);
        $amount = $result['amount'];

        return $amount;
    }

    //############################################################

    function getmaterialdetails($Terreinid, $db){
        $STH = $db->query('SELECT m.*, mt.Omschr as matType, ms.Supplier as Supplier
                           FROM TblMaterials m 
                                left join TblMaterialTypes mt on m.MaterialType_id=mt.Id 
                                left join TblMaterialSuppliers ms on m.Supplier_id=ms.Id
                           where m.Terrein_id ='.$Terreinid.'
                           order by Id');

        $STH->setFetchMode(PDO::FETCH_ASSOC);
        return $STH;
    }

    function addMaterial($terreinid, $omschr, $mattype, $srope, $mrope, $supplier, $db){
        $STH = $db->prepare("INSERT INTO TblMaterials (Omschr, MaterialType_id, Terrein_id, IndSecureRope, IndMainRope, Supplier_id) 
                                     VALUES ('$omschr', '$mattype', '$terreinid','$srope','$mrope','$supplier')");
        $STH->execute();
    }

    function delMaterial($selected, $db){
        foreach($selected as $val){
            $ids[] = (int) $val;
            //controller of voor deze sectie nog hindernissen bestaan
            $STH1=$db->prepare("Select distinct Material_id from TblObstacleMaterials");
            $STH1->execute();

            while($rows = $STH1->fetch(PDO::FETCH_ASSOC)){
                if($rows['Material_id']==$val){
                    $_SESSION['errormessage'] = "Dit materiaal wordt gebruikt voor een hindernis.<br>Verwijderen niet mogelijk!";
                    return false;	
                }
            }
        }

        $ids = implode("','", $ids);
        $STH = $db->prepare("DELETE FROM TblMaterials WHERE Id IN ('".$ids."')");
        $STH->execute();

    }

    function editMaterial($terreinid, $Id, $omschr, $mattype, $srope, $mrope, $supplier, $db){
        $STH = $db->prepare("UPDATE TblMaterials 
                            SET Omschr = '".utf8_decode($omschr)."', 
                                MaterialType_id = '".$mattype."', 
                                IndSecureRope = $srope, 
                                IndMainRope = $mrope,
                                Supplier_id = $supplier     
                            WHERE Id = $Id");
        $STH->execute();
    }

    function addDefaultMaterials($terreinid, $db){
        $STH = $db->prepare("INSERT INTO TblMaterialTypes (Omschr, Terrein_id) VALUES 
                                    ('Hout',$terreinid), ('Staal',$terreinid), ('Polypropyleen',$terreinid), ('Polyester',$terreinid), ('Rubber',$terreinid), 
                                    ('Aluminium',$terreinid), ('Koppelstuk',$terreinid)");
        $STH->execute();

        $materials = ['Hout','Staal','Polyprop.','Polyester','Rubber'];
        $arraylength = count($materials);

        $i = 0;
        while ($i < $arraylength){
            $sqlMat = "SELECT Id FROM TblMaterialTypes WHERE Terrein_id = $terreinid and Omschr ='";
            
            switch($i){
                case 0: //hout
                    $matOmschr = 'Hout';
                    break;
                case 1: //Staal
                    $matOmschr = 'Staal';
                    break;
                case 2: //polyprop
                    $matOmschr = 'Polypropyleen';
                    break;
                case 3: //Polyester
                    $matOmschr = 'Polyester';
                    break;
                case 4: //rubber
                    $matOmschr = 'Rubber';
                    break;
            }
            $STH = $db->query($sqlMat.$matOmschr."' ");
            $STH->setFetchMode(PDO::FETCH_ASSOC);
            $row   = $STH->fetch();
            $matid = $row['Id'];

            switch($i){
                case 0: //hout
                    $sqlAdd = "INSERT INTO TblMaterials (Omschr,Terrein_id,MaterialType_id) VALUES 
                            ('d100mm',".$terreinid.",".$matid."), ('d140mm',".$terreinid.",".$matid."), ('d160mm',".$terreinid.",".$matid.")";
                    break;
                case 1: //Staal
                    $sqlAdd = "INSERT INTO TblMaterials (Omschr,Terrein_id,MaterialType_id) VALUES 
                            ('stijgerbuis d48,3x3',".$terreinid.",".$matid.")";
                    break;
                case 2: //polyprop
                    $sqlAdd = "INSERT INTO TblMaterials (Omschr,Terrein_id,MaterialType_id) VALUES 
                            ('d12mm',".$terreinid.",".$matid."), ('d18mm',".$terreinid.",".$matid."), ('d32mm',".$terreinid.",".$matid.")";
                    break;
                case 3: //Polyester
                    $sqlAdd = "INSERT INTO TblMaterials (Omschr,Terrein_id,MaterialType_id) VALUES 
                            ('hijsband 2500kg',".$terreinid.",".$matid."), ('spanband 2000kg',".$terreinid.",".$matid.")";
                    break;
                case 4: //rubber
                    $sqlAdd = "INSERT INTO TblMaterials (Omschr,Terrein_id,MaterialType_id) VALUES 
                            ('autoband',".$terreinid.",".$matid."), ('cartband',".$terreinid.",".$matid.")";
                    break;
            }        
            $STH = $db->prepare($sqlAdd);
            $STH->execute();

            $i++;
        }
    }


}