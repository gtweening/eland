<?php

class ObstaclesChecked extends Controller {

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
        include_once($this->app_path."model/Mod_helpers.php"); 
        $this->mod_helpers = new mod_helpers();

        $this->sec_session_start();
    }

    function index() 
    {   
        $this->checkPermission($this->mysqli);
        $terreinid = $_SESSION['Terreinid'];

        include $this->app_path.'view/obstaclesChecked_view.php';
    }

    function view(){
        $this->checkPermission($this->mysqli);        
        $terreinid  = $_SESSION['Terreinid'];

        if(isset($_SESSION['errormessage'])) {
            $warning = $_SESSION['errormessage'];
        } else {
            $warning = "";
        }

        //bepaal op basis van opgegeven jaar en kwartaal
        //de controleperiode die beschouwd moet worden
        if(isset($this->url[2])){
            $jaar     = $this->url[2];
        }else{
            $jaar     = $_POST['jaar'];
        }
        if(isset($this->url[3])){
            $kwartaal = $this->url[3];
        }else{
            $kwartaal = $_POST['kwartaal'];
        }
        
        $checkperiod = $this->mod_helpers->getcheckperiod($jaar, $kwartaal);
        $datend      = $checkperiod[1];
        $periode     = $checkperiod[0];

        $obstacleChecksArray        = array();
        $obstaclesToCheckArray      = array();
        $obstaclesToCheck           = $this->mod_obstacles->getviewtobechecked($periode,$datend, $this->db);

        while($row = $obstaclesToCheck->fetch() ){
            $obstacleid      = $row['Id'];
            $newdata = array(
                'Sectie'    => $row['Naam'],
                'Volgnr'    => $row['Volgnr'],
                'Omschr'    => $row['Omschr'],
                'ImgPath'   => $row['ImgPath']
                );
            $obstaclesToCheckArray[$obstacleid] = $newdata;

            $obstacleChecks      = $this->mod_obstacles->getObstacleChecksPeriod($obstacleid, $datend, $this->db);
            
            while($row4 = $obstacleChecks->fetch()){
                $newdata = array(
                        'datum'      => $row4['DatCheck'],
                        'status'     => $row4['ChkSt'],
                        'controleur' => $row4['CheckedBy'],
                        'notitie'    => $row4['Note']
                        );
                $obstacleChecksArray[$obstacleid][] = $newdata;
            }
        }


        include $this->app_path.'view/obstaclesCheckedList_view.php';
    }


    function execute(){
        $this->checkPermission($this->mysqli);
        $jaar       = $this->url[2];
        $kwartaal   = $this->url[3];
        $terreinid  = $_SESSION['Terreinid'];
        $datum      = $_POST['datum'];
        $controleur = $_POST['controleur'];
        $note       = $_POST['note'];
        $periode    = $_POST['periode'];
        $datend     = $_POST['datend'];

        if(isset($_POST['cstatus']) && $_POST['cstatus']==TRUE){
            $status="1";
        }else{
            $status="0";
        }

        if(isset($_POST['delObstacleChecked'])){
            if(!empty($_POST['checkbox'])){
                foreach($_POST['checkbox'] as $val){
                    $ids[] = (int) $val;
                }
                $ids = implode("','", $ids);
                $this->mod_obstacles->delObstacleCheckForPeriod($ids, $datend, $this->db);

            }else{
                $_SESSION['errormessage'] = "Er is niets geselecteerd om te verwijderen!";

            }

        }else if(isset($_POST['refreshObstacleChecked'])){ 
            if(!empty($_POST['checkbox'])){
                foreach($_POST['checkbox'] as $val){
                    $ids[] = (int) $val;
                }
                if(!empty($datum)){
                    $i = 1;
                    foreach($ids as $item) { //bind the values one by one
                        $this->mod_obstacles->addObstacleCheck($item, $datum, $status, $controleur, $note, $this->db);
                    }
                }

            }else{
                $_SESSION['errormessage'] = "Geen hindernis geselecteerd om controleresultaat aan toe te voegen!";
            }
        }

        
        // if successful redirect 
        header('Location:'.WEBROOT.'/ObstaclesChecked/view/'.$jaar.'/'.$kwartaal);

    }


}

?>