<?php

class ReportAll extends Controller {

    public function __construct()    
    {
        parent::__construct();
        //include_once($this->app_path."model/Mod_urldecoder.php"); 
        //$this->mod_urldecoder = new mod_urldecoder();
        include_once($this->app_path."model/Mod_header.php"); 
        $this->mod_header = new mod_header();
        include_once($this->app_path."model/Mod_obstacles.php"); 
        $this->mod_obstacles = new mod_obstacles();
        include_once($this->app_path."model/Mod_terrein.php"); 
        $this->mod_terrein = new mod_terrein();
        include_once($this->app_path."model/Mod_helpers.php"); 
        $this->mod_helpers = new mod_helpers();

        $this->sec_session_start();
    }

    function index() 
    {   
        $this->checkPermission($this->mysqli);        
        $terreinid  = $_SESSION['Terreinid'];
        $terreinPic = $this->mod_terrein->getTerreinPic($terreinid, $this->db);
        $imgstyle   = $this->mod_helpers->showObsPic($this->imgTerrainPath,$terreinPic,700,700);
        $title      = "Hindernissen";

        $obstacleMaterialsArray     = array();
        $obstacleCheckpointsArray   = array();
        $obstacleChecksArray        = array();
        $obstaclesToCheckArray      = array();
        $obstacles                  = $this->mod_obstacles->getAllObstaclesForTerrain($this->db);

        while($row = $obstacles->fetch() ){
            $w          = 0;
            $h          = 0;
            $obstacleid = $row['Id'];

            if($row['ImgPath'] != ''){
                list($w,$h) = getimagesize($this->obsPath . $row['ImgPath']);
                $ratio      = $w/$h;
                switch (TRUE){
                    case ($ratio <=1):
                        $h = 300;
                        $w = 300 * $ratio;
                        break;
                    case ($ratio > 1):
                        $w = 500;
                        $h = 500 / $ratio;
                        break; 
                }
            }
            $newdata = array(
                'Sectie'    => $row['Naam'],
                'Volgnr'    => $row['Volgnr'],
                'Omschr'    => $row['Omschr'],
                'ImgPath'   => $row['ImgPath'],
                'w'         => $w,
                'h'         => $h
            );
            $obstaclesToCheckArray[$obstacleid] = $newdata;
           
            $obstacleMaterials   = $this->mod_obstacles->getObstacleMaterials($obstacleid,$this->db);
            $obstacleCheckpoints = $this->mod_obstacles->getObstacleCheckpoints($obstacleid,$this->db);
            $obstacleChecks      = $this->mod_obstacles->getObstacleChecks($obstacleid,$this->db);

            while($row2 = $obstacleMaterials->fetch() ){
            
                $newdata = array(
                        'material'     => $row2['tmtomschr'],
                        'omschrijving' => $row2['Omschr'],
                        'toelichting'  => $row2['Aantal']
                        );
                $obstacleMaterialsArray[$obstacleid][] = $newdata;
            } 
              
            while($row3 = $obstacleCheckpoints->fetch()){
                $newdata = array(
                        'omschrijving' => $row3['Omschr']
                        );
                $obstacleCheckpointsArray[$obstacleid][] = $newdata;
            }
            
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

        include $this->app_path.'view/report_obstacles.php';


    }


}

?>