<?php
/**
Shows the obstacles for the selected section.

copyright: 2013 Gerko Weening

20170705
solved undefined index when logged out
20171007
solved issue with php short code
solved issue showing obstacles of section
*/

include_once "../inc/base.php";
include_once "../inc/functions.php";

sec_session_start();
include_once "../common/header.php"; 

//secure login
if(login_check($mysqli) == true) {

include_once "../common/leftColumn.php";
$tbl_name="TblObstacles"; // Table name
$vsectionid="";
$vsectionname="";
$vsectionomschr="";
//obstacle security
$optObsSec = array("onbekend",
                   "Door SBN goedgekeurd materiaal",
                   "Taak-Risico-Analyse",
                   "Constructieberekening",
                   "Labels" );

//determine sectionname
if(isset($_GET['sectie'])){
    $vsectionname = $_GET['sectie'];
    $STH = $db->query('SELECT * 
                       from TblSections 
                       where Naam ="'.$vsectionname.'"
                             and Terrein_id = "'.$_SESSION['Terreinid'].'" ');
    $STH->setFetchMode(PDO::FETCH_ASSOC);
    $row=$STH->fetch();
    $vsectionid=$row['Id'];
}elseif(isset($_GET['omschr'])){
	 $vsectionomschr = $_GET['omschr'];
    $STH = $db->query('SELECT * 
                       from TblSections 
                       where Omschr ="'.$vsectionomschr.'"
                             and Terrein_id = "'.$_SESSION['Terreinid'].'" ');
    $STH->setFetchMode(PDO::FETCH_ASSOC);
    $row=$STH->fetch();
    $vsectionname=$row['Naam'];
    $vsectionid=$row['Id'];
}
?>

<html>
    <head>
        <script type="text/javascript">
            function FunOmschr(e,Id,sectionname,volgnr){
                if(!e.target){
                    // alert(e.srcElement.innerHTML);
                } else {
                    var hindomschr =  e.target.innerHTML;
                    window.location.href = "obstacle.php?Id=" + Id +"&Sec="+sectionname+"&Vnr="+volgnr;
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
				  action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
            <table id="materialenTable2">

               <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Hindernissen sectie: <?php echo $vsectionname;?></a>
                    <div class="cudWidget"> 
                        <button type="submit" name="delObstacle">
                            <img src="../img/del.jpeg" width="35" height="35">
                        </button>
                    </div>
                    <div class="cudWidget">
                        <input type="hidden" name="sectionName" value="<?php echo $vsectionname;?>">
                        <button type="submit" name="editObstacle">
                            <img src="../img/edit.jpeg" width="35" height="35">
                        </button>
                    </div>

                    <div id="widgetBar3x">
                        <input type="hidden" name="sectionId" value="<?php echo $vsectionid;?>">
                        <label>Volgnr.</label>
                        <input type="text" class="inputText" name="volgnr" maxlength="5" size="5">
                        <lblgrey>Gebouwd op</lblgrey>
                        <input type="date"  name="datcreate" maxlength="5" size="5">
                        <lblgrey>Hoogte</lblgrey> 
                        <input type="text" name="maxh" maxlength="2" size="2">
                        <br>
                        <label>Omschrijving</label>
                        <input type="text" class="inputText" name="hindernisOmschr" maxlength="32" size="38">
                        <br>
                        <div class="cudWidget">
                            <button type="submit" name="addObstacle" float="right">
                                <img src="../img/add.jpeg" width="35" height="35">
                            </button>
                        </div>
                        <br>
                        <lblgrey>Veiligheid gewaarborgd door:</lblgrey>
                        <select name="obsSec">
                            <?php
                            foreach($optObsSec as $key => $value):
                                echo '<option value="'.$key.'">'.$value.'</option>';
                            endforeach;
                            ?>
                        </select>
                    </div>
                    
                <tr class="theader">
                    <th width="5%" ></th>
                    <th width="10%"><strong>Volgnr</strong></th>
                    <th width="15%"><strong>Datum</strong></th>
                    <th width="5%"><strong>H</strong></th>
                    <th ><strong>Omschrijving</strong></th>
                </tr>

                <?php
                $query='Select * from TblObstacles '
                     . 'where section_id = (select Id from TblSections 
                                            where Naam = "'.$vsectionname .'" 
                                              and Terrein_id = "'.$_SESSION['Terreinid'].'") ';

                $STH = $db->query('Select * from '.$tbl_name.' 
                                   where section_id = (select Id 
                                                       from TblSections 
                                                       where Naam = "'.$vsectionname .'" 
                                                         and Terrein_id = "'.$_SESSION['Terreinid'].'") 
                                                       order by Volgnr');
                $STH->setFetchMode(PDO::FETCH_ASSOC);
                while($rows=$STH->fetch()){
                ?>

                <tr>
                    <td width="5%" class="white2"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['Id']; ?>"></td>
                    <td class = "white2"><?php echo str_pad(htmlentities($rows['Volgnr']),2,'0',STR_PAD_LEFT); ?></td>
                    <td class = "white2"><?php echo str_pad(htmlentities($rows['DatCreate']),2,'0',STR_PAD_LEFT); ?></td>
                    <td class = "white2"><?php echo str_pad(htmlentities($rows['MaxH']),2,'0',STR_PAD_LEFT); ?></td>
                    <td class = "white" onclick="FunOmschr(event,'<?php echo $rows['Id'];?>','<?php echo $vsectionname;?>','<?php echo $rows['Volgnr'];?>')"><?php echo htmlentities($rows['Omschr']); ?></td>
                </tr>

                <?php
                }
                
                if(isset($_GET['hindVolgnr'])){
                  $sVolgnr = $_GET['hindVolgnr'];
                  $sOmschr = $_GET['hindOmschr'];
                  $sId = $_GET['hindId'];
                  $ssectionNaam = $_GET['sectieNaam'];
                  $STH = $db->prepare("UPDATE $tbl_name SET Volgnr = '".$sVolgnr."', Omschr = '".$sOmschr."' WHERE Id = $sId");
                  $STH->execute();
                  // if successful redirect to delete_multiple.php
                    if($STH){
                        echo "<meta http-equiv=\"refresh\" content=\"0;URL=obstaclesSection.php?sectie=".$ssectionNaam."\">";
                    }
                }

				// Check if button isactive, start this
				if(isset($_POST['delObstacle'])){
					$vsectionid = $_POST['sectionId'];
					$vsectionname = $_POST['sectionName'];
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
						echo "<meta http-equiv=\"refresh\" content=\"0;URL=obstaclesSection.php?sectie=".$vsectionname."\">";
					 }
					 // if successful redirect to delete_multiple.php
					 if($STH){
						  echo "<meta http-equiv=\"refresh\" content=\"0;URL=obstaclesSection.php?sectie=".$vsectionname."\">";
					 }
				}else if(isset($_POST['addObstacle'])){
					$vsectionid = $_POST['sectionId'];
					$vsectionname = $_POST['sectionName'];      
					 if(is_numeric($_POST['volgnr'])){
						  //check if volgnr already exists
						  $STH1 = $db->query('select distinct Volgnr from TblObstacles
         									 where Section_id = '.$vsectionid);        
						  $STH1->setFetchMode(PDO::FETCH_ASSOC);
						  while($rows=$STH1->fetch()){
								if($_POST['volgnr'] == $rows['Volgnr']){
									echo '<script> alert("Er is al een hindernis met dit volgnummer!"); </script>';
									echo "<meta http-equiv=\"refresh\" content=\"0;URL=obstaclesSection.php?sectie=".$vsectionname."\">";
									exit;
								}
						  }
						  //Volgnr does not exist => insert
						  $STH = $db->prepare("INSERT INTO $tbl_name (Section_id, Volgnr, Omschr, MaxH, DatCreate, IndSecure) 
                                               VALUES ('$vsectionid', '$_POST[volgnr]', '$_POST[hindernisOmschr]', 
                                                       '$_POST[maxh]', '$_POST[datcreate]', '$_POST[obsSec]' )");
						  $STH->execute();
					 }else{
						echo '<script> alert("De hindernis heeft geen nummeriek volgnummer!"); </script>';
						echo "<meta http-equiv=\"refresh\" content=\"0;URL=obstaclesSection.php?sectie=".$vsectionname."\">";
					 }
					 // if successful redirect to delete_multiple.php
					 if($STH){
						  echo "<meta http-equiv=\"refresh\" content=\"0;URL=obstaclesSection.php?sectie=".$vsectionname."\">";
					 }
				}else if(isset($_POST['editObstacle'])){
				    $vsectionid = $_POST['sectionId'];
				    $vsectionname = $_POST['sectionName'];
					if(!empty($_POST['checkbox'])){
						if (count($_POST['checkbox'])<>1){
						    echo '<script> alert("Er mag maar een item geselecteerd worden bij bewerken!"); </script>';
						}else{
						    //determine which item is selected
						    foreach($_POST['checkbox'] as $val){
						        $sId = (int) $val;
						    }
						    //check if volgnr is numeriek
						    if(is_numeric($_POST['volgnr'])){
						        //check if volgnr already exists
						        $STH1 = $db->query('select distinct Volgnr from TblObstacles
             									   where Section_id = '.$vsectionid);
						        $STH1->setFetchMode(PDO::FETCH_ASSOC);
						        while($rows=$STH1->fetch()){
						           // echo $_POST['volgnr']."-".$rows['Volgnr'].";";
						            if($_POST['volgnr'] == $rows['Volgnr']){
						                $volgnrok=1;
						                break;
						            }else{
						                $volgnrok=0;     
						            }
						        }
						        //Volgnr gelijk aan opgegeven => update
						        if($volgnrok==1){
						            $sOmschr=$_POST['hindernisOmschr'];
						            if (strlen($sOmschr)<>0){
						                $STH = $db->prepare("UPDATE $tbl_name
                                                         SET Volgnr ='$_POST[volgnr]',
                                                             Omschr='$sOmschr',
                                                             MaxH='$_POST[maxh]',
                                                             DatCreate='$_POST[datcreate]',
                                                             IndSecure='$_POST[obsSec]'
                                                         WHERE Id = $sId");
						                $STH->execute();
						            }else{
						                echo '<script> alert("Omschrijving is niet gevuld!"); </script>';
						                echo "<meta http-equiv=\"refresh\" content=\"0;URL=obstaclesSection.php?sectie=".$vsectionname."\">";
						                exit;
						            }
						        }else{
						            echo '<script> alert("Het ingevulde volgnummer is anders dan de geselecteerde hindernis!"); </script>';
						            echo "<meta http-equiv=\"refresh\" content=\"0;URL=obstaclesSection.php?sectie=".$vsectionname."\">";
						            exit;
						        }
						        ;
						        
						    }else{
						        echo '<script> alert("De hindernis heeft geen nummeriek volgnummer!"); </script>';
						        echo "<meta http-equiv=\"refresh\" content=\"0;URL=obstaclesSection.php?sectie=".$vsectionname."\">";
						    }	    
						}
					}else{
					    echo '<script> alert("Er is niets geselecteerd om te bewerken!"); </script>';
					    echo "<meta http-equiv=\"refresh\" content=\"0;URL=obstaclesSection.php?sectie=".$vsectionname."\">";
					}
					// if successful redirect to delete_multiple.php
					if($STH){
					    echo "<meta http-equiv=\"refresh\" content=\"0;URL=obstaclesSection.php?sectie=".$vsectionname."\">";
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