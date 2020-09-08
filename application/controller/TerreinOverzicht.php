<?php

class TerreinOverzicht extends Controller {

    public function __construct()    
    {
        parent::__construct();
        //include_once($this->app_path."model/Mod_urldecoder.php"); 
        //$this->mod_urldecoder = new mod_urldecoder();
        include_once($this->app_path."model/Mod_header.php"); 
        $this->mod_header = new mod_header();
        include_once($this->app_path."model/Mod_terrein.php"); 
        $this->mod_terrein = new mod_terrein();
        include_once($this->app_path."model/Mod_helpers.php"); 
        $this->mod_helpers = new mod_helpers();

        $this->sec_session_start();
    }

    function index() 
    { 
        $this->checkPermission($this->mysqli);
        $terreinid = $_SESSION['Terreinid'];

        if(isset($_SESSION['errormessage'])) {
          $warning = $_SESSION['errormessage'];
        } else {
          $warning = "";
        }

        $vimg = $this->mod_terrein->getTerreinPic($terreinid, $this->db);
        $imgstyle = $this->mod_helpers->showObsPic($this->imgTerrainPath,$vimg,650,650);

        include $this->app_path.'view/terreinoverzicht_view.php';

    }

    function execute(){
        unset($_SESSION['errormessage']);
        $terreinid = $_SESSION['Terreinid'];

        if(isset($_POST['fileImport_x'])){

          $vimg = $this->mod_terrein->getTerreinPic($terreinid, $this->db);

          if(strlen($vimg) <> 0) {
            $message = "Er is al een overzichtsafbeelding voor dit terrein. <br>";
            $message .="Verwijder eerst het bestaande bestand voordat u een nieuwe opslaat!";
            $_SESSION['errormessage'] = $message;

            }else{
              //geen bestaand bestand gevonden. toevoegen.
              $terreinid = $_POST["terreinId"];
              $imgPath = $_POST["imgPath"];
              $vimg = $_POST["vimg"];

              $this->mod_helpers->imgImport($this->db, $imgPath, $vimg, NULL, $terreinid );
              
            }
            
        }elseif(isset($_POST['fileDelete_x'])){
          if(!empty($_POST['vimg'])){
            $terreinid = $_POST["terreinId"];
            $imgPath = $_POST['imgPath'];
            $vimg = $_POST['vimg'];
            $this->mod_helpers->imgDelete($this->db, NULL, $vimg, $terreinid,$imgPath);
            
          }else{
            $message .="Geen bestand geselecteerd!";
            $_SESSION['errormessage'] = $message;

          }
          

        }

        // if successful redirect 
        header('Location:../TerreinOverzicht');

    }
}

?>