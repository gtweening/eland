<?php

/**
The collection of obstacles are devided into section.
Using this page you can maintain Sections.

copyright: 2013-2017 Gerko Weening

20170702
changed frmHandling. added form validation.
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
$tbl_name="TblSections"; // Table name
?>

<html>
<head>
  <script type="text/javascript">
      function FunSectie(e){
          if(!e.target){
              // alert(e.srcElement.innerHTML);
          } else {
              var section =  e.target.innerHTML;
              window.location.href = "obstaclesSection.php?sectie=" + section;
          } 
      }
      function FunOmschr(e){
          if(!e.target){
              // alert(e.srcElement.innerHTML);
          } else {
              var sectieomschr =  e.target.innerHTML;
              window.location.href = "obstaclesSection.php?omschr=" + sectieomschr;
          } 
      }
		function editSectionFunction(sectionOmschr, sectionNaam,ids){
			 var SectieNaam = prompt("Verander de sectie naam",sectionNaam);
			 var SectieOmschr = prompt("Verander de sectie omschrijving",sectionOmschr);
			 if (SectieNaam!=null){
				  window.location.href = "sections.php?id=" + ids + "&sectieNaam=" + SectieNaam + "&sectieOmschr=" + SectieOmschr ;
			 }
		}
  </script>
</head>

<body id="sections">
  <div id="LeftColumn2">      
  </div>

  <div id="RightColumn">
  <table display:block>
  <tr >
  <td>
  <form name="form1" method="post" 
		  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <table id="materialenTable2" >
          <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Hindernissecties</a>
          <div class="cudWidget">
              <button class="submitbtn" type="submit" name="delSection">
                  <img src="../img/del.jpeg" width="35" height="35">
              </button>
          </div>
          <div class="cudWidget">
              <button class="submitbtn" type="submit" name="editSection">
                  <img src="../img/edit.jpeg" width="35" height="35">
              </button>
          </div>
          <div id="widgetBar">
              <input type="text" class="inputText" name="sectienaam" maxlength="5" size="5">
              <input type="text" class="inputText" name="sectieomschr" maxlength="50" size="32">
              <div class="cudWidget">
                  <button class="submitbtn" type="submit" name="addSection" float="right">
                      <img src="../img/add.jpeg" width="35" height="35">
                  </button>
              </div>
          </div>

          <tr class="theader">
              <th width="5%" ></th>
              <th width="10%"><strong>Naam</strong></th>
              <th ><strong>Omschrijving</strong></th>
          </tr>

          <?php
	     $whereTerrein = getterreinid();
             $STH = $db->query('SELECT * from TblSections
                                where '.$whereTerrein.'  
                                order by Id');
             $STH->setFetchMode(PDO::FETCH_ASSOC);
             while($rows=$STH->fetch()){
          ?>

          <tr >
              <td width="5%" class="white2"><input name="checkbox[]" type="checkbox" 
						id="checkbox[]" value="<?php echo $rows['Id']; ?>"></td>
              <td width="5%" class="white" 
		onclick="FunSectie(event)"><?php echo htmlentities($rows['Naam']); ?></td>
              <td class = "white" 
		onclick="FunOmschr(event)"><?php echo htmlentities($rows['Omschr']); ?></td>
          </tr>

          <?php
          }

          if(isset($_GET['sectieNaam'])){
            $sNaam = $_GET['sectieNaam'];
            $sOmschr = $_GET['sectieOmschr'];
            $sId = $_GET['id'];
            $STH = $db->prepare("UPDATE $tbl_name SET Omschr = '".$sOmschr."' , Naam = '".$sNaam."' WHERE Id = $sId");
            $STH->execute();
            // if successful redirect to sections.php
              if($STH){
                  echo "<meta http-equiv=\"refresh\" content=\"0;URL=sections.php\">";
              }
          }

            // Check if delete button active, start this
            if(isset($_POST['delSection'])){
                //var_dump($_POST);
                //$del_id = $_POST['checkbox'];
                //print_r($_POST['checkbox']);   
                if(!empty($_POST['checkbox'])){
                    foreach($_POST['checkbox'] as $val){
                        $ids[] = (int) $val;
                        //controller of voor deze sectie nog hindernissen bestaan
                        $STH1=$db->prepare("Select distinct Section_id from TblObstacles");
                        $STH1->execute();
                        //$STH1->setFetchMode(PDO::FETCH_ASSOC);
                        while($rows=$STH1->fetch(PDO::FETCH_ASSOC)){
                            if($rows['Section_id']==$val){
                                echo '<script> alert("Er zijn hindernissen in deze sectie.\nVerwijderen niet mogelijk"); </script>';
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
                    echo "<meta http-equiv=\"refresh\" content=\"0;URL=sections.php\">";
                }
            }else if(isset($_POST['addSection'])){      
                if(!empty($_POST['sectienaam'])){
                    $STH = $db->prepare("INSERT INTO $tbl_name (Naam, Omschr, Terrein_id) VALUES
                    ('$_POST[sectienaam]','$_POST[sectieomschr]',".$Terreinid.")");
                    $STH->execute();
                }else{
                    echo '<script> alert("De sectienaam moet nog ingevuld worden!"); </script>';
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
                    $STH = $db->query('select * FROM '.$tbl_name.' WHERE Id = '.$ids.'');
                    $STH->setFetchMode(PDO::FETCH_ASSOC);
                    $row=$STH->fetch();
                    $sectionOmschr=$row['Omschr'];
                    $sectionNaam=$row['Naam'];

                    //call jscript
                    echo '<script> editSectionFunction("'.$sectionOmschr.'","'.$sectionNaam.'", "'.$ids.'"); </script>';   
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


