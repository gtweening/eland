<?php
/**
processing ww reset

copyright: 2019 Gerko Weening
*/

include_once "../inc/base.php";
include_once "../inc/functions.php";
include_once "functions.php";

//sec_session_start(); // Our custom secure way of starting a PHP session.

//all inputitems are set and old-new pwd are equal
if (isset($_POST['pt'], $_POST['pn'], $_POST['ph']) && $_POST['pn']==$_POST['ph']) {
    $result = "De ingevoerde gegevens voldoen niet aan de voorwaarden. De functie wordt beeindigd!";

    $pwdt = $_POST['pt'];
    $pwd_new = $_POST['pn']; //hashed password new
    $pwd_h = $_POST['ph']; //hashed password herhaling
    $userid = $_POST['Id'];

    $username = getuser($userid,$mysqli);

    if ($username!= false){
        

        // Get timestamp of current time 
        $now = time();
        //check username/pwd and reaction time (must be in time)
        $valid = temppwdvalid($username,$pwdt,$now,$mysqli);

        if ($valid==1){
            //salt
            $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
            //password
            $password = hash('sha512', $pwd_new . $random_salt);

            //insert new pwd
            $STH = $db->prepare("UPDATE TblUsers 
                                SET Password = '$password', salt = '$random_salt', temppwd = '', validuntil = '' 
                                WHERE Id = '$userid'");
            $STH->execute();

            // if successful redirect to index 
            if($STH){
                $result = "Wachtwoord succesvol ingesteld!";
                showResult($result,1);
            }else {
                $result = "Wachtwoord reset mislukt!";
                showResult($result,1);
            }

        }else{          
            showResult($result,1);
        }

    }else{
        showResult($result,1);
    }

} else {
    // The correct POST variables were not sent to this page. 
    echo 'Ongeldig verzoek!';
}


?>