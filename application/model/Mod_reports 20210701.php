<?php
        
class mod_reports{

    function getReportAll($terreinid, $db){
        //get materialtypes per material
        $getMatType="select tm.id,concat('m_',tmt.Omschr) as MOmschr,1 as val
                    from TblMaterials tm right join TblMaterialTypes tmt              
                        on tm.MaterialType_id = tmt.id 
                    where tm.Terrein_id ='$terreinid' ";
        $STH = $db->query($getMatType);
        $STH->setFetchMode(PDO::FETCH_ASSOC);

        //materiaaltypes in array zetten
        $mattypes = array();
        while($rows=$STH->fetch()){
            $MOmschr = ($rows['MOmschr']);
            if($MOmschr !== NULL){
                if(in_array($MOmschr,$mattypes)){
                    //materiaal komt al voor. niets doen.
            }else{
                //materiaal komt nog niet voor in array. toevoegen
                    array_push($mattypes,$MOmschr);
                }
            }
        }

        $sPivotScript = '';
        foreach($mattypes as $value){
            $sPivotScript .= "max(if(t.MOmschr = '".$value."', t.val, NULL)) as ".$value.", " ;
        }
        //laatste , weghalen voor juiste query
        $sPivotScript = substr($sPivotScript,0,-2);
        //echo $sPivotScript;

        //get obstacle id for all materials (outer join)
        $getObstMat="select distinct tom.Obstacle_id as HId,mat.MOmschr, mat.val, tom.Aantal 
                        from TblObstacleMaterials tom left join (".$getMatType.") as mat 
                            on mat.id=tom.Material_id 
                        union
                        select distinct tom.Obstacle_id as HId,mat.MOmschr, mat.val, tom.Aantal 
                        from TblObstacleMaterials tom right join (".$getMatType.") as mat 
                            on mat.id=tom.Material_id ";

        //get materialtypes per material in pivot table
        $getMat="select t.HId, t.Aantal, ".$sPivotScript." 
                from (".$getObstMat.") as t 
                group by t.HId";



        //join getObstMat with Obstacle info
        //add leading zero if column length=1 (0-9)
        $getObstMatInfo="select tob.Id, tob.Section_id, 
                        case when tob.Volgnr between 0 and 9
                            then concat('0',tob.Volgnr) else tob.Volgnr
                            end as Volgnr, 
                            tob.Omschr as HOmschr,
                            tob.DatCreate, tob.MaxH, tomt.*  
                        from TblObstacles tob left join (".$getMat.") as tomt 
                        on tob.Id=tomt.HId";

        //get sectioninfo and materialtype per obstacle
        $getSecObstMatInfo="select distinct concat(ts.Naam,' ',tobm.Volgnr) as SHId, tobm.* 
                            from TblSections ts, (".$getObstMatInfo.") as tobm 
                            where tobm.Section_id = ts.Id 
                              and ts.Terrein_id ='$terreinid' 
                            order by SHId";


        //get materiaalomschr voor hoofdtouw en zekeringstouw
        $getMainSafetyRope = "select Obstacle_id, max(if(IndMainRope=1,Omschr,'')) as hoofdtouw, 
                                                    max(if(IndSecureRope=1,Omschr,'')) as zekeringstouw  
                                from (select tom.Obstacle_id, tom.Aantal, tm.Omschr, tm.IndMainRope, tm.IndSecureRope
                                    from TblObstacleMaterials tom inner join TblMaterials tm 
                                        on tom.Material_id = tm.Id 
                                where tm.IndMainRope = 1 or tm.IndSecureRope = 1) as t 
                                group by Obstacle_id";

        $getMaxDatChk = "select Id, Obstacle_id, DatCheck,ChkSt 
                         from TblObstacleChecks toc1 
                              inner join (
                                select max(Id) as maxId 
                                from TblObstacleChecks 
                                group by Obstacle_id) toc2 on toc1.Id = toc2.maxId" ;
    
        //get sectioninfo, materialtype, max obstacle check per obstacle
        $getObstChkInfoA="select tobm2.*, toc.DatCheck, toc.ChkSt 
                        from (".$getSecObstMatInfo.") as tobm2 left join 
                        (" .$getMaxDatChk.") as toc 
                        on tobm2.Id=toc.Obstacle_id ";

        //add main, safety rope to list
        $getObstChkInfo = "select tobm3.*, msr.hoofdtouw, msr.zekeringstouw 
                        from (".$getObstChkInfoA.") as tobm3 left join 
                        (".$getMainSafetyRope.") as msr 
                        on tobm3.Id = msr.Obstacle_id ";

        return $getObstChkInfo;

        

        $STH0 = $db->query($getObstChkInfo);
        if ($STH0 !== FALSE){
            $info = $STH0->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //	echo $getObstChkInfo;
            $info = "Er is een fout opgetreden bij het ophalen van de data. Dit is bekend. Er wordt aan gewerkt."."<br>";
            $info .= "Controleer of er spaties zitten in het materiaal type. Vervang deze door '_'.";
            exit;
        }

        return $info;
    }


    function rtf_header($TL){
   
    //header data
    $header = "{\\rtf1\ansi\ansicpg1252\deff0\deflang1043
     {\\fonttbl{\\f0\\froman\\fprq2\\fcharset0 Times New Roman;}
     {\\f1\\fswiss\\fprq2\\fcharset0 Calibri;}{\\f2\\fnil\\fcharset0 Calibri;}
     {\header \\tql\\tx0\\tqr\\tx15400 \\f1 Controlelijst parcourscommissie Survivalrun Bond Nederland \\tab Trainingslocatie: ".$TL."\pard}
     {\\footer \qr \\f1 \chpgn}
     }
     {\colortbl ;\\red0\green255\blue255;\\red242\green242\blue242;\\red0\green102\blue255;}
     {\*\generator Msftedit 5.41.21.2510;}
     \\viewkind4\uc1\\trowd
     \\formshade{\*\pgdscno0}\landscape\paperh11906\paperw16838\margl650\margr650\margt570\margb570\sectd\sbknone\sectunlocked1\lndscpsxn\pgndec\pgwsxn16838\pghsxn11906\marglsxn650\margrsxn650\margtsxn570\margbsxn570\\ftnbj\\ftnstart1\\ftnrstcont\\ftnnar\aenddoc\aftnrstcont\aftnstart1\aftnnrlc";
     
     $titel="\\trgaph70\\trleft-108\\trrh771\\trbrdrl\brdrs\brdrw10 
      \\trpaddl70\\trpaddr70\\trpaddfl3\\trpaddfr3
     \cellx9923\clvmgf\clbrdrb\brdrw10\brdrs 
     \cellx15550\pard\intbl\b\\f1\\fs40 Controlelijst parcourscommissie
      \\fs22\cell\b0\cell\\row\\trowd\\trgaph70\\trleft-108\\trrh427\\trbrdrl\brdrs\brdrw10 \\trpaddl70\\trpaddr70\\trpaddfl3\\trpaddfr3
     \clcbpat2\clbrdrb\brdrw10\brdrs 
     \cellx1701\clcbpat2\clbrdrb\brdrw10\brdrs 
     \cellx9923\clvmrg\clbrdrt\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx15550\pard\intbl\b Trainingslocatie:\cell ".$TL."\cell\b0\cell\\row\pard\sa200\sl276\slmult1\lang19
     ";
     
     return $header;
     }


    function rtf_header_section2(){
        $celborder = "\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs";
                      
        $header_sect2="
        \\trowd\\trgaph70\\trleft-108\\trrh397\\trbrdrl\brdrs\brdrw10 
        \\trpaddl70\\trpaddr70\\trpaddfl3\\trpaddfr3
        ".$celborder." 
        \cellx3828".$celborder." 
        \cellx7797".$celborder." 
        \cellx9923".$celborder." 
        \cellx15550\pard\intbl\\f2 Plaats\cell\cell Controleurs terrein:\\f0\cell\cell\\row\\trowd\\trgaph70\\trleft-108\\trrh397\\trbrdrl\brdrs\brdrw10 \\trbrdrt\brdrs\brdrw10 \\trbrdrr\brdrs\brdrw10 \\trbrdrb\brdrs\brdrw10 \\trpaddl70\\trpaddr70\\trpaddfl3\\trpaddfr3
        \clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
        \cellx3828".$celborder." 
        \cellx7797".$celborder." 
        \cellx9923".$celborder." 
        \cellx15550\pard\intbl\\f2 Trainingstijden\cell\cell Controleurs SBN:\\f0\cell\cell\\row\\trowd\\trgaph70\\trleft-108\\trrh397\\trbrdrl\brdrs\brdrw10 \\trbrdrt\brdrs\brdrw10 \\trbrdrr\brdrs\brdrw10 \\trbrdrb\brdrs\brdrw10 \\trpaddl70\\trpaddr70\\trpaddfl3\\trpaddfr3
        ".$celborder." 
        \cellx3828".$celborder." 
        \cellx7797".$celborder." 
        \cellx9923".$celborder." 
        \cellx15550\pard\intbl\\f2 BHV/EHBO aanwezig tijdens trainingen:\cell\cell Datum controle:\\f0\cell\cell\\row\\trowd\\trgaph70\\trleft-108\\trrh397\\trbrdrl\brdrs\brdrw10 \\trbrdrt\brdrs\brdrw10 \\trbrdrr\brdrs\brdrw10 \\trbrdrb\brdrs\brdrw10 \\trpaddl70\\trpaddr70\\trpaddfl3\\trpaddfr3
        \clbrdrt\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
        \cellx3844\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
        \cellx7797".$celborder." 
        \cellx9923".$celborder." 
        \cellx15550\pard\intbl\cell\cell\\f2 Algemeen oordeel:\\f0\cell\cell\\row\pard\sa200\sl276\slmult1\\f1
        ";
        
        return $header_sect2;
        
    }


    function rtf_header_section3($obsdata){

        //cel breedtes ophalen
        $celwidth = $this->rtf_celwidth($obsdata);	
     
         
     $header_sect3="
     \\trowd\\trgaph70\\trleft-108\\trbrdrl\brdrs\brdrw10 \\trbrdrt\brdrs\brdrw10 \\trbrdrr\brdrs\brdrw10 \\trbrdrb\brdrs\brdrw10 \\trpaddl70\\trpaddr70\\trpaddfl3\\trpaddfr3
     \clvmgf\clvertalc\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc1']."
     \clvmgf\clvertalc\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc2']."
     \clvmgf\clvertalc\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc3']."
     \clvmgf\clvertalc\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc4']."
     \clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc5']."
     \clvmgf\clvertalc\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc6']."
     \clvmgf\clvertalc\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc7']."
     \clvmgf\clvertalc\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc8']."
     \clvertalc\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx15550
     \pard\intbl\\nowidctlpar\qc\lang19\b\\f1\\fs20 Id
     \cell Omschrijving
     \cell Hoofd\par
     touw
     \cell Zekerings\par
     touw
     \cell Soort
     \cell Hoogte\par
     [m]
     \cell Datum\par
     laatste\par
     controle
     \cell Status
     \cell Controle PC
     \cell\\row\\trowd\\trgaph70\\trleft-108\\trbrdrl\brdrs\brdrw10 \\trbrdrt\brdrs\brdrw10 \\trbrdrr\brdrs\brdrw10 \\trbrdrb\brdrs\brdrw10 \\trpaddl70\\trpaddr70\\trpaddfl3\\trpaddfr3
     \clvmrg\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc1']."
     \clvmrg\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc2']."
     \clvmrg\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc3']."
     \clvmrg\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc4'];
     
     //toevoegen materiaal soorten
     $wc5x = $celwidth['wc4'] + 400;
     for ($x=1; $x <= $celwidth['matAmount']; $x++) {
         $header_sect3.="
     \clvmgf\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs\clvertalb 
     \cellx".$wc5x;
     $wc5x=$wc5x+400;			
     }
     
     $header_sect3.="
     \clvmrg\clvertalc\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc6']."
     \clvmrg\clvertalc\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc7']."
     \clvmrg\clvertalc\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc8']."
     \clvmgf\clvertalc\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc91']."
     \clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc92']."
     \clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx15550
     \pard\intbl\\nowidctlpar\\f0
     \cell
     \cell
     \cell
     ";
     
     //materiaalsoorten toevoegen
     for ($x=1; $x <= $celwidth['matAmount']; $x++) {
         if($x == 1) {
             $header_sect3.="\cell\pard\intbl\\nowidctlpar\\f1 {\horzvert0 ".$celwidth['m1']."}
     ";
         }else{
             $header_sect3.="\cell {\horzvert0 ".$celwidth['m'.$x]."}";
         }
     }
     
     $header_sect3.="
     \cell\pard\intbl\\nowidctlpar\qc\\f0\cell
     \cell
     \cell\\f1 Opmerking
     \cell Kleine rep.
     \cell Afgekeurd
     \cell\\row\\trowd\\trgaph70\\trleft-108\\trrh971\\trbrdrl\brdrs\brdrw10 \\trbrdrt\brdrs\brdrw10 \\trbrdrr\brdrs\brdrw10 \\trbrdrb\brdrs\brdrw10 \\trpaddl70\\trpaddr70\\trpaddfl3\\trpaddfr3
     \clvmrg\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc1']."
     \clvmrg\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc2']."
     \clvmrg\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc3']."
     \clvmrg\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc4'];
     
     //toevoegen materiaal soorten
     $wc5x = $celwidth['wc4'] + 400;
     for ($x=1; $x <= $celwidth['matAmount']; $x++) {
         $header_sect3.="
     \clvmrg\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$wc5x;
     $wc5x=$wc5x+400;			
     }
     
     $header_sect3.="
     \clvmrg\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc6']."
     \clvmrg\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc7']."
     \clvmrg\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc8']."
     \clvmrg\clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc91']."
     \clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx13750
     \clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx".$celwidth['wc92']."
     \clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx14950
     \clcbpat3\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs 
     \cellx15550
     \pard\intbl\\nowidctlpar\\f0
     \cell
     \cell
     \cell";
     
     
     for ($x=1; $x <= $celwidth['matAmount']; $x++) {
         $header_sect3.="\cell";		
     } 
     
     $header_sect3.="\cell
     \cell
     \cell
     \cell
     \cell\pard\intbl\\nowidctlpar\qc\\f1 Ja
     \cell Nee
     \cell Ja
     \cell Nee
     \cell
     \\row";
     
     return $header_sect3;
     }


    function rtf_celwidth($obsdata){
        //initialise
        $i=1;
        $celwidth=array();
    
        //count materialvariables
        foreach ($obsdata as $key => $value) {
            if($key==0){
              $keys=array_keys($value);
               foreach ($keys as $key1 => $value1) {
                    if (strpos($value1,"m_") !== false){	
                         //set materialtypes
                        ${'m'.$i}= substr($value1,2);
                        $celwidth["m".$i] = ${'m'.$i}; 
            
                        //count materialtypes
                         $i=$i+1;		
                    }      
               }
            }
        }
        $matAmount = $i-1;
    
        
        //tabellayout
        //breedte variatie in omschr en opmerking cel
        //A4=16838=297mm => 1mm = 56,69
        //marges Links en rechts = 2x600 |  beschikbaar: 15538 ca. 15550
    
       // kolombreedtes uitrekenen
        $wmat=$matAmount*400;
        
        $wc1=650;						//id					650 	650	- 1.4cm
        $wc2=$wc1+(7100-$wmat)/2;	//omschr				      3300
        $wc3=$wc2+1350;				//hoofd				1350  4750
        $wc4=$wc3+1350;				//zekering			1350  6100 
        $wc5=$wc4+$wmat;				//soort				      7300
                                              //t1			   400   
                                           //t2            400
        $wc6=$wc5+800;					//hoogte				800	8100
        $wc7=$wc6+1200;					//gecontroleerd	1200	9300
        $wc8=$wc7+700;					//status				700	10000
        $wc9=15550;						//controle PC		      -15550
          $wc91=$wc8+(7100-$wmat)/2;	//opmerking
          $wc92=$wc91+1200;				//kleine rep		1200
       $wc921=$wc91+600;				 //ja				600
       $wc922=$wc921+600;			 //nee			600
        $wc93=15550;					//afgekeurd  	1200
       $wc931=$wc922+600;		 	//ja				600
       $wc932=15550;					//nee			600
    // totaal         8450 => 15550-8450=7100
    //                3  soorten: 7100-1200=5900
    //                10 soorten: 7100-4000=3100
    
        $celwidth["matAmount"] = $matAmount;
        $celwidth["wc1"] = $wc1;
        $celwidth["wc2"] = $wc2;
        $celwidth["wc3"] = $wc3;
        $celwidth["wc4"] = $wc4;
        $celwidth["wc5"] = $wc5;
        $celwidth["wc6"] = $wc6;
        $celwidth["wc7"] = $wc7;
        $celwidth["wc8"] = $wc8;
        $celwidth["wc9"] = $wc9;
        $celwidth["wc91"] = $wc91;
        $celwidth["wc92"] = $wc92;
        $celwidth["wc921"] = $wc921;
        $celwidth["wc922"] = $wc922;
        $celwidth["wc93"] = $wc93;
        $celwidth["wc931"] = $wc931;
        $celwidth["wc932"] = $wc932;
    
        return $celwidth;
    
    }


    function rtf_body($obsdata){
        $i=0;
        $body="";
    
        //cel breedtes ophalen
       $celwidth = $this->rtf_celwidth($obsdata);
       
    
    
    while($i < count($obsdata)){
       //andere celkleur even/onevenrij
        if ($i % 2 == 0 ){
       //cel randen	
        $celborder="\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs ";
        }else{
       //cel randen	
        $celborder="\clcbpat2\clbrdrl\brdrw10\brdrs\clbrdrt\brdrw10\brdrs\clbrdrr\brdrw10\brdrs\clbrdrb\brdrw10\brdrs ";
        }
        
    $body.="\\trowd\\trgaph70\\trleft-108\\trrh404\\trbrdrl\brdrs\brdrw10 \\trbrdrt\brdrs\brdrw10 \\trbrdrr\brdrs\brdrw10 \\trbrdrb\brdrs\brdrw10 \\trpaddl70\\trpaddr70\\trpaddfl3\\trpaddfr3".$celborder."
    \cellx".$celwidth['wc1'].$celborder."
    \cellx".$celwidth['wc2'].$celborder."
    \cellx".$celwidth['wc3'].$celborder."
    \cellx".$celwidth['wc4'].$celborder;
    
    $wc5x = $celwidth['wc4'] + 400;
    for ($x=1; $x <= $celwidth['matAmount']; $x++) {
        $body.=" 
    \cellx".$wc5x.$celborder;
    $wc5x=$wc5x+400;			
    } 
    
    $body.="
    \cellx".$celwidth['wc6'].$celborder."
    \cellx".$celwidth['wc7'].$celborder."
    \cellx".$celwidth['wc8'].$celborder."
    \cellx13150".$celborder."
    \cellx13750".$celborder."
    \cellx14350".$celborder."
    \cellx14950".$celborder."
    \cellx15550\pard\intbl\\nowidctlpar\b0\\fs18 ".$obsdata[$i]['SHId']."
    \cell ".$obsdata[$i]['HOmschr']."
    \cell ".$obsdata[$i]['hoofdtouw']."
    \cell ".$obsdata[$i]['zekeringstouw'];
    
    
    for ($x=1; $x <= $celwidth['matAmount']; $x++) {
        $colname = "m_".$celwidth['m'.$x];
        $val = $obsdata[$i][$colname];
        if ($val == 1){
            $val = "*";
       } 
        $body.="\cell \qc".$val;			
    }
    
    if($obsdata[$i]['ChkSt']==1) {$ChkSt="V";}else {$ChkSt="!";}
    
    $body.=" 
    \cell \qc ".$obsdata[$i]['MaxH']."
    \cell ".$obsdata[$i]['DatCheck']."
    \cell \qc ".$ChkSt."
    \cell 
    \cell
    \cell
    \cell
    \cell
    \cell\\row";
        $i++;		
    }
    
    return $body;
    }

}

?>