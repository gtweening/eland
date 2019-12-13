<?php

/**
wachtwoord reset
invoeren gebruikersnaam
verzenden
tonen bevestigingstekst

copyright: 2019 Gerko Weening
*/

include_once "../inc/base.php";
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
    //ema ophalen
    $ema = getema($userId,$mysqli);
    //pwd ophalen
    $pwd = getpwd($userId,$mysqli);

    //stuur mail met wachtwoord
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .='From: automatisering.com@survivalrunbond.nl'."\r\n".
               'Reply-to: automatisering.com@survivalrunbond.nl'.
               'X-Mailer: PHP/'.phpversion();
    $subject = 'Aanvraag wachtwoord reset';
    $path = DOMAIN_NAME."/pwdrst/mailcontentstep2.php?Id=".$userId."&pwd=".$pwd;
    $msg = file_get_contents($path); 
    mail($ema,$subject,$msg,$headers);

    //melden dat tijdelijk wachtwoord verstuurd is
    $result = "Er is een 2e mail verstuurd met een tijdelijk wachtwoord naar het mailadres van deze gebruiker!";
    showResult($result,0);
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
        
        <div id="login">
        <form action="process_step2.php" method="post" name="reset_form">
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
    </body>
</html>

<?php
}else{
    echo "File not found.";
}
?>
