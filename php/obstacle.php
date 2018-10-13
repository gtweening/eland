<?php
/**
Shows the details of the selected obstacle.

copyright: 2013 Gerko Weening

20170705
solved undefined index when logged out
<<<<<<< ours
20171222
added materialtype and materialdetail in view
20180228
improved aspect ratio of preview of picture
20180903
revised

=======
20180228
improved aspect ratio of preview of picture
>>>>>>> theirs
*/

include_once "../inc/base.php";
include_once "../inc/functions.php";

sec_session_start(); 
include_once "../common/header.php"; 

//secure login
if(login_check($mysqli) == true) { 

include_once "../common/leftColumn.php";
$tbl_name="TblObstacles"; // Table name
$vhindId=$_GET['Id'];
$vsectionname=$_GET['Sec'];
$vhindVolgnr=$_GET['Vnr'];

$STH = $db->query('SELECT * from '.$tbl_name.' where Id ="'.$vhindId.'"');
$STH->setFetchMode(PDO::FETCH_ASSOC);
$row=$STH->fetch();
$vhindVolgnr=str_pad($row['Volgnr'],2,'0', STR_PAD_LEFT);
$vhindOmschr=$row['Omschr'];
$vhindDat=$row['DatCreate'];
$vhindH=$row['MaxH'];
$vhindSec=$row['IndSecure'];
if(empty($vhindSec)){
    $vhindSec=0;
};
$vimg=$row['ImgPath'];
$optObsSec = array("niet opgegeven",
    "Door SBN goedgekeurd materiaal",
    "Taak-Risico-Analyse",
    "Constructieberekening",
    "Labels" );

?>

<html>
    <head>
        <script type="text/JavaScript" src="../js/getObstacle.js"></script>
    </head>
    
    <body id="sections">
        <div id="LeftColumn2a">
              <?php include "obstacleOverviewPerSection.php"; ?>
        </div>

        <div id="RightColumn">
        <table id="obstacleTable">   
                <a class="tableTitle2">Hindernis <?php echo $vsectionname,str_pad($vhindVolgnr,2,'0',STR_PAD_LEFT)?></a>
                <div class="cudWidget">
                </div>
              
                <div id="widgetBartab">
                    <ul class="basictab">
                        <li class="selected">
                            <a href="obstacle.php?hId=<?php echo $vhindId;?>&Sec=<?php echo $vsectionname;?>&Vnr=<?php echo $vhindVolgnr;?>&Img=<?php echo $vimg;?>">Hindernisdetails</a></li>
                        <li>
                            <a href="hindernisControles.php?hId=<?php echo $vhindId;?>&Sec=<?php echo $vsectionname;?>&Vnr=<?php echo $vhindVolgnr;?>&Img=<?php echo $vimg;?>">Hindernis controles</a></li>
                    </ul>
                </div>
   
            <tr valign="top">
                <td class="hwhite" width ="50%">
                    <br><br>
                    <label>Sectie:</label> <?php echo $vsectionname; ?><br>
                    <label>Volgnummer:</label> <?php echo str_pad($vhindVolgnr,2,'0',STR_PAD_LEFT);?> <br>
                    <label>Gebouwd op:</label> <?php echo $vhindDat; ?><br>
                    <label>Hoogte:</label> <?php echo $vhindH; ?>m<br>
                    <label>Veilig door:</label> <?php echo $optObsSec[$vhindSec]; ?><br>
                    <label>Omschrijving:</label><br> <?php  echo $vhindOmschr;?><br><br>
                    <lblgrey>Hindernis controleren in:</lblgrey><br>
                    <?php if($row['ChkQ1']== True){?> Kwartaal 1 <?php }?>
                    <?php if($row['ChkQ2']== True){?> Kwartaal 2 <?php }?>
                    <?php if($row['ChkQ3']== True){?> Kwartaal 3 <?php }?>
                    <?php if($row['ChkQ4']== True){?> Kwartaal 4 <?php }?>
                </td>
                <td class="hwhite">
                    <br><br>

<!--
                    <img src="<?php echo $imgPath,$vimg;?>" alt="" width="300" height="200" >
-->

                    <?php showObsPic($imgPath,$vimg,300,200); ?>
                    <br><br>
                </td>
            </tr>
            <tr>
                <td class = "hwhite" colspan="2">
                    <form action="upload_file.php" method="post" enctype="multipart/form-data">
                    <label for="file">Bestand:</label>
                    <input type="hidden" name="hindId" value="<?php echo $vhindId;?>">
                    <input type="hidden" name="hindSec" value="<?php echo $vsectionname;?>">
                    <input type="hidden" name="hindVolgnr" value="<?php echo $vhindVolgnr;?>">
                    <input type="hidden" name="imgPath" value="<?php echo $imgPath;?>">
                    <input type="hidden" name="vimg" value="<?php echo $vimg;?>">
                    <input type="file" name="file" id="file" >
                    <input class="cudWidget" type="image" name="fileDelete" src="../img/del.jpeg" 
                           value="Verwijderen" >
                    <input class="cudWidget" type="image" name="fileImport" src="../img/save.jpeg" 
                           value="Opslaan" >
                    </form><br>
                </td>
            </tr>
        </table>
        
        <form name="form1" method="post" action="">
        <div id="RightColumnHalf">
        <table id="obstacleTableHalf">
            <tr class="theader">
                <th colspan="2"><strong>Hindernismaterialen</strong></th>
                <th></th>
                <th  align="center">
                <button type="submit" name="editHindMaterials" >
                    <img src="../img/edit.jpeg" width="35" height="35">
                </button>    
                </th>
            </tr>  
              
            <?php
            //hindernismaterialen ophalen
            $STH = $db->query('SELECT tm.Omschr, tom.Aantal, tm.*, tmt.Omschr as tmtomschr  
                               from TblObstacleMaterials tom, TblMaterials tm, TblMaterialTypes tmt 
                               where tom.Material_id = tm.Id and tmt.Id=tm.MaterialType_Id 
                                 and tom.Obstacle_id ='.$vhindId .' ');
            $STH->setFetchMode(PDO::FETCH_ASSOC);
            //hindernismaterialen tonen
            while($rows=$STH->fetch()){
            	$isrope = htmlentities($rows['IndSecureRope']);
          		$imrope = htmlentities($rows['IndMainRope']);
          		$srope="";
          		$mrope="";
          		if($isrope==1){$srope="Veiligheidstouw";}
          		if($imrope==1){$mrope="Hoofdtouw";}
            ?>
            
            <tr>
                <td class = "white"><?php echo htmlentities($rows['tmtomschr']); ?></td>
                <td class = "white"><?php echo htmlentities(utf8_encode($rows['Omschr'])); ?></td>
                <td class = "white"><?php echo htmlentities($rows['Aantal']); ?></td>
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
                <th ><strong>Hinderniscontrolepunten</strong></th>
                <th align="center">
                <button type="submit" name="editHindChecks" >
                    <img src="../img/edit.jpeg" width="35" height="35">
                </button>  
                </th>

            </tr>
            
            <?php
            //hindernismaterialen ophalen
            $STH = $db->query('SELECT tc.Omschr 
                               from TblObstacleCheckpoints toc, TblCheckpoints tc 
                               where toc.Checkpoint_id = tc.Id and toc.Obstacle_id = '.$vhindId .' ');
            $STH->setFetchMode(PDO::FETCH_ASSOC);
            //hindernismaterialen tonen
            while($rows=$STH->fetch()){
            ?>
            
            <tr>
                <td colspan="2" class = "white"><?php echo htmlentities($rows['Omschr']); ?></td>
            </tr>

            <?php
            }
            ?>
            
        </table>
         <?php
         if(isset($_POST['editHindMaterials'])){
             echo "<meta http-equiv=\"refresh\" content=\"0;URL=editHindMat.php?Id=".$vhindId."&Sec=".$vsectionname."&Vnr=".$vhindVolgnr."&Img=".$vimg."\">";
         }else if(isset($_POST['editHindChecks'])){
             echo "<meta http-equiv=\"refresh\" content=\"0;URL=editHindChk.php?Id=".$vhindId."&Sec=".$vsectionname."&Vnr=".$vhindVolgnr."&Img=".$vimg."\">";
         }

         //close connection
         $db = null;
         ?>
        </div>
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