<?php

/**
maintenance page

copyright: 2013 Gerko Weening
*/

include_once "../inc/base.php";
include_once "../inc/functions.php";

sec_session_start(); 
include_once "../common/header.php"; 
include_once "../common/leftColumnBeheerder.php";

//$tbl_name="TblSections"; // Table name
//secure login
if(login_check($mysqli) == true) { 
?>
<html>
    <head>
        <script type="text/JavaScript" src="../js/sha512.js"></script> 
        <script type="text/JavaScript" src="../js/forms.js"></script>
    </head>
    <body id="terreinbeheer">
        <div id="LeftColumn2">
            
        </div>
        <div id="RightColumn">
        <table display:block>
        <tr >
        <td>
        <form name="form1" method="post" action="frmHandling.php">
            <table id="materialenTable" >
                <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Terreinen</a>
                <div class="cudWidget">
                    <button class="submitbtn" type="submit" name="delTerrein">
                        <img src="../img/del.jpeg" width="35" height="35">
                    </button>
                </div>
                <div class="cudWidget">
                    <button class="submitbtn" type="submit" name="editTerrein">
                        <img src="../img/edit.jpeg" width="35" height="35">
                    </button>
                </div>
                <div id="widgetBar">
                    <input type="text" class="inputText" name="Terreinnaam" maxlength="50" size="18">
                    <div class="cudWidget">
                        <button class="submitbtn" type="submit" name="addTerrein" float="right" 
  				onclick="formhash(this.form, this.form.password)">   
                            <img src="../img/add.jpeg" width="35" height="35">
                        </button>
                    </div>
                </div>
                <tr class="theader">
                    <th width="5%" ></th>
                    <th width="70%"><strong>Terreinnaam</strong></th>
                </tr>

                <?php
                $STH = $db->query('SELECT * from TblTerrein order by Id');
                $STH->setFetchMode(PDO::FETCH_ASSOC);
                while($rows=$STH->fetch()){
                ?>

                <tr >
                    <td width="5%" class="white2">
	                <input name="checkbox[]" type="checkbox" id="checkbox[]" 
                               value="<?php echo $rows['Id']; ?>"></td>
                    <td width="70%" class="white" "><?php echo htmlentities($rows['Terreinnaam']); ?></td>

                </tr>

                <?php
                }

					 if(isset($_GET['var1'])){
                  $sOmschr = $_GET['var1'];
                  $sId = $_GET['var2'];
                  $STH = $db->prepare("UPDATE TblTerrein SET Terreinnaam = '".$sOmschr."' WHERE Id = $sId");
                  $STH->execute();
                  // if successful redirect to php
                    if($STH){
                        echo "<meta http-equiv=\"refresh\" content=\"0;URL=beheerTerreinen.php\">";
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



