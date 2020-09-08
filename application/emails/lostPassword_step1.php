<html>
<!--
versturen mail na stap1
-->
<?php
    include_once '../Routes.php';
    $path = WEBROOT."/LostPassword/step2/".$_GET['Id'];
?>

<head>
</head>

<body>
Hallo,<br><br>
Er is een nieuw wachtwoord aangevraagd voor Eland<br>
Als dit klopt klik dan onderstaande link.<br>
Deze link is 15 min. geldig<br><br>
Zoniet, neem dan contact op met de beheerder.<br><br>
<a href='<?php echo $path; ?>' >Wachtwoord reset</a>

</body>

</html>