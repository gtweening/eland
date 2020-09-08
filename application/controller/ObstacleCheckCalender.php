<?php

class ObstacleCheckCalender extends Controller {

    public function __construct()    
    {
        parent::__construct();
        //include_once($this->app_path."model/Mod_urldecoder.php"); 
        //$this->mod_urldecoder = new mod_urldecoder();
        include_once($this->app_path."model/Mod_header.php"); 
        $this->mod_header = new mod_header();
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

        $calender = $this->mod_obstacles->getObstacleChecksCalender($terreinid, $this->db);

        include $this->app_path.'view/obstacleCheckCalender_view.php';
    }

    function execute(){
        $this->checkPermission($this->mysqli);

        unset($_SESSION['errormessage']);

        if(isset($_POST['save'])){
            for($i=1; $i <= 4; $i++){
                $kwartaal = 'checkQ'.$i;
                $q        = $_POST[$kwartaal];
                $ids      = array();

                if(!empty($q)){
                    foreach($q as $val){
                        $ids[] = (int) $val;
                    }
                    $ids = implode("','", $ids);
                    $this->mod_obstacles->editObstacleCalenderOn($ids, $i, $this->db);
                    $this->mod_obstacles->editObstacleCalenderNotOn($ids, $i, $this->db);

                }else {
                    $obstacleids = array();
                    $terreinid   = $_SESSION['Terreinid'];
                    $calender    = $this->mod_obstacles->getObstacleChecksCalender($terreinid, $this->db);

                    while ($rows = $calender->fetch() ) {
                        $ids[] = (int) $rows['Id'];
                    }
                    $ids = implode("','", $ids);

                    $this->mod_obstacles->editObstacleCalenderOff($ids, $i, $this->db);
                }
            }
        }

         // if successful redirect 
         header('Location:../ObstacleCheckCalender');
    }


}

?>