<?php
/**
processing ww reset

copyright: 2019 Gerko Weening
*/

include_once "../inc/base.php";
include_once "functions.php";

//sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($_POST['email'], $_POST['p'])) {
    $email = $_POST['email'];
    $pwd = $_POST['p'];

    // Get timestamp of current time 
    $now = time();

    //check username/pwd and reaction time (must be in time)
    $valid = temppwdvalid($email,$pwd,$now,$mysqli);
    if ($valid==1){

        echo "<meta http-equiv=\"refresh\" content=\"0;URL=../pwdrst/step3.php?Id=".$_POST['Id']."\">";
    }else{
        $result = "De ingevoerde gegevens voldoen niet aan de voorwaarden. De functie wordt beeindigd!";
        showResult($result,1);
    }
    
} else {
    // The correct POST variables were not sent to this page. 
    echo 'Ongeldig verzoek!';
}


?>