<?php

class Settings extends Controller {

    public function __construct()    
    {
        parent::__construct();
        //include_once($this->app_path."model/Mod_urldecoder.php"); 
        //$this->mod_urldecoder = new mod_urldecoder();
        include_once($this->app_path."model/Mod_header.php"); 
        $this->mod_header = new mod_header();
        include_once($this->app_path."model/Mod_terrein.php"); 
        $this->mod_terrein = new mod_terrein();
        include_once($this->app_path."model/Mod_users.php"); 
        $this->mod_users = new mod_users();
        include_once($this->app_path."model/Mod_helpers.php"); 
        $this->mod_helpers = new mod_helpers();

        $this->sec_session_start();
    }

    function index() 
    {   
        $this->checkPermission($this->mysqli);
        $terreinId = $_SESSION['Terreinid'];

        $savedAt     = "instellingen niet opgeslagen.";
        $terreinNaam = $this->mod_terrein->getTerreinnaam($this->db,$terreinId);
        $contactEma  = $this->mod_users->getUserEmail($this->db,$terreinId);

        if($_SERVER["REQUEST_METHOD"]=="POST"){
            $terreinId   = $_POST["terreinId"];
            $terreinNaam = $_POST["terreinNaam"];
            $contactEma  = $_POST["contactEma"];
            $_SESSION['Terreinnaam'] = $terreinNaam;
            
            // Assume an entry in the DB table exists for this user.                        
            $this->mod_terrein->editTerreinnaam($this->db, $terreinId, $terreinNaam);
            $this->mod_users->editUserEmail($this->db, $terreinId, $contactEma);      
        }

        include $this->app_path.'view/settings_view.php';

    }


}

?>