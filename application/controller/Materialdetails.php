<?php

class Materialdetails extends Controller {

    public function __construct()    
    {
        parent::__construct();
        //include_once($this->app_path."model/Mod_urldecoder.php"); 
        //$this->mod_urldecoder = new mod_urldecoder();
        include_once($this->app_path."model/Mod_header.php"); 
        $this->mod_header = new mod_header();
        include_once($this->app_path."model/Mod_materials.php"); 
        $this->mod_materials = new mod_materials();
        include_once($this->app_path."model/Mod_materialSuppliers.php"); 
        $this->mod_matsup = new mod_materialSuppliers();
        include_once($this->app_path."model/Mod_helpers.php"); 
        $this->mod_helpers = new mod_helpers();

        $this->sec_session_start();
    }

    function index() 
    {   
        $this->checkPermission($this->mysqli);

        //material info ophalen
        $materials       = $this->mod_materials->getMaterials($_SESSION['Terreinid'], $this->db);
        $materialdetails = $this->mod_materials->getMaterialdetails($_SESSION['Terreinid'], $this->db);
        $matsup          = $this->mod_matsup->getMaterialSuppliers($this->db);
        $mattype         = $this->mod_matsup->getMaterialTypesofSuppliers($this->db);

        $i=0;
        while($r   = $matsup->fetch()){
            $arrmatsup[$i]['Id'] = $r['Id'];
            $arrmatsup[$i]['MaterialType'] = $r['MaterialType'];
            $arrmatsup[$i]['Supplier'] = $r['Supplier'];
            $i++;
        }
        while($row = $mattype->fetch()){
            $arrmattype[] = $row['MaterialType']; 
        }

        if(isset($_SESSION['errormessage'])) {
            $warning = $_SESSION['errormessage'];
        } else {
            $warning = "";
        }

        include $this->app_path.'view/materialdetails_view.php';
    }

    function execute(){
        unset($_SESSION['errormessage']);

        if(isset($_POST['delMaterial_x'])){
            if(!empty($_POST['checkbox'])){
                $selected = $_POST['checkbox'];
                $this->mod_materials->delMaterial($selected, $this->db);

            }else{
                $_SESSION['errormessage'] = "Er is niets geselecteerd om te verwijderen!";

            }

        }else if(isset($_POST['addMaterial'])){ 
            $terreinid = $_SESSION['Terreinid'];
            switch ($_POST['rope']){
                case 'securerope':
                    $srope=1;
                    $mrope=0;
                    break;
                case 'mainrope':
                    $mrope=1;
                    $srope=0;
                    break;
                default:
                    $mrope=0;
                    $srope=0;
                    break;
            }
            $omschr   = utf8_decode($_POST['material']);
            print_r($_POST);
            if(isset($_POST['supplier'])){
                $supplier = $_POST['supplier'];
            }else{
                $supplier = 'NULL';
            }

            if(!empty($_POST['material'])){
                $this->mod_materials->addMaterial($terreinid, $omschr, $_POST['mattype'], $srope, $mrope, $supplier, $this->db);
               
            }else{
                $_SESSION['errormessage'] = "De materiaalomschrijving moet nog ingevuld worden!";
            }

        }else if(isset($_POST['editMaterial_x'])){
            $terreinid = $_SESSION['Terreinid'];
            switch ($_POST['rope']){
                case 'securerope':
                    $srope=1;
                    $mrope=0;
                    break;
                case 'mainrope':
                    $mrope=1;
                    $srope=0;
                    break;
                default:
                    $mrope=0;
                    $srope=0;
                    break;
            }
            $sOmschr  = trim($_POST['material']);
            $mattype  = $_POST['mattype'];
            $supplier = $_POST['supplier'];

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
                    if (strlen($sOmschr)<>0){
                        $this->mod_materials->editMaterial($terreinid, $sId, $sOmschr, $mattype, $srope, $mrope, $supplier, $this->db);
                        
                    }else{
                        $_SESSION['errormessage'] = "Omschrijving is niet gevuld!";
                    }
                }

            }else{
                $_SESSION['errormessage'] = "Er is niets geselecteerd om te bewerken!";
            }
        }

        // if successful redirect 
        header('Location:../Materialdetails');
    }
}