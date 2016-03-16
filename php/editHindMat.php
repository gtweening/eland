<?php>


include_once "../inc/base.php";
include_once "../inc/functions.php";

sec_session_start(); 
include_once "../common/header.php"; 
include_once "../common/leftColumn.php";

$tbl_name="TblObstacles"; // Table name
$tbl_name1="TblObstacleMaterials"; // Table name
$vsectionname=$_GET['Sec'];
$vhindVolgnr=$_GET['Vnr'];
$vhindId=$_GET['Id'];
$vimg=$_GET['Img'];
//secure login
if(login_check($mysqli) == true) { 
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
            <? include "obstacleOverviewPerSection.php"; ?>
        </div>
        <div id="RightColumn">
        <table display:block>
        <tr >
        <td>
            <table id="obstacleTable">
                <tr>
                     <td class="tableTitle2">Hindernis <?echo $vsectionname,str_pad($vhindVolgnr,2,'0',STR_PAD_LEFT)?></td>
                     <td> <img src="<?echo $imgPath,$vimg;?>" alt="" width="60" height="50" ></td>
                </tr>
                <tr>
                    <td class="tableTitle4">Onderhouden hindernismaterialen</td>
                    <div class="cudWidget">
                    </div>
                </tr>
                <tr>    
                    <div id="widgetBar"> 
                        <div class="cudWidget">        
                        </div>
                    </div>
                </tr> 

            </table>
        <form name="form1" method="post" action="">
        <div id="RightColumnHalf">
        <table id="obstacleTableHalf">
            <tr class="theader">
                <th width="5%" ></th>
                <th ><strong>Materialen</strong></th>
                <th align="center">
                    <button type="submit" name="addMaterials" >
                        <img src="../img/forward.jpeg" width="40" height="40">
                    </button>    
                </th>
            </tr>

            <?php
            $STH1 = $db->query('SELECT * from TblMaterials order by Id');
            $STH1->setFetchMode(PDO::FETCH_ASSOC);
            while($rows=$STH1->fetch()){
            ?>

            <tr>
                <td width="5%" class="white"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<? echo $rows['Id']; ?>"></td>
                <td colspan="2" class = "white"><? echo htmlentities($rows['Omschr']); ?></td>
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
                <th colspan="2"><strong>Materialen in deze hindernis</strong></th>
                <th width="40%" align="center">
                    <button type="submit" name="delMaterials" >
                        <img src="../img/del.jpeg" width="40" height="40">
                    </button> 
                    <button type="submit" name="editMaterials" >
                        <img src="../img/edit.jpeg" width="40" height="40">
                    </button>    
                </th>
            </tr>
            <?php
            //hindernismaterialen ophalen
            $STH2 = $db->query('SELECT tom.Id, tm.Omschr, tom.Aantal from TblObstacleMaterials tom, TblMaterials tm where tom.Material_id = tm.Id and tom.Obstacle_id ='.$vhindId.' ');
            $STH2->setFetchMode(PDO::FETCH_ASSOC);
            //hindernismaterialen tonen
            while($rows=$STH2->fetch()){
            ?>
            <tr>
                <td width="5%" class="white"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<? echo $rows['Id']; ?>"></td>
                <td colspan ="2" class = "white"><? echo htmlentities($rows['Omschr']); ?></td>
                <td class = "white"><? echo htmlentities($rows['Aantal']); ?></td>
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
U bent niet geautoriseerd voor toegang tot deze pagina. <a href="index.php">Inloggen</a> alstublieft.
<?php
}
?>
