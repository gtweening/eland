<?php
/**
Each obstacle uses its own material.
Using this page you can maintain de materials of the obstacle.

copyright: 2013 Gerko Weening

20170702
solved undifined variable
20170705
solved undefined index when logged out
20171222
added materialtype and materialdetail in view

*/

include_once "../inc/base.php";
include_once "../inc/functions.php";

sec_session_start(); 
include_once "../common/header.php"; 

//secure login
if(login_check($mysqli) == true) { 

include_once "../common/leftColumn.php";
$tbl_name="TblObstacles"; // Table name
$tbl_name1="TblObstacleMaterials"; // Table name
$vsectionname=$_GET['Sec'];
$vhindVolgnr=$_GET['Vnr'];
$vhindId=$_GET['Id'];
$vimg=$_GET['Img'];
$stmt=null;
$STH=null;
?>

<html>
<head>
  <script type="text/JavaScript" src="../js/getObstacle.js"></script>
  <script type="text/javascript">
      function editFunction(value,id,hindid,sectionname,volgnr,img){
          var Materiaal=prompt("Verander de toelichting bij het materiaal",value);
          if (Materiaal!=null){
              window.location.href = "editHindMat.php?var1=" + Materiaal + "&hmId="+id+ "&Id="+hindid+ "&Sec="+sectionname+ "&Vnr="+volgnr+ "&Img="+img;
          }
      }
  </script>
</head>

<body id="sections">
  <div id="LeftColumn2a">
      <?php include "obstacleOverviewPerSection.php"; ?>
  </div>
  
  <div id="RightColumn">
      <table id="obstacleTable">
          <a class="tableTitle2">Hindernis <?php echo $vsectionname,str_pad($vhindVolgnr,2,'0',STR_PAD_LEFT)?></a>   
                 
          <div class="cudWidget">
				<a> <img src="<?php echo $imgPath,$vimg;?>" alt="" width="60" height="50" ></a>
          </div>

          <div id="widgetBar">    
             <a class="tableTitle4">Onderhouden hindernismaterialen</a>          
          </div>
      </table>

  <form name="form1" method="post" action="">
  <div id="RightColumnHalf">
  <table id="obstacleTableHalf">
      <tr class="theader">
          <th width="5%" ></th>
          <th ><strong>Materialen</strong></th>
          <th></th>
          <th align="center">
              <button type="submit" name="addMaterials" >
                  <img src="../img/forward.jpeg" width="35" height="35">
              </button>    
          </th>
      </tr>

      <?php
      $STH1 = $db->query('SELECT tm.*, tmt.Omschr as tmtomschr 
                          from TblMaterials tm, TblMaterialTypes tmt
                          where tmt.Id=tm.MaterialType_Id 
                            and tm.Terrein_id = "'.$_SESSION['Terreinid'].'"
                          order by tm.Id');
      $STH1->setFetchMode(PDO::FETCH_ASSOC);
      //show materials
      while($rows=$STH1->fetch()){
          $isrope = htmlentities($rows['IndSecureRope']);
          $imrope = htmlentities($rows['IndMainRope']);
          $srope="";
          $mrope="";
          if($isrope==1){$srope="Veiligheidstouw";}
          if($imrope==1){$mrope="Hoofdtouw";}
      ?>

      <tr>
          <td width="5%" class="white"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['Id']; ?>"></td>
          <td colspan="2" class = "white"><?php echo htmlentities($rows['tmtomschr']); ?></td>
          <td class = "white"><?php echo htmlentities($rows['Omschr']); ?></td>
          <td class = "white"><?php echo $srope.$mrope; ?></td>
      </tr>

      <?php
      }
      ?>
  </table>
  </div>

  <div id="RightColumnHalf">
  <table id="obstacleTableHalf">
      <tr class="theader">
          <th width="5%" ></th>
          <th width="40%"><strong>Materialen in deze hindernis</strong></th>
          <th colspan="3" align="right">
			 <div class="cudWidget">
              <button type="submit" name="delMaterials" >
                  <img src="../img/del.jpeg" width="35" height="35">
              </button> 

              <button type="submit" name="editMaterials" >
                  <img src="../img/edit.jpeg" width="35" height="35">
              </button>    
			 </div>
          </th>
      </tr>
      
      <?php
      //hindernismaterialen ophalen
      $STH2 = $db->query('SELECT tom.Id as tomId, tm.*, tom.Aantal, tmt.Omschr as tmtomschr 
                          from TblObstacleMaterials tom, TblMaterials tm, TblMaterialTypes tmt 
                          where tom.Material_id = tm.Id 
                            and tmt.Id=tm.MaterialType_Id 
                            and tom.Obstacle_id ='.$vhindId.' ');
      $STH2->setFetchMode(PDO::FETCH_ASSOC);
      //hindernismaterialen tonen
      while($rows=$STH2->fetch()){
      	 $isrope = htmlentities($rows['IndSecureRope']);
          $imrope = htmlentities($rows['IndMainRope']);
          $srope="";
          $mrope="";
          if($isrope==1){$srope="Veiligheidstouw";}
          if($imrope==1){$mrope="Hoofdtouw";}
      ?>
      
      <tr>
          <td width="5%" class="white"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['tomId']; ?>"></td>
          <td class = "white"><?php echo htmlentities($rows['tmtomschr']); ?></td>
          <td class = "white"><?php echo htmlentities($rows['Omschr']); ?></td>
          <td class = "white"><?php echo htmlentities($rows['Aantal']); ?></td>
          <td class = "white"><?php echo $srope.$mrope; ?></td>
      </tr>

      <?php
      }

      if(isset($_POST['addMaterials'])){  
          if(!empty($_POST['checkbox'])){
              foreach($_POST['checkbox'] as $val){
                  //$ids=array();
                  $ids[] = (int) $val; 
              }
              $i = 1;
              foreach($ids as $item) { //bind the values one by one
                  $query = "INSERT INTO $tbl_name1 (Obstacle_id, Material_id) VALUES " ;
                  $query .= "(".$vhindId.", ".$item.")";
                  $stmt = $db -> prepare($query);
                  $stmt->execute();
              }
          }
          
          if($stmt){
              echo "<meta http-equiv=\"refresh\" content=\"0;URL=editHindMat.php?Id=".$vhindId."&Sec=".$vsectionname."&Vnr=".$vhindVolgnr."&Img=".$vimg."\">";
          }
          
      }else  if(isset($_POST['delMaterials'])){
          if(!empty($_POST['checkbox'])){
              foreach($_POST['checkbox'] as $val){
                  $ids[] = (int) $val;
              }
              $ids = implode("','", $ids);
              $STH = $db->prepare("DELETE FROM $tbl_name1 WHERE Id IN ('".$ids."')");           
              $STH->execute();
          }
          
          // if successful redirect to delete_multiple.php
          if($STH){
              echo "<meta http-equiv=\"refresh\" content=\"0;URL=editHindMat.php?Id=".$vhindId."&Sec=".$vsectionname."&Vnr=".$vhindVolgnr."&Img=".$vimg."\">";
          }
          
      }else if(isset($_POST['editMaterials'])){
              if(!empty($_POST['checkbox'])){
                  foreach($_POST['checkbox'] as $val){
                      $ids[] = (int) $val;
                  }
                  $ids = implode("','", $ids);
                 $STH = $db->query('select * FROM '.$tbl_name1.' WHERE Id = '.$ids.'');
                 $STH->setFetchMode(PDO::FETCH_ASSOC);
                 $row=$STH->fetch();
                 $value=$row['Aantal'];
                 //call jscript
                 echo '<script> editFunction("'.$value.'", "'.$ids.'","'.$vhindId.'" ,"'.$vsectionname.'", "'.$vhindVolgnr.'", "'.$vimg.'"); </script>';   
              }
          }
          
          if(isset($_GET['var1'])){
            $sOmschr = $_GET['var1'];
            $hmId = $_GET['hmId'];
            $Id = $_GET['Id'];
            $vsectionname = $_GET['Sec'];
            $vhindVolgnr = $_GET['Vnr'];
            $vimg = $_GET['Img'];
            $STH = $db->prepare("UPDATE $tbl_name1 SET Aantal = '".$sOmschr."' WHERE Id = $hmId");
            $STH->execute();
            // if successful redirect to delete_multiple.php
              if($STH){
                  echo "<meta http-equiv=\"refresh\" content=\"0;URL=editHindMat.php?Id=".$Id."&Sec=".$vsectionname."&Vnr=".$vhindVolgnr."&Img=".$vimg."\">";
              }
          }
      ?>
  </table>
  </div>
  </form>
  
  <?php
  //close connection
  $db = null;
  ?>
  
  </table>
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