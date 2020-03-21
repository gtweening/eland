<?php
include_once "../inc/base.php";
include_once "../inc/functions.php";
sec_session_start(); 
?>
<html>
    <head>
        <script type="text/javascript">
            function editSectionFunction(sectionOmschr, sectionNaam,ids){
                var SectieNaam = prompt("Verander de sectie naam",sectionNaam);
                var SectieOmschr = prompt("Verander de sectie omschrijving",sectionOmschr);
                if (SectieNaam!=null){
                    window.location.href = "sections.php?id=" + ids + "&sectieNaam=" + SectieNaam + "&sectieOmschr=" + SectieOmschr ;
                }
            }
            function editMaterialsFunction(value,ids){
                var Materiaal=prompt("Verander de materiaalomschrijving",value);
                if (Materiaal!=null){
                    window.location.href = "materials.php?var1=" + Materiaal + "&var2="+ids;
                }
            }
            function editChkPointFunction(value,ids){
                var Materiaal=prompt("Verander de omschrijving van het controlepunt",value);
                if (Materiaal!=null){
                    window.location.href = "checkpoints.php?var1=" + Materiaal + "&var2="+ids;
                }
            }
				function editTerreinFunction(value,ids){
                var Terrein=prompt("Verander de terreinnaam",value);
                if (Terrein!=null){
                    window.location.href = "beheerTerreinen.php?var1=" + Terrein + "&var2="+ids;
                }
            }

        </script>
    </head>
</html>

<?php
//set terreinid for users except beheerder
if ($_SESSION['username'] != 'beheerder@beheer.nl' && $_SESSION['username'] != 'beheerder@eland.nl'){
    $Terreinid = $_SESSION['Terreinid']; //sessions terreinid
}

$tbl_sections="TblSections"; // Table name
$tbl_materials="TblMaterials"; // Table name
$tbl_chkpoints="TblCheckpoints"; // Table name
$STH=0;



// Check if delete button active, start this
if(isset($_POST['delSection'])){
        //var_dump($_POST);
        //$del_id = $_POST['checkbox'];
        //print_r($_POST['checkbox']);   
        if(!empty($_POST['checkbox'])){
            foreach($_POST['checkbox'] as $val){
                $ids[] = (int) $val;
            }
            $ids = implode("','", $ids);
            $STH = $db->prepare("DELETE FROM $tbl_sections WHERE Id IN ('".$ids."')");
            $STH->execute();
        }
        // if successful redirect to delete_multiple.php
        if($STH){
            echo "<meta http-equiv=\"refresh\" content=\"0;URL=sections.php\">";
        }
}else if(isset($_POST['addSection'])){      
        if(!empty($_POST['sectienaam'])){
            $STH = $db->prepare("INSERT INTO $tbl_sections (Naam, Omschr, Terrein_id) VALUES
            ('$_POST[sectienaam]','$_POST[sectieomschr]',".$Terreinid.")");
            $STH->execute();
        }
        // if successful redirect to delete_multiple.php
        if($STH){
            echo "<meta http-equiv=\"refresh\" content=\"0;URL=sections.php\">";
        }
}if(isset($_POST['editSection'])){
    if(!empty($_POST['checkbox'])){
        foreach($_POST['checkbox'] as $val){
            $ids[] = (int) $val;
        }
        $ids = implode("','", $ids);
       $STH = $db->query('select * FROM '.$tbl_sections.' WHERE Id = '.$ids.'');
       $STH->setFetchMode(PDO::FETCH_ASSOC);
       $row=$STH->fetch();
       $sectionOmschr=$row['Omschr'];
       $sectionNaam=$row['Naam'];

       //call jscript
       echo '<script> editSectionFunction("'.$sectionOmschr.'","'.$sectionNaam.'", "'.$ids.'"); </script>';   
    }
}else if(isset($_POST['delMaterial'])){
    //var_dump($_POST);
    //$del_id = $_POST['checkbox'];
    //print_r($_POST['checkbox']);   
    if(!empty($_POST['checkbox'])){
        foreach($_POST['checkbox'] as $val){
            $ids[] = (int) $val;
        }
        $ids = implode("','", $ids);
        $STH = $db->prepare("DELETE FROM $tbl_materials WHERE Id IN ('".$ids."')");
        $STH->execute();
    }
    // if successful redirect to delete_multiple.php
    if($STH){
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=materials.php\">";
    }
}else if(isset($_POST['addMaterial'])){      
    if(!empty($_POST['material'])){
        $STH = $db->prepare("INSERT INTO $tbl_materials (Omschr, Terrein_id) VALUES
        ('$_POST[material]', ".$Terreinid.")");
        $STH->execute();
    }
    // if successful redirect to delete_multiple.php
    if($STH){
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=materials.php\">";
    }
}else if(isset($_POST['editMaterial'])){
    if(!empty($_POST['checkbox'])){
        foreach($_POST['checkbox'] as $val){
            $ids[] = (int) $val;
        }
        $ids = implode("','", $ids);
       $STH = $db->query('select Omschr FROM '.$tbl_materials.' WHERE Id = '.$ids.'');
       $STH->setFetchMode(PDO::FETCH_ASSOC);
       $row=$STH->fetch();
       $value=$row['Omschr'];
       //call jscript
       echo '<script> editMaterialsFunction("'.$value.'", "'.$ids.'"); </script>';   
    }
}else if(isset($_POST['delChkPoint'])){
    //var_dump($_POST);
    //$del_id = $_POST['checkbox'];
    //print_r($_POST['checkbox']);   
    if(!empty($_POST['checkbox'])){
        foreach($_POST['checkbox'] as $val){
            $ids[] = (int) $val;
        }
        $ids = implode("','", $ids);
        $STH = $db->prepare("DELETE FROM $tbl_chkpoints WHERE Id IN ('".$ids."')");
        $STH->execute();
    }
    // if successful redirect to delete_multiple.php
    if($STH){
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=checkpoints.php\">";
    }
}else if(isset($_POST['addChkPoint'])){      
    if(!empty($_POST['CheckPoint'])){
        $STH = $db->prepare("INSERT INTO $tbl_chkpoints (Omschr, Terrein_id) VALUES
        ('$_POST[CheckPoint]', ".$Terreinid.")");
        $STH->execute();
    }
    // if successful redirect to delete_multiple.php
    if($STH){
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=checkpoints.php\">";
    }
}else if(isset($_POST['editChkPoint'])){
    if(!empty($_POST['checkbox'])){
        foreach($_POST['checkbox'] as $val){
            $ids[] = (int) $val;
        }
        $ids = implode("','", $ids);
       $STH = $db->query('select Omschr FROM '.$tbl_chkpoints.' WHERE Id = '.$ids.'');
       $STH->setFetchMode(PDO::FETCH_ASSOC);
       $row=$STH->fetch();
       $value=$row['Omschr'];
       //call jscript
       echo '<script> editChkPointFunction("'.$value.'", "'.$ids.'"); </script>';   
    }
}else if(isset($_POST['addUser'])){      
//controleren en aanvullen
        if(!empty($_POST['usernaam'])){
            //salt
            $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
            //password
            $pass = $_POST['p']; 
            $password = hash('sha512', $pass . $random_salt);
            //admin indicator
            if(isset($_POST['useradmin']) && $_POST['useradmin']==TRUE){
                $indadmin="1";
            }else{
                $indadmin="0";
            }
            //ema adress
            $ema = $_POST['emailadres'];
            //insert user
            $STH = $db->prepare("INSERT INTO TblUsers (Email, Password, salt, Admin, ema) VALUES
                    ('$_POST[usernaam]','$password', '$random_salt', '$indadmin', '$ema')");
            $STH->execute();
        }else {
            echo "<meta http-equiv=\"refresh\" content=\"0;URL=beheerder.php\">";
        } 
        // if successful redirect to beheerder.php
        if($STH){
            echo "<meta http-equiv=\"refresh\" content=\"0;URL=beheerder.php\">";
        }
}else if(isset($_POST['delUser'])){
    //var_dump($_POST);
    //$del_id = $_POST['checkbox'];
    //print_r($_POST['checkbox']);   
    if(!empty($_POST['checkbox'])){
        foreach($_POST['checkbox'] as $val){
            $ids[] = (int) $val;
        }
        $ids = implode("','", $ids);
        $STH = $db->prepare("DELETE FROM TblUsers WHERE Id IN ('".$ids."')");
        $STH->execute();
    }
    // if successful redirect to beheerder.php
    if($STH){
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=beheerder.php\">";
    }
}else if(isset($_POST['addTerrein'])){      
    if(!empty($_POST['Terreinnaam'])){
        $terreinnaam = $_POST['Terreinnaam' ];
        $STH = $db->prepare("INSERT INTO TblTerrein (Terreinnaam) VALUES
        ('$terreinnaam')");
        $STH->execute();

        //get terrainid
        $STH = $db->query("SELECT Id FROM TblTerrein WHERE Terreinnaam = '".$terreinnaam."'");
        $STH->setFetchMode(PDO::FETCH_ASSOC);
        $row=$STH->fetch();
        $value=$row['Id'];
       
        //add default materials
        $STH = $db->prepare("INSERT INTO TblMaterialTypes (Omschr, Terrein_id) VALUES 
                            ('Hout',$value), ('Staal',$value), ('Polypropyleen',$value), ('Polyester',$value), ('Rubber',$value), 
                            ('Aluminium',$value), ('Koppelstuk',$value)");
        $STH->execute();

        $materials = ['Hout','Staal','Polyprop.','Polyester','Rubber'];
        $arraylength = count($materials);

        $i =0;
        while ($i < $arraylength){
            $sqlMat = "SELECT Id FROM TblMaterialTypes WHERE Terrein_id = $value and Omschr ='";
            
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
            $row=$STH->fetch();
            $matid=$row['Id'];

            switch($i){
                case 0: //hout
                    $sqlAdd = "INSERT INTO TblMaterials (Omschr,Terrein_id,MaterialType_id) VALUES 
                            ('d100mm',".$value.",".$matid."), ('d140mm',".$value.",".$matid."), ('d160mm',".$value.",".$matid.")";
                    break;
                case 1: //Staal
                    $sqlAdd = "INSERT INTO TblMaterials (Omschr,Terrein_id,MaterialType_id) VALUES 
                            ('stijgerbuis d48,3x3',".$value.",".$matid.")";
                    break;
                case 2: //polyprop
                    $sqlAdd = "INSERT INTO TblMaterials (Omschr,Terrein_id,MaterialType_id) VALUES 
                            ('d12mm',".$value.",".$matid."), ('d18mm',".$value.",".$matid."), ('d32mm',".$value.",".$matid.")";
                    break;
                case 3: //Polyester
                    $sqlAdd = "INSERT INTO TblMaterials (Omschr,Terrein_id,MaterialType_id) VALUES 
                            ('hijsband 2500kg',".$value.",".$matid."), ('spanband 2000kg',".$value.",".$matid.")";
                    break;
                case 4: //rubber
                    $sqlAdd = "INSERT INTO TblMaterials (Omschr,Terrein_id,MaterialType_id) VALUES 
                            ('autoband',".$value.",".$matid."), ('cartband',".$value.",".$matid.")";
                    break;
            }        
            $STH = $db->prepare($sqlAdd);
            $STH->execute();

            $i++;
        }

    }else {
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=beheerTerreinen.php\">";
    }
    // if successful redirect to delete_multiple.php
    if($STH){
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=beheerTerreinen.php\">";
    }
}else if(isset($_POST['delTerrein'])){
    //var_dump($_POST);
    //$del_id = $_POST['checkbox'];
    //print_r($_POST['checkbox']);   
    if(!empty($_POST['checkbox'])){
        foreach($_POST['checkbox'] as $val){
            $ids[] = (int) $val;
        }
        $ids = implode("','", $ids);
        $STH = $db->prepare("DELETE FROM TblTerrein WHERE Id IN ('".$ids."')");
        $STH->execute();
    }
    // if successful redirect to beheerder.php
    if($STH){
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=beheerTerreinen.php\">";
    }
}else if(isset($_POST['editTerrein'])){
    if(!empty($_POST['checkbox'])){
        foreach($_POST['checkbox'] as $val){
            $ids[] = (int) $val;
        }
        $ids = implode("','", $ids);
       $STH = $db->query('select Terreinnaam FROM TblTerrein WHERE Id = '.$ids.'');
       $STH->setFetchMode(PDO::FETCH_ASSOC);
       $row=$STH->fetch();
       $value=$row['Terreinnaam'];
       //call jscript
       echo '<script> editTerreinFunction("'.$value.'", "'.$ids.'"); </script>';   
    }
}else if(isset($_POST['addGebrTerrein'])){      
    if(!empty($_POST['Terrein'])){
        $STH = $db->prepare("INSERT INTO TblTerreinUsers (User_id, Terrein_id) VALUES
        ('$_POST[User]','$_POST[Terrein]')");
        $STH->execute();
    }
    // if successful redirect to delete_multiple.php
    if($STH){
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=beheerGebruikersTerreinen.php\">";
    }
}else if(isset($_POST['delGebrTerrein'])){
    //var_dump($_POST);
    //$del_id = $_POST['checkbox'];
    //print_r($_POST['checkbox']);   
    if(!empty($_POST['checkbox'])){
        foreach($_POST['checkbox'] as $val){
            $ids[] = (int) $val;
        }
        $ids = implode("','", $ids);
        $STH = $db->prepare("DELETE FROM TblTerreinUsers WHERE Id IN ('".$ids."')");
        $STH->execute();
    }
    // if successful redirect to beheerder.php
    if($STH){
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=beheerGebruikersTerreinen.php\">";
    }
}else if(isset($_POST['setGebrTerrein'])){
	if(!empty($_POST['checkbox'])){
        foreach($_POST['checkbox'] as $val){
            $ids[] = (int) $val;
        }
        $ids = implode("','", $ids);
        $STH = $db->query("Select Terrein_id FROM TblTerreinUsers WHERE Id IN ('".$ids."')");
		  $STH->setFetchMode(PDO::FETCH_ASSOC);
		  while($rows=$STH->fetch()){
             $Terreinid = htmlentities($rows['Terrein_id']);
		  }
    }
    // if successful redirect to section.php
    if($STH){
		$_SESSION['Terreinid'] = $Terreinid;
		$STH = $db->query("Select Terreinnaam FROM TblTerrein WHERE Id = '".$Terreinid."'");
		$STH->setFetchMode(PDO::FETCH_ASSOC);
		$row=$STH->fetch();
        $value=$row['Terreinnaam'];
        $_SESSION['Terreinnaam'] = $value;
        //add to log
        $login_time = date('Y-m-d h:i:sa');
        $log_msg = $_SESSION['username'].';'.$value.';'.$login_time."\n";
        file_put_contents('../img/login.log', $log_msg, FILE_APPEND);

		echo "<meta http-equiv=\"refresh\" content=\"0;URL=sections.php\">";
    }
}else if(isset($_POST['addBericht'])){
    if(!empty($_POST['titel'])){
        $STH = $db->prepare("INSERT INTO TblMessages (Datum, Titel, Bericht) VALUES
        ('$_POST[datum]','$_POST[titel]','$_POST[bericht]')");
        $STH->execute();
    }
    // if successful redirect to delete_multiple.php
    if($STH){
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=beheerBerichten.php\">";
    }
}else if(isset($_POST['delBericht'])){
    //var_dump($_POST);
    //$del_id = $_POST['checkbox'];
    //print_r($_POST['checkbox']);
    if(!empty($_POST['checkbox'])){
        foreach($_POST['checkbox'] as $val){
            $ids[] = (int) $val;
        }
        $ids = implode("','", $ids);
        $STH = $db->prepare("DELETE FROM TblMessages WHERE Id IN ('".$ids."')");
        $STH->execute();
    }
    // if successful redirect to beheerder.php
    if($STH){
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=beheerBerichten.php\">";
    }
}else if(isset($_POST['pubBericht'])){
    if(!empty($_POST['checkbox'])){
        foreach($_POST['checkbox'] as $val){
            $ids[] = (int) $val;
        }
        $ids = implode("','", $ids);
        $STH = $db->prepare("Update TblMessages set Gepubliceerd = 1 WHERE Id IN ('".$ids."')");
        $STH->execute();
    }
    // if successful redirect to beheerder.php
    if($STH){
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=beheerBerichten.php\">";
    }
}
?>
