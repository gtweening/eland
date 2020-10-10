<?php

class LostPassword extends Controller {

    public function __construct()    
    {
        parent::__construct();
        include_once($this->app_path."model/Mod_users.php"); 
        $this->mod_users = new mod_users();
        include_once($this->app_path."model/Mod_helpers.php"); 
        $this->mod_helpers = new mod_helpers();

        $this->sec_session_start();
    }

    function index() 
    { 
        $result = '';

        if (isset($_POST['uname'])) {
            $uname = $_POST['uname'];
        
            if ($this->mod_users->usernamecheck($uname,$this->db) == true ) {
                // valid username
                //set temporary password 
                $pwd = $this->mod_users->settemppwd($uname,$this->db);
                
                //set endtime
                // Get timestamp of current time 
                $now = time();
                // end time password is valid for 15 minutes. 
                $endtime = $now + (30 * 60 );
        
                //save in db
                $this->mod_users->editTempPwd($pwd, $endtime, $uname, $this->db);
                
                //get userid
                $id = $this->mod_users->getuserid($uname, $this->db);
                //get ema
                $ema = $this->mod_users->getema($id,$this->db);
        
                //send mail
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .='From: automatisering.com@survivalrunbond.nl'."\r\n".
                           'Reply-to: automatisering.com@survivalrunbond.nl';
                $subject = 'Aanvraag wachtwoord reset';
        
                //$path = WEBROOT."/application/emails/lostPassword_step1.php?Id=".$id;
                //$msg = file_get_contents($path); 
                $msg = "
                <html>
                <body>
                Hallo,<br><br>
                Er is een nieuw wachtwoord aangevraagd voor Eland<br>
                Als dit klopt klik dan onderstaande link.<br>
                Deze link is 15 min. geldig<br><br>
                Zoniet, neem dan contact op met de beheerder.<br><br>
                <a href='".WEBROOT."/LostPassword/step2/".$id."' >Wachtwoord reset</a>

                </body>

                </html>
                ";
                mail($ema,$subject,$msg,$headers);
        
                $result = "Er is een mail verstuurd naar het mailadres van deze gebruiker!";
               
            } else {
                // unvalid usernam 
                $result = "Ongeldig verzoek!";
                
            } 
            
        }

        include $this->app_path.'view/lostPassword_view.php';
    }


    function step2() 
    { 
        $userId = 0;
        if(isset($this->url[2])){
            $userId = $this->url[2];
        }

        //only show page if userId is found
        //and userId has temppwd
        $result = $this->mod_users->temppwdavailable($userId,$this->db);

        if ($userId > 0 && $result==true) {
            //ema ophalen
            $ema = $this->mod_users->getema($userId,$this->db);
            //pwd ophalen
            $pwd = $this->mod_users->getpwd($userId,$this->db);
        
            //stuur mail met wachtwoord
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .='From: automatisering.com@survivalrunbond.nl'."\r\n".
                       'Reply-to: automatisering.com@survivalrunbond.nl'.
                       'X-Mailer: PHP/'.phpversion();
            $subject = 'Aanvraag wachtwoord reset';
            //$path = WEBROOT."/application/emails/lostPassword_step2.php?Id=".$userId."&pwd=".$pwd;
            //$msg = file_get_contents($path); 

            $msg = "
            <html>
            <head>
            </head>

            <body>
            Hallo,<br><br>
            Hieronder wordt je tijdelijk wachtwoord voor Eland getoond.<br><br>
            Heb je deze niet aangevraagd, neem dan contact op met de beheerder.<br>
            Ga NU naar onderstaande link om het wachtwoord direct aan te passen!<br>
            Login met je gebruikersnaam en je tijdelijke wachtwoord: <b>".$pwd."</b><br>
            en kies daarna je eigen geheime wachtwoord.<br><br>
            <a href='".WEBROOT."/LostPassword/step2/".$id."' >Wachtwoord aanpassen</a>

            </body>

            </html>
            ";
            
            mail($ema,$subject,$msg,$headers);
        
            //melden dat tijdelijk wachtwoord verstuurd is
            $result = "Er is een 2e mail verstuurd met een tijdelijk wachtwoord naar het mailadres van deze gebruiker!";
        
        }else{
            echo "File not found.";
            exit();
        }

        if (isset($_POST['email'], $_POST['p'])) {
            $email = $_POST['email'];
            $pwd   = $_POST['p'];
        
            // Get timestamp of current time 
            $now = time();
        
            //check username/pwd and reaction time (must be in time)
            $valid = $this->mod_users->temppwdvalid($email,$pwd,$now,$this->db);
            if ($valid==1){
                // if successful redirect 
                header('Location:../../LostPassword/step3/'.$_POST['Id']);
                
            }else{
                echo "De ingevoerde gegevens voldoen niet aan de voorwaarden. De functie wordt beeindigd!";
                exit();
            }
            
        } 

        include $this->app_path.'view/lostPassword_step2_view.php';

    }


    function step3() 
    { 
        $result = '';
        if(isset($this->url[2])){
            $userId = $this->url[2];
        }

        //only show page if userId is found
        //and userId has temppwd
        $hastemppwd = $this->mod_users->temppwdavailable($userId, $this->db);

        if ($userId > 0 && $hastemppwd==true) {
            include $this->app_path.'view/lostPassword_step3_view.php';

        }else{
            $location = WEBROOT;
            header('Location:'.$location);
        }

        //all inputitems are set and old-new pwd are equal
        if (isset($_POST['pt'], $_POST['pn'], $_POST['ph']) && $_POST['pn']==$_POST['ph']) {
            $result = "De ingevoerde gegevens voldoen niet aan de voorwaarden. De functie wordt beeindigd!";

            $pwdt = $_POST['pt'];
            $pwd_new = $_POST['pn']; //hashed password new
            $pwd_h = $_POST['ph']; //hashed password herhaling
            $userid = $_POST['Id'];

            $username = $this->mod_users->getuser($userid,$this->db);

            if ($username!= false){
                // Get timestamp of current time 
                $now = time();
                //check username/pwd and reaction time (must be in time)
                $valid = $this->mod_users->temppwdvalid($username,$pwdt,$now,$this->db);

                if ($valid==1){
                    //salt
                    $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
                    //password
                    $password = hash('sha512', $pwd_new . $random_salt);

                    //insert new pwd
                    $reset = $this->mod_users->resetPwd($userid, $password, $random_salt, $this->db);

                    // if successful redirect to index 
                    if($reset){
                        $result = "Wachtwoord succesvol ingesteld!";
    
                    }else {
                        $result = "Wachtwoord reset mislukt!";                   
                    }

                }

            }

        } else {
            // The correct POST variables were not sent to this page. 
            $result = 'Ongeldig verzoek!';
        }
    }

}

?>