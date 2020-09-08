<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/StyleSheet.css">
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script> 
    </head>
    
    <body style="background-color: #f2f2f2;">

        <p style="text-align:center;">
            <img src="img/logo.gif" alt="" width="152" height="50">			  
		</p>
        <br>
		<h2>Inloggen bij hindernislogboek</h2>

        <div id="login">
            <form action="Login/process" method="post" name="login_form">

                Gebruikersnaam of e-mail adres
                <input type="text" class="logininput" name="email" >
                <br><br><br><br>
                Wachtwoord
                <input type="password" class="logininput" name="passwoord" id="password" >
                <br><br><br><br>
                <input type="button" class="loginbutton" value="Inloggen" 
                        onclick="formhash(this.form, this.form.password);">
                U bent momenteel <?php echo $logged ?>gelogd.
                <?php
                    if (isset($_GET['error'])) {
                        echo '<p class="error">Uw inloggegevens kloppen niet, probeer het nog eens!</p>';
                    }
                ?>
            </form>
        </div>
        <br>
        <div id="login">
            <a href='<?php echo WEBROOT;?>/LostPassword'>wachtwoord vergeten?</a>        
        </div>
        <br><br>
        <div id="licentie">
          <?php include "GNUlicentie.php"; ?>
        </div>
    </body>
</html>