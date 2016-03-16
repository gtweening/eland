<?php

/**
The collection of obstacles are devided into section.
Using this page you can maintain Sections.

copyright: 2013 Gerko Weening
*/

include_once "../inc/base.php";
include_once "../inc/functions.php";

sec_session_start(); 
include_once "../common/header.php"; 
include_once "../common/leftColumn.php";

$tbl_name="TblSections"; // Table name

//secure login
if(login_check($mysqli) == true) { 
?>
<html>
    <head>
        <script type="text/javascript">
            function FunSectie(e){
                if(!e.target){
                    // alert(e.srcElement.innerHTML);
                } else {
                    var section =  e.target.innerHTML;
                    window.location.href = "obstaclesSection.php?sectie=" + section;
                } 
            }
            function FunOmschr(e){
                if(!e.target){
                    // alert(e.srcElement.innerHTML);
                } else {
                    var sectieomschr =  e.target.innerHTML;
                    window.location.href = "obstaclesSection.php?omschr=" + sectieomschr;
                } 
            }
        </script>
    </head>
    <body id="sections">
        <div id="LeftColumn2">
            
        </div>
        <div id="RightColumn">
        <table display:block>
        <tr >
        <td>
        <form name="form1" method="post" action="frmHandling.php">
            <table id="materialenTable2" >
                <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Hindernissecties</a>
                <div class="cudWidget">
                    <button class="submitbtn" type="submit" name="delSection">
                        <img src="../img/del.jpeg" width="35" height="35">
                    </button>
                </div>
                <div class="cudWidget">
                    <button class="submitbtn" type="submit" name="editSection">
                        <img src="../img/edit.jpeg" width="35" height="35">
                    </button>
                </div>
                <div id="widgetBar">
                    <input type="text" class="inputText" name="sectienaam" maxlength="5" size="5">
                    <input type="text" class="inputText" name="sectieomschr" maxlength="50" size="32">
                    <div class="cudWidget">
                        <button class="submitbtn" type="submit" name="addSection" float="right">
                            <img src="../img/add.jpeg" width="35" height="35">
                        </button>
                    </div>
                </div>
                <tr class="theader">
                    <th width="5%" ></th>
                    <th width="10%"><strong>Naam</strong></th>
                    <th ><strong>Omschrijving</strong></th>
                </tr>

                <?php
                $STH = $db->query('SELECT * from TblSections order by Id');
                $STH->setFetchMode(PDO::FETCH_ASSOC);
                while($rows=$STH->fetch()){
                ?>

                <tr >
                    <td width="5%" class="white2"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<? echo $rows['Id']; ?>"></td>
                    <td width="5%" class="white" onclick="FunSectie(event)"><? echo htmlentities($rows['Naam']); ?></td>
                    <td class = "white" onclick="FunOmschr(event)"><? echo htmlentities($rows['Omschr']); ?></td>
                </tr>

                <?php
                }

                if(isset($_GET['sectieNaam'])){
                  $sNaam = $_GET['sectieNaam'];
                  $sOmschr = $_GET['sectieOmschr'];
                  $sId = $_GET['id'];
                  $STH = $db->prepare("UPDATE $tbl_name SET Omschr = '".$sOmschr."' , Naam = '".$sNaam."' WHERE Id = $sId");
                  $STH->execute();
                  // if successful redirect to sections.php
                    if($STH){
                        echo "<meta http-equiv=\"refresh\" content=\"0;URL=sections.php\">";
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



