<?php

/**
Each obstacle has some points which should be checked.
Using this page you can maintain your checkpoint library.

copyright: 2013 Gerko Weening

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
$tbl_name="TblCheckpoints"; // Table name
$Terreinid = $_SESSION['Terreinid']; //sessions terreinid
?>

<html>
<head>
    <script type="text/javascript">
        function editChkPointFunction(value,ids){
            var Materiaal=prompt("Verander de omschrijving van het controlepunt",value);
            if (Materiaal!=null){
                     window.location.href = "checkpoints.php?var1=" + Materiaal + "&var2="+ids;
            }
        }
    </script>
</head>

<body id="checkpoints">
    <div id="LeftColumn2">         
    </div>

    <div id="RightColumn">
    <table display:block>
    <tr >
    <td>
    <form name="form1" method="post" 
			action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
        <table id="materialenTable">
            <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Controle punten</a>
            <div class="cudWidget"> 
                <button class="submitbtn" type="submit" name="delChkPoint">
                    <img src="../img/del.jpeg" width="35" height="35">
                </button>
            </div>
            <div class="cudWidget">
                <button class="submitbtn" type="submit" name="editChkPoint">
                    <img src="../img/edit.jpeg" width="35" height="35">
                </button>
            </div>    
            <div id="widgetBar">
                <input type="text" class="inputText" name="CheckPoint" maxlength="50" 
                                                                    size="32">
                <div class="cudWidget">
                    <button class="submitbtn" type="submit" name="addChkPoint" 
                                                                                    float="right">
                        <img src="../img/add.jpeg" width="35" height="35">
                    </button>
                </div>
            </div>

            <tr class="theader">
                <th width="5%" ></th>
                <th ><strong>Omschrijving</strong></th>
            </tr>

            <?php
            $whereTerrein = getterreinid();
            $STH = $db->query('SELECT * from TblCheckpoints 
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

            if(isset($_GET['var1'])){
                $sOmschr = $_GET['var1'];
                $sId = $_GET['var2'];
                $STH = $db->prepare("UPDATE $tbl_name SET Omschr = '".$sOmschr."' WHERE Id = $sId");
                $STH->execute();
                // if successful redirect to delete_multiple.php
                if($STH){
                    echo "<meta http-equiv=\"refresh\" content=\"0;URL=checkpoints.php\">";
                }
            }

            if(isset($_POST['delChkPoint'])){
                //var_dump($_POST);
                //$del_id = $_POST['checkbox'];
                //print_r($_POST['checkbox']);   
                if(!empty($_POST['checkbox'])){
                   foreach($_POST['checkbox'] as $val){
                       $ids[] = (int) $val;
                       //controller of dit controlepunt nog gebruikt wordt
                       $qry1 = "Select distinct Checkpoint_id 
                               from TblObstacleCheckpoints ";
                       $STH1=$db->prepare($qry1);
                       $STH1->execute();
                       //$STH1->setFetchMode(PDO::FETCH_ASSOC);
                       while($rows=$STH1->fetch(PDO::FETCH_ASSOC)){
                           if($rows['Checkpoint_id']==$val){
                               echo '<script> alert("Dit controlepunt wordt gebruikt voor een hindernis.\nVerwijderen niet mogelijk"); </script>';
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
                   echo "<meta http-equiv=\"refresh\" content=\"0;URL=checkpoints.php\">";
                }      
            }else if(isset($_POST['addChkPoint'])){      
                if(!empty($_POST['CheckPoint'])){
                        $STH = $db->prepare("INSERT INTO $tbl_name (Omschr, Terrein_id) VALUES
                        ('$_POST[CheckPoint]', ".$Terreinid.")");
                        $STH->execute();
                }else{
                    echo '<script> alert("De controlepuntomschrijving moet nog ingevuld worden!"); </script>';
                }
                // if successful redirect to delete_multiple.php
                if($STH){
                    echo "<meta http-equiv=\"refresh\" content=\"0;URL=checkpoints.php\">";
                }
            }else if(isset($_POST['editChkPoint'])){
                if(!empty($_POST['checkbox'])){
                    foreach($_POST['checkbox'] as $val){
                        $ids[] = (int) $val;
                    }
                    $ids = implode("','", $ids);
                   $STH = $db->query('select Omschr FROM '.$tbl_name.' WHERE Id = '.$ids.'');
                   $STH->setFetchMode(PDO::FETCH_ASSOC);
                   $row=$STH->fetch();
                   $value=$row['Omschr'];
                   echo $ids."<br>";
                   echo $_POST['CheckPoint'];
                   //call jscript
                   echo '<script> editChkPointFunction("'.$value.'", "'.$ids.'"); </script>';   
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


