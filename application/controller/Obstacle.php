<?php

class Obstacle extends Controller {

    public function __construct()    
    {
        parent::__construct();
        //include_once($this->app_path."model/Mod_urldecoder.php"); 
        //$this->mod_urldecoder = new mod_urldecoder();
        include_once($this->app_path."model/Mod_header.php"); 
        $this->mod_header = new mod_header();
        include_once($this->app_path."model/Mod_sections.php"); 
        $this->mod_sections = new mod_sections();
        include_once($this->app_path."model/Mod_obstacles.php"); 
        $this->mod_obstacles = new mod_obstacles();
        include_once($this->app_path."model/Mod_materials.php"); 
        $this->mod_materials = new mod_materials();
        include_once($this->app_path."model/Mod_checkpoints.php"); 
        $this->mod_checkpoints = new mod_checkpoints();      
        include_once($this->app_path."model/Mod_helpers.php"); 
        $this->mod_helpers = new mod_helpers();

        $this->sec_session_start();
    }

    function index() 
    {   
        $this->checkPermission($this->mysqli);
    }

    function view() 
    {   
        $this->checkPermission($this->mysqli);

        $terreinid = $_SESSION['Terreinid'];

        //obstacle security
        $optObsSec = array("niet opgegeven",
                    "Door SBN goedgekeurd materiaal",
                    "Taak-Risico-Analyse",
                    "Constructieberekening",
                    "Labels" );

        if(isset($_SESSION['errormessage'])) {
            $warning = $_SESSION['errormessage'];
        } else {
            $warning = "";
        }

        $obstacleid='';
        if(isset($this->url[2])){
            $obstacleid = $this->url[2];
        }

        $obstacle     = $this->mod_obstacles->getObstacle($obstacleid, $this->db);
        if ($obstacle == FALSE){
            //geen obstacle id beschikbaar => terug naar secties
            $message .= "Geen hindernisnummer beschikbaar tijdens vorige bewerking. <br>Terug naar Sectieoverzicht gegaan.";
            $_SESSION['errormessage'] = $message;
            header('Location:'.WEBROOT.'/Sections');
        }

        $row            = $obstacle->fetch();
        $sectieid       = $row['Section_id'];
        $this->volgnr   = $row['Volgnr'];
        $this->Omschr   = $row['Omschr'];
        $this->Dat      = $row['DatCreate'];
        $this->maxH     = $row['MaxH'];
        $this->indSec   = $row['IndSecure'];
        $this->ChkQ1    = $row['ChkQ1'];
        $this->ChkQ2    = $row['ChkQ2'];
        $this->ChkQ3    = $row['ChkQ3'];
        $this->ChkQ4    = $row['ChkQ4'];
        $this->img      = $row['ImgPath'];

        if(empty($this->indSec)){
            $this->indSec = 0;
        };
        $veiligdoor     = $optObsSec[$this->indSec];

        $sectie = $this->mod_sections->getSectionById($sectieid ,$this->db);
        $row    = $sectie->fetch();
        $this->sectienaam = $row['Naam'];

        $obstacles = $this->mod_obstacles->getObstaclesBySection($terreinid, $this->sectienaam, $this->db);
        $imgstyle  = $this->mod_helpers->showObsPic($this->obsPath,$this->img,300,200);

        $ObstacleMaterials = $this->mod_obstacles->getObstacleMaterials($obstacleid, $this->db);
        $ObstacleCheckpoints = $this->mod_obstacles->getObstacleCheckpoints($obstacleid, $this->db);

        include $this->app_path.'view/obstacle_view.php';

    }

    function edit() 
    {   
        $this->checkPermission($this->mysqli);
        $terreinid = $_SESSION['Terreinid'];

        if(isset($_SESSION['errormessage'])) {
            $warning = $_SESSION['errormessage'];
        } else {
            $warning = "";
        }

        $obstacleid='';
        if(isset($this->url[3])){
            $obstacleid = $this->url[3];
        }

        $obstacle = $this->mod_obstacles->getObstacle($obstacleid, $this->db);
        $row            = $obstacle->fetch();
        $sectieid       = $row['Section_id'];
        $this->volgnr   = $row['Volgnr'];
        $this->img      = $row['ImgPath'];

        $sectie         = $this->mod_sections->getSectionById($sectieid ,$this->db);
        $row            = $sectie->fetch();
        $this->sectienaam = $row['Naam'];

        $obstacles = $this->mod_obstacles->getObstaclesBySection($terreinid, $this->sectienaam, $this->db);
        $imgstyle = $this->mod_helpers->showObsPic($this->obsPath,$this->img,60,50);

        $edit='';
        if(isset($this->url[2])){
            $edit = $this->url[2];
        }

        if($edit == 'materials'){

            $materials          = $this->mod_materials->getmaterialdetails($terreinid, $this->db);
            $ObstacleMaterials  = $this->mod_obstacles->getObstacleMaterials($obstacleid, $this->db);

            include $this->app_path.'view/obstacleMaterials_view.php';

        }else if($edit = 'checks'){

            $checkpoints         = $this->mod_checkpoints->getCheckpoints($terreinid, $this->db);
            $ObstacleCheckpoints = $this->mod_obstacles->getObstacleCheckpoints($obstacleid, $this->db);

            include $this->app_path.'view/obstacleCheckpoints_view.php';
        }

    
    }

    function material() 
    {   
        $this->checkPermission($this->mysqli);
        $terreinid = $_SESSION['Terreinid'];
        $sOmschr = $_POST['toelichting'];

        $obstacleid='';
        if(isset($this->url[2])){
            $obstacleid = $this->url[2];
        }

        if(isset($_POST['addMaterials'])){
            if(!empty($_POST['checkbox'])){
                foreach($_POST['checkbox'] as $val){
                    //$ids=array();
                    $ids[] = (int) $val; 
                }
                foreach($ids as $item) { //bind the values one by one
                    $this->mod_obstacles->addObstacleMaterials($obstacleid, $item, $this->db);
                }
            }

        }else if(isset($_POST['delMaterials'])){
            if(!empty($_POST['checkbox'])){
                foreach($_POST['checkbox'] as $val){
                    $ids[] = (int) $val;
                }
                $ids = implode("','", $ids);
                $this->mod_obstacles->delObstacleMaterials($ids, $this->db);
            }

        }else if(isset($_POST['editMaterialdescr'])){
            //checkbox needs to be selected
            if(!empty($_POST['checkbox'])){
                //only one checkbox selected
                if (count($_POST['checkbox'])<>1){
                    $_SESSION['errormessage'] = "Er mag maar een item geselecteerd worden bij bewerken!";
                    
                }else{
                    //determine which item is selected
                    foreach($_POST['checkbox'] as $val){
                        $sId = (int) $val;
                    }
                    //omschrijving needs to be filled
                    if (strlen($sOmschr)==0 ){
                        $_SESSION['errormessage'] = "Toelichting is niet gevuld!";
                
                    }else{
                        $this->mod_obstacles->updateObstacleMaterial($sId, $sOmschr, $this->db);
                    }
                }
            }else{
                $_SESSION['errormessage'] = "Er is niets geselecteerd om te bewerken!";
            }

        }

        // if successful redirect 
        header('Location:../../Obstacle/edit/materials/'.$obstacleid);

    }


    function checkpoints() 
    {   
        $this->checkPermission($this->mysqli);
        $terreinid = $_SESSION['Terreinid'];

        $obstacleid='';
        if(isset($this->url[2])){
            $obstacleid = $this->url[2];
        }

        if(isset($_POST['addCheckpoints'])){
            if(!empty($_POST['checkbox'])){
                foreach($_POST['checkbox'] as $val){
                    //$ids=array();
                    $ids[] = (int) $val; 
                }
                foreach($ids as $item) { //bind the values one by one
                    $this->mod_obstacles->addObstacleCheckpoints($obstacleid, $item, $this->db);
                }
            }

        }else if(isset($_POST['delCheckpoints'])){
            if(!empty($_POST['checkbox'])){
                foreach($_POST['checkbox'] as $val){
                    $ids[] = (int) $val;
                }
                $ids = implode("','", $ids);
                $this->mod_obstacles->delObstacleCheckpoints($ids, $this->db);
            }

        }

        // if successful redirect 
        header('Location:../../Obstacle/edit/checks/'.$obstacleid);

    }

    function uploadfile()
    {
        $obstacleid = $_POST['hindId'];

        if(isset($_POST['fileImport_x'])){
        
            $this->mod_helpers->uploadFile($_FILES, $this->obsPath, $obstacleid, NULL, $this->db);
        }

        if(isset($_POST['fileDelete_x'])){
            $img = $_POST['vimg'];

            if(!empty($_POST['vimg'])){
                $this->mod_obstacles->deleteObstacleImg($obstacleid, $this->obsPath, $img, $this->db);
            }
        }

        // if successful redirect 
        header('Location:../Obstacle/view/'.$obstacleid);

    }

}

?>