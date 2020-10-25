<?php

class Sections extends Controller {

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

        $sort='';
        if(isset($this->url[2])){
            if(substr($this->url[2],0,1) == 's'){
                $sort = substr($this->url[2],2,1);
            }
        }

        //$url = $this->mod_urldecoder->urldecoder($this->url[1]);
        //$userid = $url[0]
        //$terreinid = $url[1];

        //section info ophalen
        $sections = $this->mod_sections->getSections($_SESSION['Terreinid'], $sort, $this->db);
       // print_r($GLOBALS['errormessage']);
        //echo "<br>";

        if(isset($_SESSION['errormessage'])) {
            $warning = $_SESSION['errormessage'];
        } else {
            $warning = "";
        }

        include $this->app_path.'view/sections_view.php';
    }

    function execute(){
        unset($_SESSION['errormessage']);

        if(isset($_POST['delSection'])){
            if(!empty($_POST['checkbox'])){
                $selected = $_POST['checkbox'];
                $this->mod_sections->delSection($selected, $this->db);

            }else{
                $_SESSION['errormessage'] = "Er is niets geselecteerd om te verwijderen!";

            }

        }else if(isset($_POST['addSection'])){ 
            $terreinid = $_SESSION['Terreinid'];
            $sectienaam = $_POST['sectienaam'];
            $sectieomschr = $_POST['sectieomschr'];

            if(!empty($sectienaam)){
                if(strrchr($sectienaam, "/") == TRUE){
                    $_SESSION['errormessage'] = "De sectienaam mag geen '/' bevatten!";
                }else{
                    $this->mod_sections->addSection($terreinid, $sectienaam, $sectieomschr, $this->db);
                }

            }else{
                $_SESSION['errormessage'] = "De sectienaam moet nog ingevuld worden!";
           
            }

        }else if(isset($_POST['editSection'])){
            $terreinid = $_SESSION['Terreinid'];
            $sNaam=$_POST['sectienaam'];
            $sOmschr=$_POST['sectieomschr'];
            
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
                    if (strlen($sOmschr)==0 or strlen($sNaam)==0){
                        $_SESSION['errormessage'] = "Omschrijving of sectienaam is niet gevuld!";
                
                    }else{

                        $check = $this->mod_helpers->checkUnique($sId, $terreinid, 'TblSections', 'Naam', $sNaam, $this->db);

                        if($check == true){
                            if(strrchr($sNaam, "/") == TRUE){
                                $_SESSION['errormessage'] = "De sectienaam mag geen '/' bevatten!";
                            }else{
                                $this->mod_sections->editSection($sId, $sNaam, $sOmschr, $this->db);
                            }
                           
                        }else{
                            $_SESSION['errormessage'] = "Sectienaam is elders gebruikt! Kies andere naam!";

                        } 
                    }
                }

            }else{
                $_SESSION['errormessage'] = "Er is niets geselecteerd om te bewerken!";
            }
        }

        // if successful redirect 
        header('Location:../Sections');
    }

    function sn()
    {
        $this->checkPermission($this->mysqli);

        $this->vsectionname="";
        if(isset($this->url[2])){
            $this->vsectionname = $this->url[2];
        }
        
        $sections = $this->mod_sections->getSectionByName($_SESSION['Terreinid'], $this->vsectionname, $this->db);
        $row=$sections->fetch();
        $this->vsectionid = $row['Id'];

        $this->viewObstacleSections();
    }


    function viewObstacleSections()
    {
        //obstacle security
        $optObsSec = array("niet opgegeven",
                   "Door SBN goedgekeurd materiaal",
                   "Taak-Risico-Analyse",
                   "Constructieberekening",
                   "Labels" );
        //set default values for obstacle properties
        $inputvolgnr="";
        $inputdate="";
        $inputh="";
        $inputomschr="";
        $inputobssec="";

        if(isset($this->url[3])){
            $this->obstacleid = $this->url[3];
            $obstacle= $this->mod_obstacles->getObstacle($this->obstacleid, $this->db);

            $row=$obstacle->fetch();
            $inputvolgnr    =$row['Volgnr'];
            $inputdate      =$row['DatCreate'];
            $inputh         =$row['MaxH'];
            $inputomschr    =$row['Omschr'];
            $inputobssec    =$row['IndSecure'];
        }
       
        $obstacles = $this->mod_obstacles->getObstaclesBySection($_SESSION['Terreinid'], $this->vsectionname, $this->db);
       
        if(isset($_SESSION['errormessage'])) {
            $warning = $_SESSION['errormessage'];
        } else {
            $warning = "";
        }

        include $this->app_path.'view/obstaclespersection_view.php';
    }

    function executeObstacleSection(){
        unset($_SESSION['errormessage']);

        if(isset($_POST['delObstacle'])){
            $terreinid = $_SESSION['Terreinid'];
            $vsectionid = $_POST['sectionId'];
            $vsectionname = $_POST['sectionName']; 

            if(!empty($_POST['checkbox'])){
                $selected = $_POST['checkbox'];
                foreach($selected as $val){
                    $ids[] = (int) $val;
                }
                $ids = implode("','", $ids);
                $this->mod_obstacles->delObstacles($ids, $this->db);

            }else{
                $_SESSION['errormessage'] = "Er is niets geselecteerd om te verwijderen!";

            }

        }else if(isset($_POST['addObstacle'])){ 
            $terreinid    = $_SESSION['Terreinid'];
            $vsectionid   = $_POST['sectionId'];
            $vsectionname = $_POST['sectionName']; 

            if(is_numeric($_POST['volgnr'])){
                $obstacles = $this->mod_obstacles->getObstacleBySectionId($vsectionid, $this->db);

                while($rows = $obstacles->fetch()){
                    if($_POST['volgnr'] == $rows['Volgnr']){
                        $_SESSION['errormessage'] = "Er is al een hindernis met dit volgnummer!";
                        break;
                    }
                }
                $this->mod_obstacles->addObstacle($vsectionid, $_POST['volgnr'], $_POST['hindernisOmschr'],$_POST['maxh'], $_POST['datcreate'], $_POST['obsSec'], $this->db );

            }else{
                $_SESSION['errormessage'] = "De hindernis heeft geen nummeriek volgnummer!";
            
            }

        }else if(isset($_POST['editObstacle'])){
            $terreinid      = $_SESSION['Terreinid'];
            $vsectionid     = $_POST['sectionId'];
            $vsectionname   = $_POST['sectionName']; 
            $sOmschr        = $_POST['hindernisOmschr'];
            $volgnr         = $_POST['volgnr'];
            $maxh           = $_POST['maxh'];
            $datcreate      = $_POST['datcreate'];
            $obsSec         = $_POST['obsSec'];
            
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
                        $_SESSION['errormessage'] = "Omschrijving is niet gevuld!";
                
                    }else{
                        //check if volgnr already exists
                        $check = $this->mod_obstacles->getObstacleBySectionId($vsectionid, $this->db);

                        while($rows = $check->fetch()){
                            if($volgnr == $rows['Volgnr']){
                                $volgnrok = true;
                                break;
                            }else{
                                $volgnrok = false;     
                            }
                        }

                        if($volgnrok == true){
                            $this->mod_obstacles->editObstacle($sId, $volgnr, $sOmschr, $maxh, $datcreate, $obsSec, $this->db);
                
                        }else{
                            $_SESSION['errormessage'] = "Het ingevulde volgnummer is anders dan de geselecteerde hindernis!";

                        } 
                    }
                }

            }else{
                $_SESSION['errormessage'] = "Er is niets geselecteerd om te bewerken!";
            }
        }

        // if successful redirect 
        header('Location:../Sections/sn/'.$vsectionname);
    }
    
}

?>