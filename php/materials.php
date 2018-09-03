<?php
/**
Each obstacle is build from materials.
Using this page you can maintain your materials library.
 
20171222
changed from materials to materialtypes
20180903
revised
 
copyright: 2017 Gerko Weening
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
    $tbl_name="TblMaterialTypes"; // Table name
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
            <li class="selected"><a href="materials.php">Materialen</a></li>
            <li><a href="materialdetails.php">Materiaaldetails</a></li>
        </ul>
    </div>
    
 <table display:block>                
  <tr >
  <td>
  <form name="form1" method="post" 
		action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
      <div class="cudWidget"> 
          <button class="submitbtn" type="submit" name="delMaterialType">
              <img src="../img/del.jpeg" width="35" height="35">
          </button>
      </div>
      <div class="cudWidget">
          <button class="submitbtn" type="submit" name="editMaterialType">
              <img src="../img/edit.jpeg" width="35" height="35">
          </button>
      </div>    
      <div id="widgetBar">
          <input type="text" class="inputText" name="materialtype" maxlength="32" size="32">
          <div class="cudWidget">
              <button class="submitbtn" type="submit" name="addMaterialType" >
                  <img src="../img/add.jpeg" width="35" height="35">
              </button>
          </div>
      </div>
      
       <table id="materialenTable">
          <tr class="theader">
              <th width="5%" ></th>
              <th ><strong>Omschrijving</strong></th>
          </tr>

          <?php
			 $whereTerrein = getterreinid();
          $STH = $db->query('SELECT * from TblMaterialTypes 
									  where '.$whereTerrein.'
									  order by Id');
          $STH->setFetchMode(PDO::FETCH_ASSOC);
          while($rows=$STH->fetch()){
          ?>

          <tr>
              <td width="5%" class="white2"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['Id']; ?>"></td>
              <td class = "white"><?php echo htmlentities($rows['Omschr']); ?></td>
          </tr>

          <?php
          }


		if(isset($_POST['delMaterialType'])){
			 //var_dump($_POST);
			 //$del_id = $_POST['checkbox'];
			 //print_r($_POST['checkbox']);   
			 if(!empty($_POST['checkbox'])){
				  foreach($_POST['checkbox'] as $val){
					   $ids[] = (int) $val;
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
		}else if(isset($_POST['addMaterialType'])){   
		    $sOmschr=str_replace(" ","_",$_POST['materialtype']);
			 if(!empty($sOmschr)){
				  $STH = $db->prepare("INSERT INTO $tbl_name (Omschr, Terrein_id) VALUES
				  ('$sOmschr', ".$Terreinid.")");
				  $STH->execute();
			 }else{
				echo '<script> alert("Het materiaalsoort moet nog ingevuld worden!"); </script>';
			 }
			 // if successful redirect to delete_multiple.php
			 if($STH){
				echo "<meta http-equiv=\"refresh\" content=\"0;URL=materials.php\">";
			 }
		}else if(isset($_POST['editMaterialType'])){
		    $sOmschr=$_POST['materialtype'];
		    //checkbox needs to be selected
			if(!empty($_POST['checkbox'])){
			    //only one checkbox selected
			    if (count($_POST['checkbox'])<>1){
			        echo '<script> alert("Er mag maar een item geselecteerd worden bij bewerken!"); </script>';
			        exit;
			    }else{
			        //determine which item is selected
			        foreach($_POST['checkbox'] as $val){
			            $sId = (int) $val;
			        }
			        //omschrijving needs to be filled
			        if (strlen($sOmschr)==0){
			            echo '<script> alert("Omschrijving is niet gevuld!"); </script>';
			            exit;
			        }else{
			            //name needs to be unique, selected name not included
			            $sql1 = 'select Omschr from '.$tbl_name.'
                                 where Id = '.$sId.' ';
			            $STH1=$db->prepare($sql1);
			            $STH1->execute();
			            $result = $STH1->fetch(PDO::FETCH_ASSOC);
			            $sNaamSel=$result['Omschr'];
			            //select other names
			            $STH2 = $db->query('select distinct Omschr from '.$tbl_name.'
         									where Terrein_id = '.$Terreinid.'
                                            and Omschr <>"'.$sNaamSel.'" ');
			            $STH2->setFetchMode(PDO::FETCH_ASSOC);
			            //set default in case only one or two sections
			            $volgnrok=1;
			            //loop through results
			            while($rows=$STH2->fetch()){
			                if($sOmschr == $rows['Omschr']){
			                    $volgnrok=0; // names are equal => not ok
			                    break;
			                }else{
			                    $volgnrok=1; // names are not equal => ok
			                }
			            }
			            //
			            if($volgnrok==1){
			                $STH = $db->prepare("UPDATE $tbl_name
                                                 SET Omschr = '".$sOmschr."'
                                                 WHERE Id = $sId");
			                $STH->execute();
			            }else{
			                echo '<script> alert("Controle omschrijving is elders gebruikt! Kies andere naam."); </script>';
			                exit;
			            }
			        }
			    }
			    echo "<meta http-equiv=\"refresh\" content=\"0;URL=materials.php\">";	 
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
