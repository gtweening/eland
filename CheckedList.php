<?php
include_once "inc/base.php";
include_once "inc/functions.php";

sec_session_start(); 
include_once "common/header.php"; 
include_once "common/leftColumn.php";

$tbl_name1="TblObstacleChecks"; // Table name

//secure login
if(login_check($mysqli) == true) { 
    
//bepaal op basis van opgegeven jaar en kwartaal
//de controleperiode die beschouwd moet worden
if(isset($_GET['ChkQ'])){
    $ChkQ=$_GET['ChkQ'];
    $datend=$_GET['datend'];
}
if(isset($_POST['jaar'])){
    $jaar = substr($_POST['jaar'], -2);
    //$jaar = $_POST['jaar'];    
    $k=$_POST['kwartaal'];
    switch ($k) {
        case 1:
            $datbegin = $jaar."-01-01";
            $datend = date("Y-m-d", strtotime($datbegin));
            $ChkQ = 'tob.ChkQ1';
            break;
        case 2:
            $datend = date("Y-m-d", strtotime($jaar."-04-01"));
            $ChkQ = 'tob.ChkQ2';
            break;
        case 3:
            $datend = date("Y-m-d", strtotime($jaar."-07-01"));
            $ChkQ = 'tob.ChkQ3';
            break;
        case 4:
            $datend = date("Y-m-d", strtotime($jaar."-10-01"));
            $ChkQ = 'tob.ChkQ4';
            break;
    }
}
$query1 = "select ts.Naam,tob.* ";
$query1 .="from TblSections ts,TblObstacles tob ";
$query1 .="where tob.Section_id = ts.Id and $ChkQ is true ";
$query1 .="union ";
$query1 .="select ts.Naam,tob.* ";
$query1 .="from TblSections ts,TblObstacles tob left join TblObstacleChecks tobc on (tob.id=tobc.obstacle_id) ";
$query1 .="where tob.Section_id = ts.Id and $ChkQ is false ";
$query1 .="and DatCheck between DATE_ADD('$datend', INTERVAL -9 MONTH) and '$datend' ";
$query1 .="and ChkSt is false ";

?>
<html>
    <head>
        <script type="text/javascript">
            
        </script>
    </head>
    <body id="checked">
        <div id="RightColumn">
        <table display:block>
        <tr >
        <td>
        <form name="form1" method="post" action="frmHandlingChkLst.php">
            <table id="materialenTable" >
                <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Gecontroleerde hindernissen</a>
                <div class="cudWidget">
                    <button class="submitbtn" type="submit" name="delCheck">
                        <img src="img/del.jpeg" width="35" height="35">
                    </button>
                </div>
                <div id="widgetBar">
                    <input type="date" class="inputText" name="cdatum" maxlength="10" size="10"
                           value="<?php echo date('Y-m-d'); ?>">
                    <input type="checkbox" class="inputText" name="cstatus" >
                    <input type="text" class="inputText" name="ccontroleur" maxlength="15" size="15">
                    <input type="hidden" name="ChkQ" value="<?php echo $ChkQ;?>">
                    <input type="hidden" name="datend" value="<?php echo $datend;?>">
                    <textarea rows="2" cols="32" name="cnote" >
                    </textarea>
                    <div class="cudWidget">
                        <button class="submitbtn" type="submit" name="addCheck" float="right">
                            <img src="img/refresh.jpeg" width="35" height="35">
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
                $STH = $db->query($query1);
                $STH->setFetchMode(PDO::FETCH_ASSOC);
                while($rows=$STH->fetch()){
                    //selecteer controles van hindernis
                    $query4 = "select tob.*, tobc.DatCheck, tobc.ChkSt, tobc.CheckedBy,tobc.Note ";
                    $query4 .="from TblObstacles tob left join TblObstacleChecks tobc on (tob.id=tobc.obstacle_id) ";
                    $query4 .= "where tob.Id =". $rows['Id']." and ";
                    $query4 .= "DatCheck between '$datend' and DATE_ADD('$datend', INTERVAL +3 MONTH) ";
                    
                ?>
                <tr >
                    <td width="5%" class="white"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<? echo $rows['Id']; ?>"></td>
                    <td width="15%" class = "white" ><? echo htmlentities($rows['Naam']),htmlentities($rows['Volgnr']); ?>
                        <span class="white-text" style="margin-left: 1em;">
                        <img src="<?echo $imgPath,$rows['ImgPath'];?>" alt="" width="55" height="38" ></td>
                    <td  width="30%" class = "white" ><? echo htmlentities($rows['Omschr']); ?></td>
                    <?php
                    $STH4 = $db->query($query4);
                    while($rows4=$STH4->fetch()){
                    ?>
                
                    <td width="10%" class = "white"><? echo htmlentities($rows4['DatCheck']); ?></td>
                    <td width="5%" class = "white"><?if($rows4['ChkSt']== FALSE){?>
                            <img src="img/warning.jpeg" width="20" height="20"><?
                         }else{?>
                            <img src="img/ok.jpeg" width="20" height="20"><?
                         }; ?></td>
                    <td width="15%" class = "white"><? echo htmlentities($rows4['CheckedBy']); ?></td>
                    <td width="20%" class = "white"><? echo htmlentities($rows4['Note']); ?></td>
                </tr>

                <?php
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
U bent niet geautoriseerd voor toegang tot deze pagina. <a href="index.php">Inloggen</a> alstublieft.
<?php
}
?>



