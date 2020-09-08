<!DOCTYPE html>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="<?php echo WEBROOT;?>/css/StyleSheet.css">
    </head>

    <body style="background-color: #f2f2f2;"> 
        <p style="text-align:center;">
			<img src="<?php echo WEBROOT;?>/img/logo.gif" 
				 alt="" width="152" height="50">		  
		</p>
        <br>
        
        <div id="login">
            <form action="<?php echo WEBROOT; ?>/LostPassword/step2/<?php echo $userId; ?>" method="post" name="reset_form">
                <input type="hidden" name="Id" value="<?php echo $userId;?>">
                Gebruikersnaam
                <input type="text" class="logininput" name="email" >
                <br><br><br><br>
                Wachtwoord
                <input type="password" class="logininput" name="p" id="password" >
                <br><br><br><br>
                <input type="submit" class="loginbutton" value="Versturen" >
            </form>
        </div>

        <div style="text-align:center;">
            <?php echo "<br>".$result;?>
        </div>

    </body>
</html>