<?php
/**
Each obstacle has checkpoints.
Using this page you can maintain the checkpoints for the obstacle.

copyright: 2013 Gerko Weening

20170702
solved undifined variable
20170705
solved undefined index when logged out

*/

include_once "../inc/base.php";
include_once "../inc/functions.php";

sec_session_start();
include_once "../common/header.php"; 

//secure login
if(login_check($mysqli) == true) { 

include_once "../common/leftColumn.php";

$tbl_name="TblObstacles"; // Table name
$tbl_name1="TblObstacleCheckpoints"; // Table name
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
        <table display:block>
        <tr >
        <td>
            <table id="obstacleTable">
                <a class="tableTitle2">Hindernis <?php echo $vsectionname,str_pad($vhindVolgnr,2,'0',STR_PAD_LEFT)?></a>

                <div class="cudWidget"> 
                   <a> <img src="<?php echo $imgPath,$vimg;?>" alt="" width="60" height="50" ></a>
                </div>

                <div id="widgetBar"> 
                    <a class="tableTitle4">Onderhouden hinderniscontrolepunten</a>
                </div>
            </table>

        <form name="form1" method="post" action="">
        <div id="RightColumnHalf">
        <table id="obstacleTableHalf">
            <tr class="theader">
                <th width="5%" ></th>
                <th ><strong>Controlepunten</strong></th>
                <th align="center">
                    <button type="submit" name="addMaterials" >
                        <img src="../img/forward.jpeg" width="40" height="40">
                    </button>    
                </th>
            </tr>

            <?php
            $STH1 = $db->query('SELECT * 
                                from TblCheckpoints 
                                where Terrein_id = "'.$_SESSION['Terreinid'].'"
                                order by Id');
            $STH1->setFetchMode(PDO::FETCH_ASSOC);
            while($rows=$STH1->fetch()){
            ?>

            <tr>
                <td width="5%" class="white"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['Id']; ?>"></td>
                <td colspan="2" class = "white"><?php echo htmlentities($rows['Omschr']); ?></td>
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
                <th colspan="2"><strong>Controlepunten in deze hindernis</strong></th>
                <th width="40%" align="center">
                    <button type="submit" name="delMaterials" >
                        <img src="../img/del.jpeg" width="40" height="40">
                    </button> 

                </th>
            </tr>
            <?php
            //hindernismaterialen ophalen
            $STH2 = $db->query('SELECT toc.Id, tc.Omschr from TblObstacleCheckpoints toc, TblCheckpoints tc where toc.Checkpoint_id = tc.Id and toc.Obstacle_id = '.$vhindId .' ');
            $STH2->setFetchMode(PDO::FETCH_ASSOC);
            //hindernismaterialen tonen
            while($rows=$STH2->fetch()){
            ?>
            <tr>
                <td width="5%" class="white"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['Id']; ?>"></td>
                <td colspan ="3" class = "white"><?php echo htmlentities($rows['Omschr']); ?></td>
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
                        $query = "INSERT INTO $tbl_name1 (Obstacle_id, Checkpoint_id) VALUES " ;           
                        $query .= "(".$vhindId.", ".$item.")";
                        $stmt = $db -> prepare($query);
                        $stmt->execute();
                    }
                }
                if($stmt){
                    echo "<meta http-equiv=\"refresh\" content=\"0;URL=editHindChk.php?Id=".$vhindId."&Sec=".$vsectionname."&Vnr=".$vhindVolgnr."&Img=".$vimg."\">";
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
                    echo "<meta http-equiv=\"refresh\" content=\"0;URL=editHindChk.php?Id=".$vhindId."&Sec=".$vsectionname."&Vnr=".$vhindVolgnr."&Img=".$vimg."\">";
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
