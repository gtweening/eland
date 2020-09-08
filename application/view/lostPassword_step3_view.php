<!DOCTYPE html>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="<?php echo WEBROOT;?>/css/StyleSheet.css">
        <script type="text/JavaScript" src="<?php echo WEBROOT;?>/js/sha512.js"></script> 
        <script type="text/JavaScript" src="<?php echo WEBROOT;?>/js/forms.js"></script> 
        <script>
            function validate(){
                var a = document.getElementById("passwordn").value;
                var b = document.getElementById("passwordh").value;
                if(a!=b){
                    document.getElementById("message").style.color='red';
                    document.getElementById("message").innerHTML = 'Wachtwoorden komen niet overeen!';
                    document.getElementById("submitbutton").disabled = true;
                } else{
                    document.getElementById("message").style.color='green';
                    document.getElementById("message").innerHTML = 'Wachtwoorden komen overeen!';
                    document.getElementById("submitbutton").disabled = false;
                }
            }
        </script>
    </head>

    <body style="background-color: #f2f2f2;">
        <p style="text-align:center;">
			<img src="<?php echo WEBROOT;?>/img/logo.gif" 
				 alt="" width="152" height="50">		  
		</p>
        <br>
        
        <div id="login">
        <form action="LostPassword/step3" method="post" name="reset_form">
            <input type="hidden" name="Id" value="<?php echo $userId;?>">
            Tijdelijk wachtwoord
            <input type="password" class="logininput" name="pt" id="passwordt" >
            <br><br><br><br>
            Nieuw wachtwoord
            <input type="password" class="logininput" name="pn" id="passwordn" onkeyup='validate();'>
            <br><br><br><br>
            Herhaling nieuw wachtwoord
            <input type="password" class="logininput" name="ph" id="passwordh" onkeyup='validate();'>
            <span id='message'></span>
            <br><br><br><br>
            <input type="submit" class="loginbutton" value="Versturen" id="submitbutton" disabled=""
                   onclick="formhash2(this.form, this.form.passwordn, this.form.passwordh);" >
        </form>
        </div>

        <div style="text-align:center;">
            <?php echo "<br>".$result;?>
        </div>

    </body>
</html>