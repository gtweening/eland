<?php

/**
Each obstacle has some points which should be checked.
Using this page you can maintain your checkpoint library.

copyright: 2013 Gerko Weening
*/

include_once "../inc/base.php";
include_once "../inc/functions.php";
include_once "../inc/queries.php";
sec_session_start();
include_once "../common/header.php";
include_once "../common/leftColumn.php";

$tbl_name="TblCheckpoints"; // Table name
//secure login
if(login_check($mysqli) == true) { 
?>
<html>
<head>
</head>
<body id="checkpoints">
    <div id="LeftColumn2">
            
    </div>
    <div id="RightColumn">
    <table display:block>
    <tr >
    <td>
    <form name="form1" method="post" action="frmHandling.php">
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
                    <input type="text" class="inputText" name="CheckPoint" maxlength="50" size="32">
                    <div class="cudWidget">
                        <button class="submitbtn" type="submit" name="addChkPoint" float="right">
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
                <td width="5%" class="white2"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<? echo $rows['Id']; ?>"></td>
                <td class = "white"><? echo htmlentities($rows['Omschr']); ?></td>
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


