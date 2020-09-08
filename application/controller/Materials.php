<?php

class Materials extends Controller {

    public function __construct()    
    {
        parent::__construct();
        //include_once($this->app_path."model/Mod_urldecoder.php"); 
        //$this->mod_urldecoder = new mod_urldecoder();
        include_once($this->app_path."model/Mod_header.php"); 
        $this->mod_header = new mod_header();
        include_once($this->app_path."model/Mod_materials.php"); 
        $this->mod_materials = new mod_materials();
        include_once($this->app_path."model/Mod_helpers.php"); 
        $this->mod_helpers = new mod_helpers();

        $this->sec_session_start();
    }

    function index() 
    {   
        $this->checkPermission($this->mysqli);

        //section info ophalen
        $materials = $this->mod_materials->getMaterials($_SESSION['Terreinid'], $this->db);

        if(isset($_SESSION['errormessage'])) {
            $warning = $_SESSION['errormessage'];
        } else {
            $warning = "";
        }

        include $this->app_path.'view/materials_view.php';
    }

    function execute(){
        unset($_SESSION['errormessage']);

        if(isset($_POST['delMaterialType'])){
            if(!empty($_POST['checkbox'])){
                $selected = $_POST['checkbox'];
                $this->mod_materials->delMaterialType($selected, $this->db);

            }else{
                $_SESSION['errormessage'] = "Er is niets geselecteerd om te verwijderen!";

            }

        }else if(isset($_POST['addMaterialType'])){ 
            $terreinid = $_SESSION['Terreinid'];
            $material = $_POST['materialtype'];

            if(!empty($_POST['materialtype'])){
                $this->mod_materials->addMaterialType($terreinid, $material, $this->db);

            }else{
                $_SESSION['errormessage'] = "De materiaalomschrijving moet nog ingevuld worden!";
           
            }

        }else if(isset($_POST['editMaterialType'])){
            $terreinid = $_SESSION['Terreinid'];
            $material = $_POST['materialtype'];
            
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
                    if (strlen($material)==0){
                        $_SESSION['errormessage'] = "Omschrijving of materiaaltype is niet gevuld!";
                
                    }else{

                        $check = $this->mod_helpers->checkUnique($sId, $terreinid, 'TblMaterialTypes', 'Omschr', $material, $this->db);

                        if($check == true){
                            $this->mod_materials->editMaterialType($sId, $material, $this->db);
                
                        }else{
                            $_SESSION['errormessage'] = "Materiaalltype is al in gebruik! Kies andere naam!";
                        	
                        } 
                    }
                }

            }else{
                $_SESSION['errormessage'] = "Er is niets geselecteerd om te bewerken!";
            }
        }

        // if successful redirect 
        header('Location:../Materials');
    }
}