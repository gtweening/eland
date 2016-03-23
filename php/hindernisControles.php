<?php>

/**
Shows the tabpage inside obstacle showing the results of the checks performed.

copyright: 2013 Gerko Weening
*/

include_once "../inc/base.php";
include_once "../inc/functions.php";

sec_session_start();
include_once "../common/header.php"; 
include_once "../common/leftColumn.php";

$tbl_name="TblObstacles"; // Table name
$tbl_name1="TblObstacleChecks"; // Table name

$vhindId=$_GET['hId'];
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
        <script type="text/javascript">
            function editFunction(CheckNote,Id,hindid,sectionname,volgnr){
                var currentTime=new Date();
                var month = currentTime.getMonth() + 1
                var day = currentTime.getDate()
                var year = currentTime.getFullYear()
                var OmschrNew=prompt("Vul de notitie aan:\n"+CheckNote+ ";\n"+year + "/"+ month + "/"+ day + ":","");
                var Omschr = CheckNote.replace("\n","\\n");
                if (OmschrNew!=null){    
                    Omschr += ";\\n";
                    Omschr += year + "/"+ month + "/"+ day + ":";
                    Omschr += OmschrNew;
                }
                //alert(Omschr);
                if (Omschr!=null){
                    window.location.href = "hindernisControles.php?Id=" + Id + 
                        "&Note="+Omschr +"&hId="+hindid+"&Sec="+sectionname+"&Vnr="+volgnr;
                }
            }
        </script>
    </head>
    <body id="sections">
        <div id="LeftColumn2a">
            <? include "obstacleOverviewPerSection.php"; ?>
        </div>
        
        <div id="RightColumn">
        <table id="obstacleTable">
               
                <a class="tableTitle2">Hindernis <?echo $vsectionname,$vhindVolgnr?></a>
                <a > <img src="<?echo $imgPath,$vimg;?>" alt="" width="60" height="50" ></a>
                <div class="cudWidget">
                </div>
      
                <div id="widgetBar">
                    <ul class="basictab">
                        <li ><a href="obstacle.php?Id=<?echo $vhindId;?>&Sec=<?echo $vsectionname;?>&Vnr=<?echo $vhindVolgnr;?>&Img=<?echo $vimg;?>">Hindernisdetails</a></li>
                        <li class="selected"><a href="hindernisControles.php">Hindernis controles</a></li>
                    </ul>
                </div>
      
        </table>
        <form name="form1" method="post" action="">
        <table id="obstacleTable" >
            <tr>
                <td width="5%"></td>
                <div class="cudWidget"> 
                    <button type="submit" name="delCheck">
                        <img src="../img/del.jpeg" width="35" height="35">
                    </button>
                </div>
                <div class="cudWidget">
                    <button type="submit" name="editCheck">
                        <img src="../img/edit.jpeg" width="35" height="35">
                    </button>
                </div>
                
            </tr>
            <br><br><br>
            <tr>    
                <div id="widgetBar">
                    <input type="date" class="inputText" name="datum" maxlength="10" size="8">
                    <input type="checkbox" class="inputText" name="status" >
                    <input type="text" class="inputText" name="controleur" maxlength="15" size="10">
                    <textarea rows="2" cols="33" name="note" >
                    </textarea>

                    <div class="cudWidget">
                        <button type="submit" name="addCheck" float="right">
                            <img src="../img/add.jpeg" width="35" height="35">
                        </button>
                    </div>
                </div>
            </tr>   
            <tr class="theader">
                <th width="5%" ></th>
                <th width="15%"><strong>Datum</strong></th>
                <th ><strong>Status</strong></th>
                <th ><strong>Controleur</strong></th>
                <th ><strong>Notitie</strong></th>
            </tr>
            <?php

            $STH = $db->query('Select * from '.$tbl_name1.' where Obstacle_id = '.$vhindId.' ');
            $STH->setFetchMode(PDO::FETCH_ASSOC);
            while($rows=$STH->fetch()){
            ?>

            <tr>
                <td width="5%" class="white"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<? echo $rows['Id']; ?>"></td>
                <td class = "white"><? echo htmlentities($rows['DatCheck']); ?></td>
                <td class = "white" >
                        <? if($rows['ChkSt']== FALSE){?>
                              <img src="../img/warning.jpeg" width="20" height="20"><?
                           }else{?>
                              <img src="../img/ok.jpeg" width="20" height="20"><?
                           }; ?>
                </td>
                <td class = "white" ><? echo htmlentities($rows['CheckedBy']); ?></td>
                <td class = "white" ><? echo nl2br(htmlentities($rows['Note'])); ?></td>
            </tr>

            <?php
            }

            if(isset($_POST['delCheck'])){
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
                        echo "<meta http-equiv=\"refresh\" content=\"0;URL=hindernisControles.php?hId=".$vhindId."&Sec=".$vsectionname."&Vnr=".$vhindVolgnr."&Img=".$vimg."\">";
                    }
                }else if(isset($_POST['addCheck'])){
                        if(isset($_POST['status']) && $_POST['status']==TRUE){
                            $status="1";
                        }else{
                            $status="0";
                        }
                        if(!empty($_POST['datum'])){
                            $STH = $db->prepare("INSERT INTO $tbl_name1 (Obstacle_id, DatCheck, ChkSt, CheckedBy, Note) VALUES
                            ('$vhindId', '$_POST[datum]' , '$status', '$_POST[controleur]', '$_POST[note]')");
                            $STH->execute();
                        }
                        // if successful redirect to delete_multiple.php
                        if($STH){
                            echo "<meta http-equiv=\"refresh\" content=\"0;URL=hindernisControles.php?hId=".$vhindId."&Sec=".$vsectionname."&Vnr=".$vhindVolgnr."&Img=".$vimg."\">";
                        }
                }else if(isset($_POST['editCheck'])){
                    if(!empty($_POST['checkbox'])){
                        foreach($_POST['checkbox'] as $val){
                            $ids[] = (int) $val;
                        }
                        $ids = implode("','", $ids);
                       $STH = $db->query('select * FROM '.$tbl_name1.' WHERE Id = '.$ids.'');
                       $STH->setFetchMode(PDO::FETCH_ASSOC);
                       $row=$STH->fetch();
                       //$CheckNote=$row['Note']
                       $CheckNote = str_replace("\n","\\n",$row['Note']);
                       //echo $CheckNote.$ids.$vhindId.$vsectionname.$vhindVolgnr;   
                       //call jscript
                       echo '<script> editFunction("'.$CheckNote.'", "'.$ids.'","'.$vhindId.'","'.$vsectionname.'","'.$vhindVolgnr.'"); </script>';   
                    }
                }
                if(isset($_GET['Note'])){
                  $sOmschr = $_GET['Note'];
                  $sId = $_GET['Id'];
                  $STH = $db->prepare("UPDATE $tbl_name1 SET Note = '".$sOmschr."' WHERE Id = $sId");
                  $STH->execute();
                  // if successful redirect to delete_multiple.php
                    if($STH){
                        echo "<meta http-equiv=\"refresh\" content=\"0;URL=hindernisControles.php?hId=".$vhindId."&Sec=".$vsectionname."&Vnr=".$vhindVolgnr."&Img=".$vimg."\">";
                    }
                }
            ?>
        </table>
        </form>
        </div>
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

