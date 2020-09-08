<?php 
    include_once "header.php"; 
    include_once "leftColumnBeheerder.php"; 
?>

<html>
    <head>
        <script type="text/JavaScript" src="<?php echo WEBROOT;?>/js/sha512.js"></script> 
        <script type="text/JavaScript" src="<?php echo WEBROOT;?>/js/forms.js"></script>
    </head>

    <body id="gebruikersbeheer">
        <div id="LeftColumn2" >
        </div>

        <div id="RightColumn">
            <form name="form1" method="post" action="<?php echo WEBROOT.'/Beheer/gebruikersbeheer'; ?>">
                <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Gebruikers</a>
                <div class="cudWidget">
                    <button class="submitbtn" type="submit" name="delUser">
                        <img src="<?php echo WEBROOT;?>/img/del.jpeg" width="35" height="35">
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
                            <img src="<?php echo WEBROOT;?>/img/add.jpeg" width="35" height="35">
                        </button>
                    </div>
                </div>
                
                <table id="materialenTable" >
                    <tr class="theader">
                        <th width="5%" ></th>
                        <th width="40%"><strong>Gebruikersnaam</strong></th>
                        <th width="40%"><strong>Email adres</strong></th>
                        <th ><strong>Administrator</strong></th>
                    </tr>
                
                    <?php
                    while($rows = $users->fetch()){
                    ?>

                    <tr >
                        <td width="5%" class="white2">
                            <input name="checkbox[]" type="checkbox" id="checkbox[]" 
                                  value="<?php echo $rows['Id']; ?>"></td>
                        <td width="70%" class="white" "><?php echo htmlentities($rows['Email']); ?></td>
                        <td width="70%" class="white" "><?php echo htmlentities($rows['ema']); ?></td>
                        <td width="5%" class="white"><?php if($rows['Admin']== FALSE){ ?>
                                <img src="<?php echo WEBROOT;?>/img/nok.png" width="20" height="20"><?php
                            }else{?>
                                <img src="<?php echo WEBROOT;?>/img/ok.jpeg" width="20" height="20"><?php
                            }; ?></td>
                    </tr>
                    <?php
                    }
                    //close connection
                    $db = null;
                    ?>
                </table>
            </form>
        </div>
    </body>
</html>