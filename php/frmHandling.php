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
include_once "../inc/base.php";
include_once "../inc/functions.php";
sec_session_start(); 
$Terreinid = $_SESSION['Terreinid']; //sessions terreinid
$tbl_sections="TblSections"; // Table name
$tbl_materials="TblMaterials"; // Table name
$tbl_chkpoints="TblCheckpoints"; // Table name
$STH=0;

//var_dump($_POST);

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
            //insert user
            $STH = $db->prepare("INSERT INTO TblUsers (Email, Password, salt, Admin) VALUES
            ('$_POST[usernaam]','$password', '$random_salt', '$indadmin')");
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
        $STH = $db->prepare("INSERT INTO TblTerrein (Terreinnaam) VALUES
        ('$_POST[Terreinnaam]')");
        $STH->execute();
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
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=sections.php\">";
    }
   

}

?>
