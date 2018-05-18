<?php

/**
The collection of obstacles are devided into section.
Using this page you can maintain Sections.

copyright: 2013-2017 Gerko Weening

20170702
changed frmHandling. added form validation.
20170705
prevent undefined index when logged out
20171222 
changed way of processing update
20180504
added order name

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

//determine sort
if(isset($_GET['s'])){
    if ($_GET['s']=='a'){
        $orderby=" order by Naam asc";
    }else{
        $orderby=" order by Naam desc";
    }
}else{
  $orderby=" order by Id";
}
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
              <th class="dropdown" width="10%">
              	Naam
              	<div class="account-dropbox">
                      <a href="sections.php">Standaard</a>
                      <a href="sections.php?s=a">
                          <img src="../img/sort_AZ.png" width="20" height="20">
                          Oplopend</a>
                      <a href="sections.php?s=d">
                      <img src="../img/sort_ZA.png" width="20" height="20">
                      Aflopend</a>
                   </div>
              </th>
              <th ><strong>Omschrijving</strong></th>
          </tr>

          <?php
	         $whereTerrein = getterreinid();
             $STH = $db->query('SELECT * from TblSections
                                where '.$whereTerrein.$orderby.'  
                                ');
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
                $sNaam=$_POST['sectienaam'];
                $sOmschr=$_POST['sectieomschr'];
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
                        if (strlen($sOmschr)==0 or strlen($sNaam)==0){
                            echo '<script> alert("Omschrijving of sectienaam is niet gevuld!"); </script>';
                            exit;
                        }else{
                            //name needs to be unique, selected name not included
                            $sql1 = 'select Naam from '.$tbl_name.'
                                     where Id = '.$sId.' ';
                            $STH1=$db->prepare($sql1);
                            $STH1->execute();
                            $result = $STH1->fetch(PDO::FETCH_ASSOC);
                            $sNaamSel=$result['Naam'];
                            //select other names
                            $STH2 = $db->query('select distinct Naam from '.$tbl_name.'
             									where Terrein_id = '.$Terreinid.' 
                                                and Naam <>"'.$sNaamSel.'" ');
                            $STH2->setFetchMode(PDO::FETCH_ASSOC);
                            //set default in case only one or two sections
                            $volgnrok=1;
                            //loop through results
                            while($rows=$STH2->fetch()){
                                if($sNaam == $rows['Naam']){
                                    $volgnrok=0; // names are equal => not ok
                                    break;
                                }else{
                                    $volgnrok=1; // names are not equal => ok
                                }
                            }
                            //
                            if($volgnrok==1){
                                $STH = $db->prepare("UPDATE $tbl_name 
                                                     SET Omschr = '".$sOmschr."' , Naam = '".$sNaam."' 
                                                     WHERE Id = $sId");
                                $STH->execute();
                            }else{
                                echo '<script> alert("Sectienaam is elders gebruikt! Kies andere naam."); </script>';
                                exit;
                            }
                        }
                    }
                    echo "<meta http-equiv=\"refresh\" content=\"0;URL=sections.php\">";
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


