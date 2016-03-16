<?php>

/**
Shows the details of the selected obstacle.

copyright: 2013 Gerko Weening
*/

include_once "../inc/base.php";
include_once "../inc/functions.php";

sec_session_start(); 
include_once "../common/header.php"; 
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
$vimg=$row['ImgPath'];
//secure login
if(login_check($mysqli) == true) { 
?>
<html>
    <head>
        <script type="text/JavaScript" src="../js/getObstacle.js"></script>
    </head>
    <body id="sections">
        <div id="LeftColumn2a">
                 <? include "obstacleOverviewPerSection.php"; ?>
        </div>

        <div id="RightColumn">
        <table id="obstacleTable">
            <tr >     
                <td class="tableTitle2">Hindernis <?echo $vsectionname,str_pad($vhindVolgnr,2,'0',STR_PAD_LEFT)?></td>
                <div class="cudWidget">
                </div>
            </tr>
            <tr>    
                <div id="widgetBar">
                    <ul class="basictab">
                        <li class="selected"><a href="hindernis.php">Hindernisdetails</a></li>
                        <li><a href="hindernisControles.php?hId=<?echo $vhindId;?>&Sec=<?echo $vsectionname;?>&Vnr=<?echo $vhindVolgnr;?>&Img=<?echo $vimg;?>">Hindernis controles</a></li>
                    </ul>
                </div>
            </tr> 
            <tr valign="top">
                <td class="hwhite" width ="50%">
                    <br><br><br><br>
                    <label>Sectie:</label> <?echo $vsectionname; ?><br>
                    <label>Volgnummer:</label> <?echo str_pad($vhindVolgnr,2,'0',STR_PAD_LEFT);?> <br><br>
                    <label>Omschrijving:</label><br> <? echo $vhindOmschr;?><br><br>
                    Hindernis controleren in:<br>
                    <? if($row['ChkQ1']== True){?> Kwartaal 1 <?}?>
                    <? if($row['ChkQ2']== True){?> Kwartaal 2 <?}?>
                    <? if($row['ChkQ3']== True){?> Kwartaal 3 <?}?>
                    <? if($row['ChkQ4']== True){?> Kwartaal 4 <?}?>
                </td>
                <td class="hwhite">
                    <br><br><br><br>
                    <img src="<?echo $imgPath,$vimg;?>" alt="" width="300" height="200" >
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
                    <input type="file" name="file" id="file" >
                    <input type="submit" name="fileImport" value="Importeren" float="right">
                    </form><br>
                </td>
            </tr>

        </table>
        <form name="form1" method="post" action="">
        <div id="RightColumnHalf">
        <table id="obstacleTableHalf">
            <tr class="theader">
                <th ><strong>Hindernismaterialen</strong></th>
                <th align="center">
                <button type="submit" name="editHindMaterials" >
                    <img src="../img/edit.jpeg" width="35" height="35">
                </button>    
                </th>

            </tr>    
            <?php
            //hindernismaterialen ophalen
            $STH = $db->query('SELECT tm.Omschr, tom.Aantal from TblObstacleMaterials tom, TblMaterials tm where tom.Material_id = tm.Id and tom.Obstacle_id ='.$vhindId .' ');
            $STH->setFetchMode(PDO::FETCH_ASSOC);
            //hindernismaterialen tonen
            while($rows=$STH->fetch()){
            ?>
            <tr>
                <td class = "white"><? echo htmlentities($rows['Omschr']); ?></td>
                <td class = "white"><? echo htmlentities($rows['Aantal']); ?></td>
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
            $STH = $db->query('SELECT tc.Omschr from TblObstacleCheckpoints toc, TblCheckpoints tc where toc.Checkpoint_id = tc.Id and toc.Obstacle_id = '.$vhindId .' ');
            $STH->setFetchMode(PDO::FETCH_ASSOC);
            //hindernismaterialen tonen
            while($rows=$STH->fetch()){
            ?>
            <tr>
                <td colspan="2" class = "white"><? echo htmlentities($rows['Omschr']); ?></td>
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
U bent niet geautoriseerd voor toegang tot deze pagina. <a href="index.php">Inloggen</a> alstublieft.
<?php
}
?>