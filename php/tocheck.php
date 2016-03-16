<?php>

/**
It is assumed that an organisation plans a day to check some obstacles.
For this day you can generate an overview of obstacles which should be checked.
The overview is based on the quarters in which an obstacle should be checked.

copyright: 2013 Gerko Weening
*/

include_once "../inc/base.php";
include_once "../inc/functions.php";

sec_session_start(); 
include_once "../common/header.php"; 
include_once "../common/leftColumn.php";

$tbl_name="TblObstacles"; // Table name

//secure login
if(login_check($mysqli) == true) { 
?>
<html>
    <head>
    </head>        
    <body id="checklist">
        <div id="LeftColumn2">
            
        </div>
        <div id="RightColumn">
        <table display:block>
        <tr>
        <td>
        <form name="form1" method="post" action="report.php">
            <table id="materialenTable">
                <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Overzicht te controleren</a>
                <div class="cudWidget"> 
                    <button class="submitbtn" type="submit" name="report">
                        <img src="../img/checklist.jpeg" width="35" height="35">
                    </button>
                </div>
                    <div id="widgetBar">
                        <br>
                        <a>Geef aan voor welk kwartaal en jaar het overzicht gemaakt moet worden.</a>
                    </div>
                <tr class="theader">
                    <th width="5%"><strong>Jaar</strong></th>
                    <th><strong>Kwartaal</strong></th>
                </tr>
                <tr>
                    <td>
                        <br><input type="text" name="jaar" maxlength="5" size="5">
                    </td>
                    <td width="5%"><br>
                        <select size="1" id="kwartaal" name="kwartaal">
                            <option value="">Kies kwartaal</option>
                            <option value="1">Kwartaal 1</option>
                            <option value="2">kwartaal 2</option>
                            <option value="3">Kwartaal 3</option>
                            <option value="4">kwartaal 4</option>
                        </select>
                    </td>
                </tr>
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
    
