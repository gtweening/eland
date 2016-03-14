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
        </script>
    </head>
</html>

<?php
include_once "inc/base.php";
include_once "inc/functions.php";
$tbl_sections="TblSections"; // Table name
$tbl_materials="TblMaterials"; // Table name
$tbl_chkpoints="TblCheckpoints"; // Table name
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
            $STH = $db->prepare("INSERT INTO $tbl_sections (Naam, Omschr) VALUES
            ('$_POST[sectienaam]','$_POST[sectieomschr]')");
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
        $STH = $db->prepare("INSERT INTO $tbl_materials (Omschr) VALUES
        ('$_POST[material]')");
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
        $STH = $db->prepare("INSERT INTO $tbl_chkpoints (Omschr) VALUES
        ('$_POST[CheckPoint]')");
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
            //$pass = hash('sha512', 'wij zijn een stichting.');
            $pass = $_POST['p']; 
            $password = hash('sha512', $pass . $random_salt);
            //admin indicator
	    if(isset($_POST['useradmin']) && $_POST['useradmin']==TRUE){
		$indadmin="1";
	    }else{
		$indadmin="0";
	    } 
            //insert
            $STH = $db->prepare("INSERT INTO TblUsers (Email, Password, salt, Admin) VALUES
            ('$_POST[usernaam]','$password', '$random_salt', '$indadmin')");
            $STH->execute();
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
}

?>
