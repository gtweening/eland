<?php

/**
wachtwoord reset
invoeren gebruikersnaam
verzenden
tonen bevestigingstekst

copyright: 2019 Gerko Weening
*/

include_once "../inc/base.php";
include_once "../inc/functions.php";
include_once "functions.php";

//UserId ophalen
$userId=0;
if(isset($_GET['Id'])){
    $userId=$_GET['Id'];
}

//only show page if userId is found
//and userId has temppwd
$result = temppwdavailable($userId,$mysqli);

if ($userId > 0 && $result==true) {
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="../css/StyleSheet.css">
        <script type="text/JavaScript" src="../js/sha512.js"></script> 
        <script type="text/JavaScript" src="../js/forms.js"></script> 
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
        
        <div id="login">
        <form action="process_step3.php" method="post" name="reset_form">
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
    </body>
</html>

<?php
}else{
    echo "File not found.";
}
?>
