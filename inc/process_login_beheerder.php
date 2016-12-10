<?php

include_once "base.php";
include_once "functions.php";

sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($_POST['email'], $_POST['p'])) {
    $email = $_POST['email'];
    $password = $_POST['p']; // The hashed password.
    
    if (loginbeheerder($email, $password, $mysqli) == true ) {
        // Login success 
        //check if user = administrator
        header('Location:../php/beheerder.php');
    } else {
        // Login failed 
        header('Location:../beheerderlogin.php?error=1');
    } 
    
} else {
    // The correct POST variables were not sent to this page. 
    echo 'Ongeldig verzoek';
}

?>
