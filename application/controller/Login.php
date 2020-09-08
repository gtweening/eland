<?php

class Login extends Controller {

    public function __construct()    
    {
        parent::__construct();
        include_once($this->app_path."model/Mod_header.php"); 
        $this->mod_header = new mod_header();
        include_once($this->app_path."model/Mod_login.php"); 
        $this->mod_login = new Model();
        include_once($this->app_path."model/Mod_terrein.php"); 
        $this->mod_terrein = new mod_terrein();
        
        $this->sec_session_start();

    }

    function index() 
    {  
        //echo "login->index<br>";
        //secure login
        $logged = $this->login_check($this->mysqli);
        if ($logged == true) {
            $logged = 'in';
        } else {
            $logged = 'uit';
        }

        if(isset($this->url[1])){
            if($this->url[1]=='process'){
                $this->process();
            }
        }
        include $this->app_path.'view/login_view.php';
    }

    function process(){

        if (isset($_POST['email'], $_POST['p'])) {
            $email = $_POST['email'];
            $password = $_POST['p']; // The hashed password.
            $url='';

            if ($this->mod_login->login($email, $password, $this->mysqli, $this->db) == true ) {  
                // Login success 
                //$url = $GLOBALS['userid'].'/'.$GLOBALS['terreinId'];
                //$url = base64_encode($url);
                //check if user = administrator
                header('Location:../Sections');
            } else {
                // Login failed 
                header('Location:../index.php?error=1');
            } 
            
        } else {
            // The correct POST variables were not sent to this page. 
            echo 'Ongeldig verzoek';
        }
    }

    function terreinselect(){

        $this->checkPermission($this->mysqli);
        $userid      = $_SESSION['user_id'];
        $terreinusers = $this->mod_terrein->getTerreinUsers($userid, $this->db);

        include $this->app_path.'view/terreinselect_view.php';
    }

    function terreinselectexecute(){
        if(!empty($_POST['checkbox'])){
            foreach($_POST['checkbox'] as $val){
                $ids[] = (int) $val;
            }
            $ids = implode("','", $ids);

            $Terreinid = $this->mod_terrein->getTerreinidbyId($ids, $this->db);
        }

        // if successful redirect to section.php
        if($Terreinid <> ''){
            $_SESSION['Terreinid'] = $Terreinid;

            $terreinnaam = $this->mod_terrein->getTerreinnaam($this->db, $Terreinid);
            $_SESSION['Terreinnaam'] = $terreinnaam;

            //add to log
            $login_time = date('Y-m-d h:i:sa');
            $log_msg = $_SESSION['username'].';'.$terreinnaam.';'.$login_time."\n";
            file_put_contents('application/log/login.log', $log_msg, FILE_APPEND);
    
            header('Location:../Sections');
        }
    }

}

?>