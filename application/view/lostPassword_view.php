<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="<?php echo WEBROOT; ?>/css/StyleSheet.css">
    </head>

    <body style="background-color: #f2f2f2;">
        <p style="text-align:center;">
			<img src="<?php echo WEBROOT; ?>/img/logo.gif" 
				 alt="" width="152" height="50">		  
		</p>
		<br>
		<p></p>
        <div id="login">
            <form action="<?php echo "LostPassword";?>" method="post" name="reset_form">
				Gebruikersnaam 
				<input type="text" class="logininput" name="uname" >
				<br><br><br><br> 
				<input type="submit" class="loginbutton" value="Versturen" >
            </form>
        </div>

        <div style="text-align:center;">
            <?php echo "<br>".$result;?>
        </div>
    </body>
</html>