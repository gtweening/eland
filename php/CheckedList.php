<?php
/**
Once the obstacle are checked the adminstration should be updated.
Based on the selected year and quarte you get a list of obstacles which should be checked.
You can maintain the check results.

copyright: 2013 Gerko Weening

20170702
improved view of list
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
$tbl_name1="TblObstacleChecks"; // Table name
$ChkQ = "";
$datend = "";

//bepaal op basis van opgegeven jaar en kwartaal
//de controleperiode die beschouwd moet worden
if(isset($_GET['ChkQ'])){
    $ChkQ=$_GET['ChkQ'];
    $datend=$_GET['datend'];
}else{
    $checkperiod = getcheckperiod($_POST['jaar'], $_POST['kwartaal']);
    $datend = $checkperiod[1];
    $ChkQ=$checkperiod[0];
}
//selecteer items te controleren uit gekozen kwartaal;
//selecteer items gecontroleerd uit voorgaande kwartalen die niet OK zijn.
//selecteer enkel hindernis; overige info via aparte queries ophalen
//$qry1 = getviewtobechecked($checkperiod[0],$datend);
$qry1 = getviewtobechecked($ChkQ,$datend);

?>
<html>
    <head>
        <script type="text/javascript">
            
        </script>
    </head>

    <body id="checked">
        <div id="LeftColumn2">
            
        </div>
        <div id="RightColumn">
        <table display:block>
        <tr >
        <td>
        <form name="form1" method="post" action="frmHandlingChkLst.php">
            <table id="materialenTable" >
                <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Gecontroleerde hindernissen</a>
                <div class="cudWidget">
                    <button class="submitbtn" type="submit" name="delCheck">
                        <img src="../img/del.jpeg" width="35" height="35">
                    </button>
                </div>
                <div id="widgetBar">
                    <input type="date" class="inputText" name="cdatum" maxlength="10" size="8"
                           value="<?php echo date('Y-m-d'); ?>">
                    <input type="checkbox" class="inputText" name="cstatus" >
                    <input type="text" class="inputText" name="ccontroleur" maxlength="10" size="12" value="controleur">
                    <input type="hidden" name="ChkQ" value="<?php echo $ChkQ;?>">
                    <input type="hidden" name="datend" value="<?php echo $datend;?>">
                    <textarea rows="1.7" cols="27" name="cnote">
                    </textarea>
                    <div class="cudWidget">
                        <button class="submitbtn" type="submit" name="addCheck" float="right">
                            <img src="../img/refresh.jpeg" width="35" height="35">
                        </button>
                    </div>
                </div>
                <tr class="theader">
                    <th width="5%" ></th>
                    <th width="15%"><strong>Naam</strong></th>
                    <th width="30%"><strong>Omschrijving</strong></th>
                    <th width="10%"><strong>Datum</strong></th>
                    <th width="5%"><strong>Status</strong></th>
                    <th width="15%"><strong>Controleur</strong></th>
                    <th width="20%"><strong>Opmerking</strong></th>
                </tr>

                <?php
                $STH = $db->query($qry1);
                $STH->setFetchMode(PDO::FETCH_ASSOC);
                while($rows=$STH->fetch()){
                    //selecteer controles van hindernis
                    $query4 = "select tob.*, tobc.DatCheck, tobc.ChkSt, tobc.CheckedBy,tobc.Note ";
                    $query4 .="from TblObstacles tob left join TblObstacleChecks tobc on (tob.id=tobc.obstacle_id) ";
                    $query4 .= "where tob.Id =". $rows['Id']." and ";
                    $query4 .= "DatCheck between '$datend' and DATE_ADD('$datend', INTERVAL +3 MONTH) ";
                    
                ?>
                <tr >
                    <td width="5%" class="white"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['Id']; ?>"></td>
                    <td width="15%" class = "white" ><?php echo htmlentities($rows['Naam']),htmlentities($rows['Volgnr']); ?>
                        <span class="white-text" style="margin-left: 1em;">
                        <img src="<?php echo $imgPath,$rows['ImgPath'];?>" alt="" width="55" height="38" ></td>
                    <td  width="30%" class = "white" ><?php echo htmlentities($rows['Omschr']); ?></td>
                    <?php
                    $STH4 = $db->query($query4);
						  $i=1;
                    while($rows4=$STH4->fetch()){
                        if($i>1){
                               echo "<td></td><td></td><td></td>";
                        }
                    ?>

                    <td width="10%" class = "white"><?php echo htmlentities($rows4['DatCheck']); ?></td>
                    <td width="5%" class = "white"><?php if($rows4['ChkSt']== FALSE){?>
                            <img src="../img/warning.jpeg" width="20" height="20"><?php
                         }else{?>
                            <img src="../img/ok.jpeg" width="20" height="20"><?php
                         }; ?></td>
                    <td width="15%" class = "white"><?php echo htmlentities($rows4['CheckedBy']); ?></td>
                    <td width="20%" class = "white"><?php echo htmlentities($rows4['Note']); ?></td>
                </tr>

                <?php
                    $i=$i+1;
                } //rows4
                } //rows
                
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


