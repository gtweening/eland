<?php

class Beheer extends Controller {

    public function __construct()    
    {
        parent::__construct();
        include_once($this->app_path."model/Mod_header.php"); 
        $this->mod_header = new mod_header();
        include_once($this->app_path."model/Mod_login.php"); 
        $this->mod_login = new Model();
        include_once($this->app_path."model/Mod_users.php"); 
        $this->mod_users = new mod_users();
        include_once($this->app_path."model/Mod_terrein.php"); 
        $this->mod_terrein = new mod_terrein();
        include_once($this->app_path."model/Mod_materials.php"); 
        $this->mod_materials = new mod_materials();
        include_once($this->app_path."model/Mod_messages.php"); 
        $this->mod_messages = new mod_messages();
        include_once($this->app_path."model/Mod_helpers.php"); 
        $this->mod_helpers = new mod_helpers();

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
        include $this->app_path.'view/loginbeheerder_view.php';
    }


    function process(){

        if (isset($_POST['email'], $_POST['p'])) {
            $email = $_POST['email'];
            $password = $_POST['p']; // The hashed password.
            $url='';

            if ($this->mod_login->login($email, $password, $this->mysqli) == true ) {  
                // Login success 
                //$url = $GLOBALS['userid'].'/'.$GLOBALS['terreinId'];
                //$url = base64_encode($url);
                //check if user = administrator
                header('Location:../Beheer/gebruikers');
            } else {
                // Login failed 
                header('Location:../index.php?error=1');
            } 
            
        } else {
            // The correct POST variables were not sent to this page. 
            echo 'Ongeldig verzoek';
        }
    }


    function gebruikers(){
        $this->checkPermission($this->mysqli);

        $users = $this->mod_users->getUsers($this->db);

        include $this->app_path.'view/beheerGebruikers_view.php';

    }

    function gebruikersbeheer(){
        $this->checkPermission($this->mysqli);

        unset($_SESSION['errormessage']);

        if(isset($_POST['addUser'])){      
            //controleren en aanvullen
            if(!empty($_POST['usernaam'])){
                //salt
                $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
                //password
                $pass        = $_POST['p']; 
                $password    = hash('sha512', $pass . $random_salt);
                //admin indicator
                if(isset($_POST['useradmin']) && $_POST['useradmin']==TRUE){
                    $indadmin="1";
                }else{
                    $indadmin="0";
                }
                //post
                $ema        = $_POST['emailadres'];
                $username   = $_POST['username'];
                //insert user
                $this->mod_users->addUser($username, $password, $random_salt, $indadmin, $ema, $this->db);
              
            }else {
               //nothing
            } 

            // if successful redirect 
            header('Location:../Beheer/gebruikers');

        }else if(isset($_POST['delUser'])){ 
            if(!empty($_POST['checkbox'])){
                foreach($_POST['checkbox'] as $val){
                    $ids[] = (int) $val;
                }
                $ids = implode("','", $ids);
                //del user
                $this->mod_users->delUsers($ids, $this->db);
            }
            // if successful redirect 
            header('Location:../Beheer/gebruikers');
        }
    }


    function terreinen(){
        $this->checkPermission($this->mysqli);

        $terreinid = "";
        if(isset($this->url[2])){
            $terreinid = $this->url[2];
        }
        $terreinnaam = $this->mod_terrein->getTerreinnaam($this->db, $terreinid);
        $terreinen   = $this->mod_terrein->getTerreinen($this->db);

        include $this->app_path.'view/beheerTerreinen_view.php';

    }

    function terreinbeheer(){
        $this->checkPermission($this->mysqli);

        unset($_SESSION['errormessage']);

        if(isset($_POST['addTerrein'])){      
            if(!empty($_POST['Terreinnaam'])){
                $terreinnaam = $_POST['Terreinnaam' ];
                $this->mod_terrein->addTerrein($terreinnaam, $this->db);
        
                //get terrainid
                $terreinId = $this->mod_terrein->getTerreinIdbyName($terreinnaam, $this->db);
            
                //add default materials
                $this->mod_materials->addDefaultMaterials($terreinId, $this->db);

            }else {
               //niets
            }

        }else if(isset($_POST['delTerrein'])){   
            if(!empty($_POST['checkbox'])){
                foreach($_POST['checkbox'] as $val){
                    $ids[] = (int) $val;
                }
                $ids = implode("','", $ids);

                $this->mod_terrein->delTerreinen($ids, $this->db);
            }
         

        }else if(isset($_POST['editTerrein'])){
            if(!empty($_POST['checkbox'])){
                foreach($_POST['checkbox'] as $val){
                    $Id = (int) $val;
                }
                $terreinnaam = $_POST['Terreinnaam'];

                $this->mod_terrein->editTerreinnaam($this->db, $Id, $terreinnaam);
            }
        }

        // if successful redirect 
        header('Location:../Beheer/terreinen');
    }


    function gebruikersTerreinen(){
        $this->checkPermission($this->mysqli);

        $gebruikers        = $this->mod_users->getUsers($this->db);
        $terreinen         = $this->mod_terrein->getTerreinen($this->db);
        $terreingebruikers = $this->mod_users->getGebruikersTerreinen($this->db);

        include $this->app_path.'view/beheerGebruikersTerreinen_view.php';

    }

    function gebruikersterreinbeheer(){
        $this->checkPermission($this->mysqli);

        unset($_SESSION['errormessage']);

        if(isset($_POST['addGebrTerrein'])){      
            if(!empty($_POST['Terrein'])){
                $user    = $_POST['User']; 
                $terrein = $_POST['Terrein'];
                $this->mod_users->addTerreinUser($user, $terrein, $this->db);
            }
            
        }else if(isset($_POST['delGebrTerrein'])){ 
            if(!empty($_POST['checkbox'])){
                foreach($_POST['checkbox'] as $val){
                    $ids[] = (int) $val;
                }
                $ids = implode("','", $ids);
                $this->mod_users->delTerreinUsers($ids, $this->db);
            }
        }

        // if successful redirect 
        header('Location:../Beheer/gebruikersTerreinen');

    }

    function berichten(){
        $this->checkPermission($this->mysqli);

        $berichten = $this->mod_messages->getMessages($this->db);

        include $this->app_path.'view/beheerBerichten_view.php';
    }

    function berichtenbeheer(){
        $this->checkPermission($this->mysqli);
        unset($_SESSION['errormessage']);

        if(isset($_POST['addBericht'])){
            if(!empty($_POST['titel'])){
                $datum   = $_POST['datum'];
                $titel   = $_POST['titel'];
                $bericht = $_POST['bericht'];
                $this->mod_messages->addMessage($datum, $titel, $bericht, $this->db);
            }
           
        }else if(isset($_POST['delBericht'])){
            if(!empty($_POST['checkbox'])){
                foreach($_POST['checkbox'] as $val){
                    $ids[] = (int) $val;
                }
                $ids = implode("','", $ids);
                $this->mod_messages->delMessages($ids, $this->db);
            }
           
        }else if(isset($_POST['pubBericht'])){
            if(!empty($_POST['checkbox'])){
                foreach($_POST['checkbox'] as $val){
                    $ids[] = (int) $val;
                }
                $ids = implode("','", $ids);
                $this->mod_messages->publishMessages($ids, $this->db);
            }
            
        }
        // if successful redirect 
        header('Location:../Beheer/berichten');
    }
}

?>