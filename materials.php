<?php
include_once "inc/base.php";
include_once "inc/functions.php";
sec_session_start(); 
include_once "common/header.php"; 
include_once "common/leftColumn.php";

$tbl_name="TblMaterials"; // Table name
//secure login
if(login_check($mysqli) == true) {   
?>
<html>
    <head>
    </head>
    <body id="materials">     
        <div id="LeftColumn2">
            
        </div>
        <div id="RightColumn">
        <table display:block>
        <tr >
        <td>
        <form name="form1" method="post" action="frmHandling.php">
            <table id="materialenTable">
                <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Materialen</a>
                <div class="cudWidget"> 
                    <button class="submitbtn" type="submit" name="delMaterial">
                        <img src="img/del.jpeg" width="35" height="35">
                    </button>
                </div>
                <div class="cudWidget">
                    <button class="submitbtn" type="submit" name="editMaterial">
                        <img src="img/edit.jpeg" width="35" height="35">
                    </button>
                </div>    
                <div id="widgetBar">
                    <input type="text" class="inputText" name="material" maxlength="32" size="32">
                    <div class="cudWidget">
                        <button class="submitbtn" type="submit" name="addMaterial" float="right">
                            <img src="img/add.jpeg" width="35" height="35">
                        </button>
                    </div>
                </div>
                <tr class="theader">
                    <th width="5%" ></th>
                    <th ><strong>Omschrijving</strong></th>
                </tr>

                <?php
                $STH = $db->query('SELECT * from TblMaterials order by Id');
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
                        echo "<meta http-equiv=\"refresh\" content=\"0;URL=materials.php\">";
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

