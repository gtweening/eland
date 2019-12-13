<?php
/**
processing ww reset

copyright: 2019 Gerko Weening
*/

include_once "../inc/base.php";
include_once "functions.php";

//sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($_POST['uname'])) {
    $uname = $_POST['uname'];

    if (usernamecheck($uname,$mysqli) == true ) {
        // valid username
        //set temporary password 
        $pwd = settemppwd($uname,$mysqli);
        
        //set endtime
        // Get timestamp of current time 
        $now = time();
        // end time password is valid for 15 minutes. 
        $endtime = $now + (30 * 60 );

        //save in db
        $stmt = $mysqli->prepare("UPDATE TblUsers 
                                  SET temppwd = '".$pwd."' , validuntil = '".$endtime."' 
                                  WHERE email = '".$uname."' ");
        $stmt->execute();
        
        //get userid
        $id = getuserid($uname,$mysqli);
        //get ema
        $ema = getema($id,$mysqli);

        //send mail
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .='From: automatisering.com@survivalrunbond.nl'."\r\n".
                   'Reply-to: automatisering.com@survivalrunbond.nl';
        $subject = 'Aanvraag wachtwoord reset';

        $path = DOMAIN_NAME."/pwdrst/mailcontentstep1.php?Id=".$id;
        $msg = file_get_contents($path); 
        mail($ema,$subject,$msg,$headers);

        $result = "Er is een mail verstuurd naar het mailadres van deze gebruiker!";
        showResult($result,1);
    } else {
        // unvalid usernam 
        $result = "Ongeldig verzoek!";
        showResult($result,1);
    } 
    
} else {
    // The correct POST variables were not sent to this page. 
    echo 'Ongeldig verzoek!';
}


?>