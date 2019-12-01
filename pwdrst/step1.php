<?php

/**
wachtwoord reset
invoeren gebruikersnaam
verzenden
tonen bevestigingstekst

copyright: 2019 Gerko Weening
*/

include_once "../inc/base.php";
//include_once "functions.php";

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="../css/StyleSheet.css">
    </head>

    <body style="background-color: #f2f2f2;">
        <?php
            if (isset($_GET['error'])) {
                echo '<p class="error">Uw inloggegevens kloppen niet, probeer het nog eens!</p>';
            }
        ?> 
        <p style="text-align:center;">
			<img src="../img/logo.gif" 
				 alt="" width="152" height="50">		  
		</p>
		<br>
		<p></p>
        <div id="login">
            <form action="process_step1.php" method="post" name="reset_form">
				Gebruikersnaam 
				<input type="text" class="logininput" name="uname" >
				<br><br><br><br> 
				<input type="submit" class="loginbutton" value="Versturen" >
            </form>
        </div>
    </body>
</html>