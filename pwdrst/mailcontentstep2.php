<html>
<!--
versturen mail na stap2
-->
<?php
include_once "../inc/base.php";
$path = DOMAIN_NAME."/pwdrst/step2.php?Id=".$_GET['Id'];
?>

<head>
</head>

<body>
Hallo,<br><br>
Hieronder wordt je tijdelijk wachtwoord voor Eland getoond.<br><br>
Heb je deze niet aangevraagd, neem dan contact op met de beheerder.<br>
Ga NU naar onderstaande link om het wachtwoord direct aan te passen!<br>
Login met je gebruikersnaam en je tijdelijke wachtwoord: <b><?php echo htmlentities($_GET['pwd']); ?> </b><br>
en kies daarna je eigen geheime wachtwoord.<br><br>
<a href='<?php echo $path; ?>' >Wachtwoord aanpassen</a>

</body>

</html>