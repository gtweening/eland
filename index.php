<?php

/**
index page

copyright: 2013 Gerko Weening
v1.1
201704:layout aangepast

*/

include_once "inc/base.php";
include_once "inc/functions.php";

sec_session_start();
//include_once "common/header.php"; 

//secure login 
if (login_check($mysqli) == true) {
    $logged = 'in';
} else {
    $logged = 'uit';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/StyleSheet.css">
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script> 
    </head>
    <body style="background-color: #f2f2f2;">
        <?php
        if (isset($_GET['error'])) {
            echo '<p class="error">Uw inloggegevens kloppen niet, probeer het nog eens!</p>';
        }
        ?> 
        <p style="text-align:center;">
				<img src="img/logo.gif" 
				 alt="" width="152" height="50">		  
		  </p>
		  <br>
		  <h2>Inloggen bij hindernislogboek</h2>
		  <p></p>
        <div id="login">
            <form action="inc/process_login.php" method="post" name="login_form">

					Gebruikersnaam of e-mail adres
					 <input type="text" class="logininput" name="email" >
					 <br><br><br><br>
					 Wachtwoord
					 <input type="password" class="logininput" name="passwoord" id="password" >
					 <br><br><br><br>
					 <input type="button" class="loginbutton" value="Inloggen" 
							  onclick="formhash(this.form, this.form.password);">
					 U bent momenteel <?php echo $logged ?>gelogd.

            </form>
        </div>
<br><br><br>
        <div id="footer">
          <?php include "common/footer.php"; ?>
        </div>
    </body>
</html>
