<?php 

class ObstacleChecks extends Controller {

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

        if(isset($_SESSION['errormessage'])) {
            $warning = $_SESSION['errormessage'];
        } else {
            $warning = "";
        }

        $obstacleid='';
        if(isset($this->url[2])){
            $obstacleid = $this->url[2];
        }

        $obstacleid='';
        if(isset($this->url[2])){
            $obstacleid = $this->url[2];
        }

        $obstacle           = $this->mod_obstacles->getObstacle($obstacleid, $this->db);
        $row                = $obstacle->fetch();
        $sectieid           = $row['Section_id'];
        $this->volgnr       = $row['Volgnr'];
        $sectie             = $this->mod_sections->getSectionById($sectieid ,$this->db);
        $row                = $sectie->fetch();
        $this->sectienaam   = $row['Naam'];
        $obstacles          = $this->mod_obstacles->getObstaclesBySection($terreinid, $this->sectienaam, $this->db);
        $obstaclechecks     = $this->mod_obstacles->getObstacleChecks($obstacleid, $this->db);
        $obstaclecheckid    = 0;
        $datum              = '';
        $status             = '';
        $controleur         = '';
        $note               = '';

        if(isset($this->url[3])){
            $obstaclecheckid = $this->url[3];
            $oneobstaclecheck= $this->mod_obstacles->getOneObstacleCheck($obstacleid, $obstaclecheckid, $this->db);
            $row             = $oneobstaclecheck->fetch();
            $datum           = $row['DatCheck'];
            $status          = $row['ChkSt'];
            $controleur      = $row['CheckedBy'];
            $note            = $row['Note'];
        }

        include $this->app_path.'view/obstacleChecks_view.php';

    }

    function execute(){
        $this->checkPermission($this->mysqli);
        unset($_SESSION['errormessage']);

        $obstacleid='';
        if(isset($this->url[2])){
            $obstacleid = $this->url[2];
        }

        $terreinid  = $_SESSION['Terreinid'];
        $datum      = $_POST['datum'];
        $status     = $_POST['status'];
        $controleur = $_POST['controleur'];
        $note       = $_POST['note'];

        if(isset($_POST['delObstacleCheck_x'])){
            if(!empty($_POST['checkbox'])){
                foreach($_POST['checkbox'] as $val){
                    $ids[] = (int) $val;
                }
                $ids = implode("','", $ids);
                $this->mod_obstacles->delObstacleCheck($ids, $this->db);

            }else{
                $_SESSION['errormessage'] = "Er is niets geselecteerd om te verwijderen!";

            }

        }else if(isset($_POST['addObstacleCheck'])){ 
            if(isset($status) && $status==TRUE){
                $status="1";
            }else{
                $status="0";
            }

            if(!empty($datum)){
                $this->mod_obstacles->addObstacleCheck($obstacleid, $datum, $status, $controleur, $note, $this->db);

            }else{
                $_SESSION['errormessage'] = "Er is geen datum opgegeven!";
            }

        }else if(isset($_POST['editObstacleCheck_x'])){
            //checkbox needs to be selected
            if(!empty($_POST['checkbox'])){
                //only one checkbox selected
                if (count($_POST['checkbox'])<>1){
                    $_SESSION['errormessage'] = "Er mag maar een item geselecteerd worden bij bewerken!";
                    
                }else{
                    //determine which item is selected
                    foreach($_POST['checkbox'] as $val){
                        $sId[] = (int) $val;
                    }
                    $sId = implode("','", $sId);

                    if(isset($status) && $status==TRUE){
                        $status="1";
                    }else{
                        $status="0";
                    }

                    if(isset($status) && !empty($datum)){
                        $this->mod_obstacles->updateObstacleCheck($sId, $datum, $status, $controleur, $note, $this->db);

                    }else{
                        $_SESSION['errormessage'] = "Er is geen datum opgegeven!";
                    }
                }
            
            }else{
                $_SESSION['errormessage'] = "Er is niets geselecteerd om te bewerken!";
            
            }
        }

        // if successful redirect 
        header('Location:../view/'.$obstacleid);

    }
}

?>