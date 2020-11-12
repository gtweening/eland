<?php

class ReportControlelijstPC extends Controller {

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
        include_once($this->app_path."model/Mod_reports.php"); 
        $this->mod_reports = new mod_reports();
        include_once($this->app_path."model/Mod_helpers.php"); 
        $this->mod_helpers = new mod_helpers();


        $this->sec_session_start();
    }

    function index() 
    {   
        $this->checkPermission($this->mysqli);
        $terreinid = $_SESSION['Terreinid'];

        if (isset($_SESSION['Terreinnaam'])){
            $Terreinnaam = $_SESSION['Terreinnaam'];
        }else{
            $Terreinnaam = "";
        }

        $materialTypes = $this->mod_materials->getmaterialdetails($terreinid, $this->db);

        if ($materialTypes->fetchColumn()==0){
            echo "<br>";
            echo '<a href="materials.php">Materiaal types</a> nog niet gevuld. Controlelijst kan niet gemaakt worden.' ;
            exit;
        }

        $getObstChkInfo = $this->mod_reports->getReportAll($terreinid, $this->db);
        $STH = $this->db->query($getObstChkInfo);
        $data = $STH->fetchAll(PDO::FETCH_ASSOC);
        //materialnames are dynamic
        //get materialnames out of columnnames
        //put materialnames into variable
        $i=0;
        if ($result = $this->mysqli->query($getObstChkInfo)) {
            $finfo = $result->fetch_fields();
            $i=1;
            foreach ($finfo as $val) {
                if (strpos($val->name,"m_") !== false){
                    $slen=strlen($val->name);
                    ${'m'.$i}= substr($val->name,2);
                    //count materialvariables
                    $i=$i+1;		
                }
            }
            $result->free();
        }


        include $this->app_path.'view/report_controlelijstPC.php';

    }

    function ExportXLS() 
    { 
        $data       = "";
        $header     = "";
        $TL         = $obstacleid = $this->url[2];
        $terreinid  = $_SESSION['Terreinid'];

        // header info
        $header  = "Controlelijst Parcours commissie\n";
        $header .= "Trainingslocatie;".$TL."\n";
        $header .= "Plaats:;;Controleurs terrein:\n";
        $header .= "Trainingstijden:;;Controleurs SBN:\n";
        $header .= "BHV / EHBO aanwezig tijdens trainingen:;Ja/Nee;Datum controle:\n";
        $header .= ";;Algemeen oordeel:\n";

        //data
        $getObstChkInfo = $this->mod_reports->getReportAll($terreinid, $this->db);
        $result         = $this->mysqli->query($getObstChkInfo);
        $STH            = $this->db->query($getObstChkInfo);
        $info           = $STH->fetchAll(PDO::FETCH_ASSOC);
        $info2          = $result->fetch_fields();
        $fields         = array(
                            "0" => "SHId",
                            "1" => "HOmschr",
                            "2" => "hoofdtouw",
                            "3" => "zekeringstouw"
                        );

  
        //materialnames are dynamic
        //get materialnames out of columnnames
        //put materialnames into variable
        $i=1;
        $material = '';
        foreach ($info2 as $val) {
            if (strpos($val->name,"m_") !== false){
                $slen=strlen($val->name);
                ${'m'.$i} = substr($val->name,2);
                $material .= ";".substr($val->name,2);
                array_push($fields, $val->name);
                //count materialvariables
                $i=$i+1;		
            }
        }
        $result->free();
        array_push($fields, "MaxH");
        array_push($fields, "DatCheck");
        array_push($fields, "ChkSt");
       

        //data titelrij info
        //1e regel
        $data .= ";;;;Soort materiaal";
        for ($c = 1; $c <= $i; $c++){
            $data .= ";";
        }
        $data .= ";;Controle PC\n";
        
        //2e regel
        $data .= ";;;;;";
        for ($c = 1; $c <= $i; $c++){
            $data .= ";";
        }
        $data .= ";;kleine rep;;Afgekeurd\n";

        //3e regel
        $data .= "Id;Omschrijving;Hoofdtouw;Zekeringstouw";
        $data .= $material;
        $data .=";Hoogte;Gecontroleerd;Status;Opmerking;Ja;Nee;Ja;Nee\n";


        // loop through resultset
        foreach ($info as $row) {
            foreach ($fields as $field) {
                switch ($field){
                    case (substr($field,0,2) == 'm_'):
                        if ($row[$field] == 1){
                            $data .= "*;";
                        }else{
                            $data .= ";";
                        }
                        break;

                    case "ChkSt":
                        if ($row[$field] == 0){
                            $data .= "!;";
                        }else {
                            $data .= "V;";
                        }
                        break;

                    default:
                        $data .= $row[$field] . ";";
                } 
            }
            $data.= "\n";
        }


        if ($data == "") {
            $data = "\n(0) Records Found!\n";
        }

        //export naar excel
        $filename = "PC_controlelijst_".$TL.".csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename='.$filename );
        header("Pragma: no-cache");
        header("Expires: 0");
        print "$header\n$data";
    }


    function ExportRTF(){

        $TL              = $obstacleid = $this->url[2];
        $terreinid       = $_SESSION['Terreinid'];
        $getObstChkInfo  = $this->mod_reports->getReportAll($terreinid, $this->db);
        $result          = $this->mysqli->query($getObstChkInfo);
        $STH             = $this->db->query($getObstChkInfo);
        $info            = $STH->fetchAll(PDO::FETCH_ASSOC);
        $header          = $this->mod_reports->rtf_header($TL);
        $header_section2 = $this->mod_reports->rtf_header_section2();
        $header_section3 = $this->mod_reports->rtf_header_section3($info);
        $body            = $this->mod_reports->rtf_body($info);

        //combine the data
        $data            = $header.$header_section2.$header_section3.$body."}";
        
        // Change the encoding of the file using iconv
        //$string_encoded = iconv( mb_detect_encoding( $header ), 'Windows-1252//TRANSLIT', $header );
        $string_encoded  = iconv( mb_detect_encoding( $data ), "WINDOWS-1252", $data );

        $path            = getcwd()."/downloads/";
        $filename        = "PC-controlelijst_".$TL.".rtf";
        $file            = $path.$filename;
        $myfile          = fopen($file, "w") or die("Unable to open file!");
        fwrite($myfile, $string_encoded);
        fclose($myfile);
        
        //$str='<a href="../downloads/PC-controlelijst_'.$TL.'.rtf" download>download</a>';
        //echo $str;
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/rtf");
        header("Content-Transfer-Encoding: binary");
        readfile("../downloads/".$filename);
    }


}

?>
