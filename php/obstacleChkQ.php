<?php
/**
Each obstacle has to be checked once or several times each year.
Using this page you can maintain the quarters in which an obstacle should be checked.

copyright: 2013 Gerko Weening

20170705
solved undefined index when logged out

*/

include_once "../inc/base.php";
include_once "../inc/functions.php";
include_once "../inc/queries.php";

sec_session_start(); 
include_once "../common/header.php"; 

//secure login
if(login_check($mysqli) == true) { 

include_once "../common/leftColumn.php";
$tbl_name="TblObstacles"; // Table name

?>

<html>
    <head>
    </head>        
    <body id="chkq">
        <div id="LeftColumn2">
            
        </div>
        <div id="RightColumn">
        <table display:block>
        <tr >
        <td>
        <form name="form1" method="post" action="">
            <table id="materialenTable">
                <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Agenda hinderniscontrole</a>
                <div class="cudWidget"> 
                    <button class="submitbtn" type="submit" name="save">
                        <img src="../img/save.jpeg" width="35" height="35">
                    </button>
                </div>
                    <div id="widgetBar">
                        <br>
                        <a>Geef aan in welk(e) kwarta(a)l(en) de hindernis gecontroleerd wordt!</a>
                    </div>
                <tr class="theader">
                    <th width="15%"><strong>Hindernis</strong></th>
                    <th width="30%"><strong>Omschrijving</strong></th>
                    <th ><strong>Kwartaal 1</strong></th>
                    <th ><strong>Kwartaal 2</strong></th>
                    <th ><strong>Kwartaal 3</strong></th>
                    <th ><strong>Kwartaal 4</strong></th>
                </tr>

                <?php
		$whereTerrein = getterreinid();
                $STH = $db->query('select tos.*, tss.naam 
                                    from TblObstacles as tos, TblSections as tss 
                                    where tos.section_id = tss.id 
                                                    and tss.'.$whereTerrein.' 
                                    order by naam,Volgnr');
                $STH->setFetchMode(PDO::FETCH_ASSOC);
                while($rows=$STH->fetch()){
                ?>

                <tr>
                    <td width="15%" class = "white"><?php echo htmlentities($rows['naam']),htmlentities($rows['Volgnr']); ?>
                        <span class="white-text" style="margin-left: 1em;">
                        <img src="<?php echo $imgPath,$rows['ImgPath'];?>" alt="" width="55" height="38" ></td>
                    <td width="30%" class = "white"><?php echo htmlentities($rows['Omschr']); ?></td>
                    <td width="15%" class="white"><input name="checkQ1[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['Id']; ?>" <?php echo $rows["ChkQ1"] ? 'checked="checked"' : ''; ?>></td>
                    <td width="15%" class="white"><input name="checkQ2[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['Id']; ?>" <?php echo $rows["ChkQ2"] ? 'checked="checked"' : ''; ?>></td>
                    <td width="15%" class="white"><input name="checkQ3[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['Id']; ?>" <?php echo $rows["ChkQ3"] ? 'checked="checked"' : ''; ?>></td>
                    <td width="15%" class="white"><input name="checkQ4[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['Id']; ?>" <?php echo $rows["ChkQ4"] ? 'checked="checked"' : ''; ?>></td>
                </tr>

                <?php
                }
                // Check if savee button active, start this
                if(isset($_POST['save'])){
                    //var_dump($_POST);
                    //print_r($_POST['checkQ1']); 
                    if(!empty($_POST['checkQ1'])){
                            foreach($_POST['checkQ1'] as $val){
                                $ids1[] = (int) $val;
                            }
                            $ids1 = implode("','", $ids1);
                            $STH1a = $db->prepare("UPDATE $tbl_name SET ChkQ1 = 1 WHERE Id IN ('".$ids1."')");
                            $STH1a->execute();
                            $STH1b = $db->prepare("UPDATE $tbl_name SET ChkQ1 = 0 WHERE Id not IN ('".$ids1."')");
                            $STH1b->execute();
                    }else {
                            $STH1c = $db->prepare("UPDATE $tbl_name SET ChkQ1 = 0");
                            $STH1c->execute();
                    }
                    if(!empty($_POST['checkQ2'])){
                            foreach($_POST['checkQ2'] as $val){
                                $ids2[] = (int) $val;
                            }
                            $ids2 = implode("','", $ids2);
                            $STH2a = $db->prepare("UPDATE $tbl_name SET ChkQ2 = 1 WHERE Id IN ('".$ids2."')");
                            $STH2a->execute();
                            $STH2b = $db->prepare("UPDATE $tbl_name SET ChkQ2 = 0 WHERE Id not IN ('".$ids2."')");
                            $STH2b->execute();
                    }else {
                            $STH2c = $db->prepare("UPDATE $tbl_name SET ChkQ2 = 0");
                            $STH2c->execute();
                    }
                    if(!empty($_POST['checkQ3'])){
                            foreach($_POST['checkQ3'] as $val){
                                $ids3[] = (int) $val;
                            }
                            $ids3 = implode("','", $ids3);
                            $STH3a = $db->prepare("UPDATE $tbl_name SET ChkQ3 = 1 WHERE Id IN ('".$ids3."')");
                            $STH3a->execute();
                            $STH3b = $db->prepare("UPDATE $tbl_name SET ChkQ3 = 0 WHERE Id not IN ('".$ids3."')");
                            $STH3b->execute();
                    }else {
                            $STH3c = $db->prepare("UPDATE $tbl_name SET ChkQ3 = 0");
                            $STH3c->execute();
                    }
                    if(!empty($_POST['checkQ4'])){
                            foreach($_POST['checkQ4'] as $val){
                                $ids4[] = (int) $val;
                            }
                            $ids4 = implode("','", $ids4);
                            $STH4a = $db->prepare("UPDATE $tbl_name SET ChkQ4 = 1 WHERE Id IN ('".$ids4."')");
                            $STH4a->execute();
                            $STH4b = $db->prepare("UPDATE $tbl_name SET ChkQ4 = 0 WHERE Id not IN ('".$ids4."')");
                            $STH4b->execute();
                    }else {
                            $STH4c = $db->prepare("UPDATE $tbl_name SET ChkQ4 = 0");
                            $STH4c->execute();
                    }
                    //refresh page
                    if($STH1a or $STH1b or $STH1c or $STH2a or $STH2b or $STH2c or $STH3a or $STH3b or $STH3c or $STH4a or $STH4b or $STH4c){
                       echo "<meta http-equiv=\"refresh\" content=\"0;URL=obstacleChkQ.php\">";
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
    