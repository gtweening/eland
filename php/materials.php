<?php

/**
Each obstacle is build from materials. 
Using this page you can maintain your materials library.

copyright: 2013 Gerko Weening

20170702
changed frmHandling. added form validation
20170705
prevent undefined index when logged out

*/

include_once "../inc/base.php";
include_once "../inc/functions.php";
include_once "../inc/queries.php";

sec_session_start(); 
include_once "../common/header.php"; 

//secure login
if(login_check($mysqli) == true) {
   
include_once "../common/leftColumn.php";
$Terreinid = $_SESSION['Terreinid']; //sessions terreinid
$tbl_name="TblMaterials"; // Table name
?>

<html>
<head>
	<script type="text/javascript">
		function editMaterialsFunction(value,ids){
			 var Materiaal=prompt("Verander de materiaalomschrijving",value);
			 if (Materiaal!=null){
		       window.location.href = "materials.php?var1=" + Materiaal + "&var2="+ids;
			 }	
		}
	</script>
</head>

<body id="materials">     
  <div id="LeftColumn2">
      
  </div>
  <div id="RightColumn">
  <table display:block>
  <tr >
  <td>
  <form name="form1" method="post" 
		  action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
      <table id="materialenTable">
          <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Materialen</a>
          <div class="cudWidget"> 
              <button class="submitbtn" type="submit" name="delMaterial">
                  <img src="../img/del.jpeg" width="35" height="35">
              </button>
          </div>
          <div class="cudWidget">
              <button class="submitbtn" type="submit" name="editMaterial">
                  <img src="../img/edit.jpeg" width="35" height="35">
              </button>
          </div>    
          <div id="widgetBar">
              <input type="text" class="inputText" name="material" maxlength="32" size="32">
              <div class="cudWidget">
                  <button class="submitbtn" type="submit" name="addMaterial" float="right">
                      <img src="../img/add.jpeg" width="35" height="35">
                  </button>
              </div>
          </div>

          <tr class="theader">
              <th width="5%" ></th>
              <th ><strong>Omschrijving</strong></th>
          </tr>

          <?php
          $whereTerrein = getterreinid();
          $STH = $db->query('SELECT * from TblMaterials 
                            where '.$whereTerrein.'
                            order by Id');
          $STH->setFetchMode(PDO::FETCH_ASSOC);
          while($rows=$STH->fetch()){
          ?>

          <tr>
              <td width="5%" class="white2"><input name="checkbox[]" type="checkbox" 
						id="checkbox[]" value="<?php echo $rows['Id']; ?>"></td>
              <td class = "white"><?php echo htmlentities($rows['Omschr']); ?></td>
          </tr>

          <?php
          }

          if(isset($_GET['var1'])){
            $sOmschr = $_GET['var1'];
            $sId = $_GET['var2'];
            $STH = $db->prepare("UPDATE $tbl_name SET Omschr = '".$sOmschr."' WHERE Id = $sId");
            $STH->execute();
            // if successful redirect to delete_multiple.php
              if($STH){
                  echo "<meta http-equiv=\"refresh\" content=\"0;URL=materials.php\">";
              }
          }

            if(isset($_POST['delMaterial'])){
                //var_dump($_POST);
                //$del_id = $_POST['checkbox'];
                //print_r($_POST['checkbox']);   
                if(!empty($_POST['checkbox'])){
                    foreach($_POST['checkbox'] as $val){
                        $ids[] = (int) $val;
                        //controller of dit controlepunt nog gebruikt wordt
                        $qry1 = "Select distinct Material_id 
                                from TblObstacleMaterials ";
                        $STH1=$db->prepare($qry1);
                        $STH1->execute();
                        while($rows=$STH1->fetch(PDO::FETCH_ASSOC)){
                            if($rows['Material_id']==$val){
                                echo '<script> alert("Dit materiaal wordt gebruikt voor een hindernis.\nVerwijderen niet mogelijk"); </script>';
                                exit;	
                             }
                        }
                    }
                    $ids = implode("','", $ids);
                    $STH = $db->prepare("DELETE FROM $tbl_name WHERE Id IN ('".$ids."')");
                    $STH->execute();
                }else{
                    echo '<script> alert("Er is niets geselecteerd om te verwijderen!"); </script>';
                }
                // if successful redirect to delete_multiple.php
                if($STH){
                         echo "<meta http-equiv=\"refresh\" content=\"0;URL=materials.php\">";
                }
            }else if(isset($_POST['addMaterial'])){      
                if(!empty($_POST['material'])){
                    $STH = $db->prepare("INSERT INTO $tbl_name (Omschr, Terrein_id) VALUES
                    ('$_POST[material]', ".$Terreinid.")");
                    $STH->execute();
                }else{
                    echo '<script> alert("De materiaalomschrijving moet nog ingevuld worden!"); </script>';
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
                   $STH = $db->query('select Omschr FROM '.$tbl_name.' WHERE Id = '.$ids.'');
                   $STH->setFetchMode(PDO::FETCH_ASSOC);
                   $row=$STH->fetch();
                   $value=$row['Omschr'];
                   //call jscript
                   echo '<script> editMaterialsFunction("'.$value.'", "'.$ids.'"); </script>';   
                }else{
                    echo '<script> alert("Er is niets geselecteerd om te bewerken!"); </script>';
                }
            }

          //close connection
          $db = null;
          ?>

      </table>
  </form>
  </td>
  </tr>
  </table>
  </div>
</body>
</html>
<?php
} else { ?>
<br>
U bent niet geautoriseerd voor toegang tot deze pagina. <a href="../index.php">Inloggen</a> alstublieft.
<?php
}
?>