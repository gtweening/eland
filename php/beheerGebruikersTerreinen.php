<?php

/**
maintenance page 

copyright: 2016 Gerko Weening
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
    <body id="gebruikersterreinbeheer">
        <div id="LeftColumn2">
            
        </div>
        <div id="RightColumn">
        <table display:block>
        <tr >
        <td>
        <form name="form1" method="post" action="frmHandling.php">
            <table id="materialenTable" >
                <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;GebruikersTerreinen</a>
                <div class="cudWidget">
                    <button class="submitbtn" type="submit" name="delGebrTerrein">
                        <img src="../img/del.jpeg" width="35" height="35">
                    </button>
                </div>
                
                <div id="widgetBar">
                    <select name="User">
							<?php
							$STH = $db->query('SELECT Id, Email from TblUsers');
							$STH->setFetchMode(PDO::FETCH_ASSOC);
							while($rows=$STH->fetch()){
								echo "<option value='".htmlentities($rows['Id'])."'>".htmlentities($rows['Email']). "</option>";
							}
							?>
						  </select>

						  <select name="Terrein">
							<?php
							$STH = $db->query('SELECT Id, Terreinnaam from TblTerrein');
							$STH->setFetchMode(PDO::FETCH_ASSOC);
							while($rows=$STH->fetch()){
								echo "<option value='".htmlentities($rows['Id'])."'>".htmlentities($rows['Terreinnaam']). "</option>";
							}
							?>
						  </select>

                    <div class="cudWidget">
                        <button class="submitbtn" type="submit" name="addGebrTerrein" float="right" 
  				onclick="formhash(this.form, this.form.password)">   
                            <img src="../img/add.jpeg" width="35" height="35">
                        </button>
                    </div>
                </div>
                <tr class="theader">
                    <th width="5%" ></th>
                    <th width="50%"><strong>Gebruikersnaam</strong></th>
						  <th ><strong>Terrein</strong></th>
                </tr>

                <?php
                $STH = $db->query('select ttu.*, tt.Terreinnaam, tu.Email
												from TblTerreinUsers ttu, TblTerrein tt, TblUsers tu
												where ttu.Terrein_id = tt.Id and
													ttu.User_id = tu.Id 
											   order by tu.Id');
                $STH->setFetchMode(PDO::FETCH_ASSOC);
                while($rows=$STH->fetch()){
                ?>

                <tr >
                    <td width="5%" class="white2">
	                <input name="checkbox[]" type="checkbox" id="checkbox[]" 
                               value="<?php echo $rows['Id']; ?>"></td>
                    <td width="50%" class="white" ><?php echo htmlentities($rows['Email']); ?></td>
							<td class="white"><?php echo htmlentities($rows['Terreinnaam']); ?> </td>
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



