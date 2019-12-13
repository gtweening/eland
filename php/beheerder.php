<?php
/**
maintenance page
copyright: 2013 Gerko Weening

201609 - changed style leftcoloumn
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
    <body id="gebruikersbeheer">
        <div id="LeftColumn2" >
            
        </div>
        <div id="RightColumn">
        <table display:block>
        <tr >
        <td>
        <form name="form1" method="post" action="frmHandling.php">
            <table id="materialenTable" >
                <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Gebruikers</a>
                <div class="cudWidget">
                    <button class="submitbtn" type="submit" name="delUser">
                        <img src="../img/del.jpeg" width="35" height="35">
                    </button>
                </div>
                
                <div id="widgetBar2x">
                    <input type="text" class="inputText" name="usernaam" maxlength="50" size="18" placeholder='gebruikersnaam'>
                    <input type="text" class="inputText" name="password" maxlength="50" size="18" placeholder='wachtwoord'>
                    <input type="checkbox" class="inputText" name="useradmin" ><br>
                    <input type="text" class="inputText" name="emailadres" maxlength="100" size="45" placeholder='email adres'>
                    <div class="cudWidget">
                        <button class="submitbtn" type="submit" name="addUser" float="right" 
  				                onclick="formhash(this.form, this.form.password)">   
                            <img src="../img/add.jpeg" width="35" height="35">
                        </button>
                    </div>
                </div>
                <tr class="theader">
                    <th width="5%" ></th>
                    <th width="40%"><strong>Gebruikersnaam</strong></th>
                    <th width="40%"><strong>Email adres</strong></th>
                    <th ><strong>Administrator</strong></th>
                </tr>

                <?php
                $STH = $db->query('SELECT * from TblUsers order by Id');
                $STH->setFetchMode(PDO::FETCH_ASSOC);
                while($rows=$STH->fetch()){
                ?>

                 <tr >
                    <td width="5%" class="white2">
	                <input name="checkbox[]" type="checkbox" id="checkbox[]" 
                               value="<? echo $rows['Id']; ?>"></td>
                    <td width="70%" class="white" "><?php echo htmlentities($rows['Email']); ?></td>
                    <td width="70%" class="white" "><?php echo htmlentities($rows['ema']); ?></td>
		            <td width="5%" class="white"><?php if($rows['Admin']== FALSE){ ?>
                            <img src="../img/nok.png" width="20" height="20"><?php
                         }else{?>
                            <img src="../img/ok.jpeg" width="20" height="20"><?php
                         }; ?></td>
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
