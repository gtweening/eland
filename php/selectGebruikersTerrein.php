<?php

/**
maintenance page

copyright: 2013 Gerko Weening
*/

include_once "../inc/base.php";
include_once "../inc/functions.php";
sec_session_start(); 
include_once "../common/header.php"; 


$userid=$_GET['Id'];

//secure login
if(login_check($mysqli) == true) { 

?>
<html>
    <head>
        <script type="text/JavaScript" src="../js/sha512.js"></script> 
        <script type="text/JavaScript" src="../js/forms.js"></script>
    </head>
    <body id="gebruikersterreinbeheer">
        <div id="LeftColumn2">
            
        </div>
        <div id="RightColumn">
        <table display:block>
        <tr >
        <td>
        <form name="form1" method="post" action="frmHandling.php">
            <table id="materialenTable" >
                <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Gebruiker terreinen</a>
                <div class="cudWidget">
                    <button class="submitbtn" type="submit" name="setGebrTerrein" float="right" >   
                            <img src="../img/ok.jpeg" width="35" height="35">
                    </button>
                </div>
                
                <div id="widgetBar">
						<br>
    					<a>U beheert meerdere terreinen. U kunt 1 terrein tegelijkertijd onderhouden. Kies terrein.</a>
                </div>
                <tr class="theader">
                    <th width="5%" ></th>
                    <th width="50%"><strong>Gebruikersnaam</strong></th>
						  <th ><strong>Terrein</strong></th>
                </tr>

                <?php
                $STH = $db->query('select ttu.*, tt.Terreinnaam, tu.Email
												from TblTerreinUsers ttu, TblTerrein tt, TblUsers tu
												where ttu.Terrein_id = tt.Id 
												and ttu.User_id = tu.Id 
												and ttu.User_id = "'.$userid.'" 
											   order by tu.Id');
                $STH->setFetchMode(PDO::FETCH_ASSOC);
                while($rows=$STH->fetch()){
                ?>

                <tr >
                    <td width="5%" class="white2">
	                <input name="checkbox[]" type="checkbox" id="checkbox[]" 
                               value="<? echo $rows['Id']; ?>"></td>
                    <td width="50%" class="white" ><? echo htmlentities($rows['Email']); ?></td>
							<td class="white"><? echo htmlentities($rows['Terreinnaam']); ?> </td>
                </tr>

                <?php
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



