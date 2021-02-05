<?php

class Checkpoints extends Controller {

    public function __construct()    
    {
        parent::__construct();

        include_once($this->app_path."model/Mod_header.php"); 
        $this->mod_header = new mod_header();
        include_once($this->app_path."model/Mod_checkpoints.php"); 
        $this->mod_checkpoints = new mod_checkpoints();
        include_once($this->app_path."model/Mod_helpers.php"); 
        $this->mod_helpers = new mod_helpers();

        $this->sec_session_start();
    }

    function index(){
        $this->checkPermission($this->mysqli);

        //checkpoint info ophalen
        $checkpoints = $this->mod_checkpoints->getCheckpoints($_SESSION['Terreinid'], $this->db);
 
        if(isset($_SESSION['errormessage'])) {
            $warning = $_SESSION['errormessage'];
        } else {
            $warning = "";
        }

        include $this->app_path.'view/checkpoints_view.php';
    }

    function execute(){
        unset($_SESSION['errormessage']);

        if(isset($_POST['delChkPoint_x'])){
            if(!empty($_POST['checkbox'])){
                $selected = $_POST['checkbox'];
                $this->mod_checkpoints->delCheckpoints($selected, $this->db);

            }else{
                $_SESSION['errormessage'] = "Er is niets geselecteerd om te verwijderen!";
            }

        }else if(isset($_POST['addChkPoint'])){ 
            $terreinid = $_SESSION['Terreinid'];
            $checkpoint = $_POST['CheckPoint'];

            if(!empty($_POST['CheckPoint'])){
                $this->mod_checkpoints->addCheckpoint($terreinid, $checkpoint, $this->db);

            }else{
                $_SESSION['errormessage'] = "De controleomschrijving moet nog ingevuld worden!";
           
            }

        }else if(isset($_POST['editChkPoint_x'])){
            $terreinid = $_SESSION['Terreinid'];
            $checkpoint = $_POST['CheckPoint'];

            //checkbox needs to be selected
            if(!empty($_POST['checkbox'])){
                if (count($_POST['checkbox'])<>1){
                    $_SESSION['errormessage'] = "Er mag maar een item geselecteerd worden bij bewerken!";

                }else{
                    //determine which item is selected
                    foreach($_POST['checkbox'] as $val){
                        $sId = (int) $val;
                    }
                    //omschrijving needs to be filled
                    if (strlen($checkpoint)==0 ){
                        $_SESSION['errormessage'] = "Controle omschrijving is niet gevuld!";
                
                    }else{
                        $check = $this->mod_helpers->checkUnique($sId, $terreinid, 'TblCheckpoints', 'Omschr', $checkpoint, $this->db);

                        if($check == true){
                            $this->mod_checkpoints->editCheckpoint($sId, $checkpoint, $this->db);

                        }else{
                            $_SESSION['errormessage'] = "Controle omschrijving is elders gebruikt! Kies andere naam.";
                            return false;
                        }
                    }
                } 
                 
            }else{
                $_SESSION['errormessage'] = "Er is niets geselecteerd om te bewerken!";
            }
        }

        // if successful redirect 
        header('Location:../Checkpoints');
    }
}