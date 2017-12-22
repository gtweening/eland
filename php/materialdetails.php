<?php

/**
Each obstacle is build from materials. 
Using this page you can maintain your materials library.

copyright: 2017 Gerko Weening

20171222
changed from materials to materialtypes


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

	</script>
</head>

<body id="materials">     
  <div id="LeftColumn2">      
  </div>
  
  <div id="RightColumn">
      <div id="widgetBartab">
        <ul class="basictab">
            <li ><a href="materials.php">Materialen</a></li>
            <li class="selected"><a href="materialdetails.php">Materiaaldetails</a></li>
        </ul>
      </div>

     <form name="form1" method="post" 
		   action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">          
          <div >
              <div class="cudWidget"> 
                  <button class="submitbtn" type="submit" name="delMaterial">
                      <img src="../img/del.jpeg" width="35" height="35">
                  </button>
              </div>
              <div class="cudWidget">
                   <button class="submitbtn" type="submit" name="editMaterial" > 
                      <img src="../img/edit.jpeg" width="35" height="35">
                  </button>
              </div>
          </div>    
          
          <div id="widgetBar3x">
              <label>Materiaal:</label> 
      		  <?php
    			$whereTerrein = getterreinid();
    			$sqlmattypes = "select Id, Omschr from TblMaterialTypes
    						    where ".$whereTerrein;
    			$stmt = $db->query($sqlmattypes);
    			$stmt->setFetchMode(PDO::FETCH_ASSOC);
    			$sSelect = "<select class='inputText' name='mattype' id='mattype' size=1>";
    			echo $sSelect;
    				while($rows=$stmt->fetch()){
    				  echo "<option value=".$rows['Id'].">" .$rows['Omschr'] . "</option>";
    				}
    			echo "</select><br>"; 
			  ?>
			  
              <lblgrey>Omschrijving: </lblgrey>
              <input type="text" class="inputText" name="material" maxlength="32" size="32">
              <br>
              <lblgrey>Extra detail:   </lblgrey>
              <input class="inputText" type="radio" name="rope" value="geen"> Geen        
              <input class="inputText" type="radio" name="rope" value="securerope"> Veiligheidstouw
              <input class="inputText" type="radio" name="rope" value="mainrope"> Hoofdtouw
          
              <div class="cudWidget">
                  <button class="submitbtn" type="submit" name="addMaterial" >
                      <img src="../img/add.jpeg" width="35" height="35">
                  </button>
              </div>      
          </div>
          
  <table display:block>
  <tr>
  <td>
     <table id="materialenTable">
          <tr class="theader">
              <th width="5%" ></th>
              <th width="30%"><strong>Materiaal type</strong></th>
              <th width="45%"><strong>Omschrijving</strong></th>
              <th ><strong>Optie</strong></th>
          </tr>

          <?php
          //get terreinid
          $whereTerrein = getterreinid();
          //get materials
          $STH = $db->query('SELECT m.*, mt.Omschr as matType
                             from TblMaterials m left join TblMaterialTypes mt 
								  on m.MaterialType_id=mt.Id 
                             where m.'.$whereTerrein.'
									  order by Id');
          $STH->setFetchMode(PDO::FETCH_ASSOC);
          //show materials
          while($rows=$STH->fetch()){
              $isrope = htmlentities($rows['IndSecureRope']);
              $imrope = htmlentities($rows['IndMainRope']);
              $srope="";
              $mrope="";
              if($isrope==1){$srope="Veiligheidstouw";}
              if($imrope==1){$srope="Hoofdtouw";}
          ?>
          <tr>
              <td width="5%" class="white2"><input name="checkbox[]" type="checkbox" 
				  id="checkbox[]" value="<?php echo $rows['Id']; ?>"></td>
              <td class = "white"><?php echo htmlentities($rows['matType']); ?></td>
              <td class = "white"><?php echo htmlentities($rows['Omschr']); ?></td>
              <td class = "white"><?php echo $srope.$mrope; ?></td>
          </tr>
          <?php
          }
         
        if(isset($_POST['delMaterial'])){
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
                     echo "<meta http-equiv=\"refresh\" content=\"0;URL=materialdetails.php\">";
            }
        }else if(isset($_POST['addMaterial'])){      
            if(!empty($_POST['material'])){
                switch ($_POST['rope']){
                    case 'securerope':
                        $srope=1;
                        $mrope=0;
                        break;
                    case 'mainrope':
                        $mrope=1;
                        $srope=0;
                        break;
                    default:
                }
                $STH = $db->prepare("INSERT INTO $tbl_name (Omschr, MaterialType_id, Terrein_id, IndSecureRope, IndMainRope) 
                                     VALUES ('$_POST[material]', '$_POST[mattype]', '$Terreinid','$srope','$mrope')");
                $STH->execute();
            }else{
                echo $_POST['rope'];
                echo '<script> alert("De materiaalomschrijving moet nog ingevuld worden!"); </script>';
            }
            // if successful redirect to delete_multiple.php
            if($STH){
                echo "<meta http-equiv=\"refresh\" content=\"0;URL=materialdetails.php\">";
            }
        }else if(isset($_POST['editMaterial'])){
            if(!empty($_POST['checkbox'])){
                if (count($_POST['checkbox'])<>1){
                    echo '<script> alert("Er mag maar een item geselecteerd worden bij bewerken!"); </script>';
                }else{
                    foreach($_POST['checkbox'] as $val){
                        $sId = (int) $val;
                    }
                    $sOmschr = trim($_POST['material']);
                    $mattype = $_POST['mattype'];
                    switch ($_POST['rope']){
                        case 'securerope':
                            $srope=1;
                            $mrope=0;
                            break;
                        case 'mainrope':
                            $mrope=1;
                            $srope=0;
                            break;
                        default:
                            $mrope=0;
                            $srope=0;
                            break;
                    }
                    if (strlen($sOmschr)<>0){
                        $STH = $db->prepare("UPDATE $tbl_name 
                                             SET Omschr = '".$sOmschr."', 
                                                 MaterialType_id = '".$mattype."', 
                                                 IndSecureRope = $srope, 
                                                 IndMainRope = $mrope    
                                             WHERE Id = $sId");
                        $STH->execute();
                    }else{
                        echo '<script> alert("Omschrijving is niet gevuld!"); </script>';
                    }
                }
            }else{
                echo '<script> alert("Er is niets geselecteerd om te bewerken!"); </script>';
            }
            // if successful redirect to delete_multiple.php
            if($STH){
               //echo "<meta http-equiv=\"refresh\" content=\"0;URL=materialdetails.php\">";
            }
        }

        //close connection
        $db = null;
        ?>
      </table>
    </td>
    </tr>
  </table>
  </form>
  
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