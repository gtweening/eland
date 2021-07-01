<?php

require_once 'tcpdf/tcpdf.php';

class RptControlelijstPC_PDF extends TCPDF {


    function setData($TL, $info)
    {
        $this->TL   = $TL;
        $this->info = $info;
    }

    function pdf_docinfo($TL)
    {
        // set document information
        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('SBN');
        $this->SetTitle('Controlelijst PC - '.$TL);
        $this->SetSubject('Controlelijst PC');
        $this->SetKeywords('TCPDF, SBN, controlelijst, '.$TL);
    }

    // Page header
    function Header()
    {   
        $celwidth = $this->pdf_celwidth($this->info);
        $ch       = $celwidth['ch']; 

        $this->SetFont('Helvetica','B',12);
        $this->Cell(10,20,'Controlelijst Parcours Commissie');
        $this->Image(WEBROOT."/img/LogoSBN.png",260,8,30);
        $this->SetX(110);
        $this->Cell(0,20,'Trainingslocatie: '.$this->TL);
        $this->Line(10,20,290,20);
        $this->SetY(20);
        if($this->PageNo() == 1){
            $this->pdf_basisdata();
            $this->SetY(48);
            $this->pdf_tableheader($this->info);
        }else{
            $this->pdf_tableheader($this->info);
            $this->SetMargins(10,32, 10, true);
        }
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Helvetica','I',8);

        $this->Cell(0,10,'Gemaakt op: '.date('d-m-Y H:i:s'),0,0,'L');
        // Page number
        $this->Cell(0,10,'Pagina '.$this->PageNo().'/'.$this->getAliasNbPages(),0,0,'R');
        $this->Line(10,195,290,195);
    }


    function pdf_basisdata()
    {
        $data = array(
            array("Plaats:","","Controleurs terrein:",""),
            array("Trainingstijden:","","Controleurs SBN:",""),
            array("","","Datum controle:",""),
            array("BHV / EHBO aanwezig tijdens trainingen:","","Algemeen oordeel:",""),
        );

        // Colors, line width and bold font
        $this->SetTextColor(255);
        $this->SetDrawColor(0,0,0);
        $this->SetLineWidth(.1);
        $this->SetFont('Helvetica','',12);
        $this->SetY(20);
        $w = array(80, 50, 40, 110);
    
        // Color and font restoration
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
       
        // Data
        $fill = false;
        foreach($data as $row)
        {
            $this->Cell($w[0],6,$row[0],'L',0,'L',$fill);
            $this->Cell($w[1],6,$row[1],'R',0,'L',$fill);
            $this->Cell($w[2],6,$row[2],'L',0,'L',$fill);
            $this->Cell($w[3],6,$row[3],'R',0,'L',$fill);
            $this->Ln();
            $fill = !$fill;
        }
        // Closing line
        $this->Cell(array_sum($w),0,'','T');

        // Formfields
        $this->SetXY(43,20);
        $this->TextField('plaats', 97, 6,array('lineWidth'=>1, 'borderStyle'=>'solid'));
        $this->SetXY(43,26);
        $this->TextField('trainingstijden', 97, 12,array('multiline'=>true,'lineWidth'=>1, 'borderStyle'=>'solid'));
        $this->SetXY(91,38);
        $this->TextField('ehbo', 49, 6,array('lineWidth'=>1, 'borderStyle'=>'solid'));
        $this->SetXY(180,20);
        $this->TextField('ctlterrein', 110, 6,array('lineWidth'=>1, 'borderStyle'=>'solid'));
        $this->SetXY(180,26);
        $this->TextField('ctlsbn', 110, 6,array('lineWidth'=>1, 'borderStyle'=>'solid'));
        $this->SetXY(180,32);
        $this->TextField('datum', 110, 6,array('lineWidth'=>1, 'borderStyle'=>'solid'));
        $this->SetXY(180,38);
        $this->TextField('algoordeel', 110, 6,array('lineWidth'=>1, 'borderStyle'=>'solid'));
        $this->Ln(6);
    }

    function pdf_tableheader($info)
    {
        //get celwidth
        $celwidth   = $this->pdf_celwidth($info);
        $w1         = $celwidth['w1'];
        $w2         = $celwidth['w2'];
        $w3         = $celwidth['w3'];
        $ch         = $celwidth['ch'];
        $cM         = $celwidth['cMat'];
        $w2H        = $celwidth['w2H'];
        $fill       = true;

        //instellen minimun mat breedte
        switch ($cM){
            case 1:
                $wM = 24;
                break;
           
            case 2:
                $wM = 12;
                break;

           default:
            $wM = 8;
       }

        //set lining and filling
        $this->SetFillColor(102,178,255);
        $this->SetTextColor(0,0,0);
        $this->SetDrawColor(0,0,0);
        $this->SetLineWidth(.1);
        $this->SetFont('Helvetica','',9);        

        //1e regel
        $this->Cell($w1[0],6,"",'L',0,'L',$fill);
        $this->Cell($w1[1],6,"",'L',0,'L',$fill);
        $this->Cell($w1[2],6,"",'L',0,'L',$fill);
        $this->Cell($w1[3],6,"",'L',0,'L',$fill);
        $this->Cell($w1[4],6,"Leverancier",'L',0,'C',$fill);
        $this->Cell($w1[5],6,"",'L',0,'L',$fill);
        $this->Cell($w1[6],6,"",'L',0,'L',$fill);
        $this->Cell($w1[7],6,"",'L',0,'L',$fill);
        $this->Cell($w1[8],6,"Controle PC",'LB',0,'C',$fill);
        $this->Ln(6);

        //2e regel
        $this->Cell($w2[0],6,"Id",'L',0,'L',$fill);
        $this->Cell($w2[1],6,"Omschrijving",'L',0,'C',$fill);
        $this->Cell($w2[2],6,"Hoofdtouw",'L',0,'C',$fill);
        $this->Cell($w2[3],6,"Zekeringstouw",'L',0,'C',$fill);
        $this->Cell($w2[4],6,"koppelstuk",'L',0,'C',$fill);
        //variabel aantal materialen
        //for ($x=1; $x <= $cM; $x++) {
        //    $c = $x+3;
        //    $this->Cell($w3[$c],$wM,"",'LT',0,'C',$fill);
        //}
        $this->Cell($w2[5],6,"H [m]",'L',0,'C',$fill);
        $this->Cell($w2[6],6,"Datum",'L',0,'C',$fill);
        $this->Cell($w2[7],6,"Status",'L',0,'C',$fill);
        $this->Cell($w2[8],6,"opmerking",'L',0,'C',$fill);
        $this->Cell($w2[9],6,"afgekeurd",'LB',0,'L',$fill);
        $this->Ln(6);

        //3e regel
        //$this->Cell($w3[0],6,"",'LB',0,'L',$fill);
        //$this->Cell($w3[1],6,"",'LB',0,'L',$fill);
        //$this->Cell($w3[2],6,"",'LB',0,'L',$fill);
        //$this->Cell($w3[3],6,"",'LB',0,'L',$fill);
        //$this->Cell($w3[4],6,"",'LB',0,'L',$fill);
        //materialen
        //$this->StartTransform();
        //$this->Rotate(90);
        //$this->TranslateX(-6);
        //for ($x=1; $x <= $cM; $x++) {
        //    $c = $x+3;
        //    $this->Cell($ch,$w3[$c],$celwidth['m'.$x],'LT',0,'L',$fill);
        //    $this->TranslateY(8);
        //    $this->TranslateX(-$ch);
        //}
        //$this->StopTransform();
        //reposition
        //$this->SetX($w2H);
        //$this->Cell($w3[5],6,"[m]",'LB',0,'C',$fill);
        //$this->Cell($w3[6],6,"",'LB',0,'L',$fill);
        //$this->Cell($w3[7],6,"",'LB',0,'L',$fill);
        //$this->Cell($w3[8],6,"",'LB',0,'L',$fill);
        //$this->Cell($w3[$c+5],6,"Ja",'LB',0,'C',$fill);
        //$this->Cell($w3[$c+6],6,"Nee",'LB',0,'C',$fill);
        //$this->Cell($w3[$c+6],6,"Ja",'LB',0,'C',$fill);
        //$this->Cell($w3[9],6,"Ja",'LB',0,'C',$fill);
        //$this->Ln(6);

    }


    function pdf_body($info)
    {
        $i=0;

        //get celwidth
        $celwidth   = $this->pdf_celwidth($info);
        $w1         = $celwidth['w1'];
        $w2         = $celwidth['w2'];
        $w3         = $celwidth['w3'];
        $ch         = $celwidth['ch'];
        $cM         = $celwidth['cMat'];
        $w2Opm      = $celwidth['w2Opm'];
        $SHId_old   = '';
        //set filling
        $fill       = false;
        if($this->PageNo() === 1){
            $this->SetY(60);
        }

        //set lining and filling
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0,0,0);
        $this->SetDrawColor(0,0,0);
        $this->SetLineWidth(.1);
        $this->SetFont('Helvetica','',9);
       
        //fill obstacle data
        while($i < count($info)){
            $SHId = $info[$i]['SHId'];
            if($SHId_old <> $SHId){
                //status symbool instellen
                if($info[$i]['ChkSt']==1) {$ChkSt="OK";}else {$ChkSt="!";}

                $this->Cell($w3[0],6,$info[$i]['SHId'],'L',0,'L',$fill);
                $this->Cell($w3[1],6,$info[$i]['HOmschr'],'L',0,'L',$fill);
                $this->Cell($w3[2],6,$info[$i]['hoofdtouw'],'L',0,'L',$fill);
                $this->Cell($w3[3],6,$info[$i]['zekeringstouw'],'L',0,'L',$fill);
                $this->Cell($w3[4],6,$info[$i]['Supplier'],'L',0,'L',$fill);

                //for ($x=1; $x <= $cM; $x++) {
                //    $colname = "m_".$celwidth['m'.$x];
                //    $val     = $info[$i][$colname];
                //    if ($val == 1){
                //        $val = "*";
                //    } 
                //   $c = $x+3;
                //    $this->Cell($w3[$c],6,$val,'L',0,'C',$fill);
                //}

                $this->Cell($w3[5],6,$info[$i]['MaxH'],'L',0,'C',$fill);
                $this->Cell($w3[6],6,$info[$i]['DatCheck'],'L',0,'C',$fill);
                $this->Cell($w3[7],6,$ChkSt,'L',0,'C',$fill);
                $this->Cell($w3[8],6,"",'L',0,'C',$fill);
                $this->SetX($w2Opm);
                $this->TextField('opm'.$i, $w3[8], 6,array('lineWidth'=>1, 'borderStyle'=>'solid' ));
                $this->Cell($w3[9],6,"",'L',0,'L',$fill);
                $this->SetX($w2Opm + $w3[8]);
                $this->TextField('JA1'.$i, $w3[9], 6,array('lineWidth'=>1, 'borderStyle'=>'solid' ));
                //$this->Cell($w3[9],6,"",'L',0,'L',$fill);
                //$this->SetX($w2Opm + $w3[$c+4] + $w3[$c+5]);
                //$this->TextField('NEE1'.$i, $w3[$c+6], 6,array('lineWidth'=>1, 'borderStyle'=>'solid' ));
                //$this->Cell($w3[$c+6],6,"",'L',0,'L',$fill);
                //$this->SetX($w2Opm + $w3[$c+4] + $w3[$c+5] );
                //$this->TextField('JA2'.$i, $w3[$c+6], 6,array('lineWidth'=>1, 'borderStyle'=>'solid' ));
                //$this->Cell($w3[$c+7],6,"",'L',0,'L',$fill);
                //$this->SetX($w2Opm + $w3[$c+4] + $w3[$c+5] + $w3[$c+6] );
                //$this->TextField('NEE2'.$i, $w3[$c+7], 6,array('lineWidth'=>1, 'borderStyle'=>'solid' ));

                $this->Ln(6);
            }else{
                $this->Cell($w3[0],6,'','L',0,'L',$fill);
                $this->Cell($w3[1],6,'','L',0,'L',$fill);
                $this->Cell($w3[2],6,'','L',0,'L',$fill);
                $this->Cell($w3[3],6,'','L',0,'L',$fill);
                $this->Cell($w3[4],6,$info[$i]['Supplier'],'L',0,'L',$fill);
                $this->Cell($w3[5],6,'','L',0,'C',$fill);
                $this->Cell($w3[6],6,'','L',0,'C',$fill);
                $this->Cell($w3[7],6,'','L',0,'C',$fill);
                $this->Cell($w3[8],6,"",'L',0,'C',$fill);
                $this->SetX($w2Opm);
                $this->TextField('opm'.$i, $w3[8], 6,array('lineWidth'=>1, 'borderStyle'=>'solid' ));
                $this->Cell($w3[9],6,"",'L',0,'L',$fill);
                $this->SetX($w2Opm + $w3[8]);
                $this->TextField('JA1'.$i, $w3[9], 6,array('lineWidth'=>1, 'borderStyle'=>'solid' ));
                $this->Ln(6);
            }
            $SHId_old = $info[$i]['SHId'];

            //alternate fill
            $fill = !$fill;
            $i++;
        }
        

    }

    function pdf_celwidth($info)
    {
        //initialise
        $i      = 1;
        $lenmat = 1;
        $result = array();

        //count materialvariables
        foreach ($info as $key => $value) {
            if($key==0){
                $keys=array_keys($value);
                foreach ($keys as $key1 => $value1) {
                    if (strpos($value1,"m_") !== false){	 
                        //set materialtypes
                        ${'m'.$i} = substr($value1,2);
                        $result["m".$i] = ${'m'.$i};    
                        //lengte tekst
                        $lenmat = max(strlen($result["m".$i]),$lenmat);
                        //count materialtypes
                        $i=$i+1;		
                    }      
                }
            }
        }
        $matAmount = $i-1;

        //totaal beschikbaar breedte: 280
        $wcor      = 0;
        $wmat      = 8;
        $wmattot   = $wmat*$matAmount;
        $wmattot   = max($wmat*$matAmount,24);
        //if($matAmount > 3){
        //    $wcor = ($matAmount-3) * 4;
        //}else{
        //    $wcor = 0;
        //}
        $womschr   = 53 - $wcor;
        $wcpc      = 98 - $wcor;
        $wopm      = 82 - $wcor;
        $w1        = array(17, $womschr, 24, 24, 24, 9, 19, 12, $wcpc);
        $w2        = array(17, $womschr, 24, 24, 24, 9, 19, 12, $wopm, 16);
        $w3        = array(17, $womschr, 24, 24, 24, 9, 19, 12, $wopm, 16);

            /** 
            $w2[0] = 15;
            $w2[1] = $womschr;
            $w2[2] = 24;
            $w2[3] = 24;
            for($x = 0; $x < $matAmount; $x++) {
               $c      = $x + 4;
               switch ($matAmount){
                    case 1:
                        $w2[$c] = 24;
                        break;
                   
                    case 2:
                        $w2[$c] = 12;
                        break;

                   default:
                    $w2[$c] = $wmat;
               }
                
            }
            $w2[$c+1] = 9;
            $w2[$c+2] = 19;
            $w2[$c+3] = 12;
            $w2[$c+4] = $wopm;
            $w2[$c+5] = 16;
            $w2[$c+6] = 16;
            */

            //repositioning column H
            $w2H      = 15+$womschr+24+24+max(($matAmount*8)+10,34);
            //repositioning column opmerking
            //$w2Opm    = 15+$womschr+24+24+max(($matAmount*8)+10,34)+9+19+12;
            $w2Opm    = 17+$womschr+24+24+34+9+19+12;
         /**   
        $w3        = array();
            $w3[0] = 15;
            $w3[1] = $womschr;
            $w3[2] = 24;
            $w3[3] = 24;
            for($x = 0; $x < $matAmount; $x++) {
               $c      = $x + 4;
               switch ($matAmount){
                    case 1:
                        $w3[$c] = 24;
                        break;
                   
                    case 2:
                        $w3[$c] = 12;
                        break;

                   default:
                    $w3[$c] = $wmat;
               }
                
            }
            $w3[$c+1] = 9;
            $w3[$c+2] = 19;
            $w3[$c+3] = 12;
            $w3[$c+4] = $wopm;
            $w3[$c+5] = 16;
            $w3[$c+6] = 8;
            $w3[$c+7] = 8;
            //$w3[$c+8] = 8;
            */
        //$w2        = array(15, 53, 24, 24, $wmat,$wmat,$wmat,9,19,12,68,16,16);
        //$w3        = array(15, 53, 24, 24, $wmat,$wmat,$wmat,9,19,12,68,8,8,8,8);
        //Lorem ipsum dolor sit amet kwap
        //$tekst     = "Lorem ipsum dolor";
        //$lenmat    = strlen($tekst);
        $coef      = max($lenmat-8,1);
        $factor    = max($lenmat/$coef, 1.6); 
        $ch        = 6 + ($coef * $factor);
        
        $result["w1"] = $w1;
        $result["w2"] = $w2;
        $result["w3"] = $w3;
        $result["ch"] = $ch;
        $result["cMat"]  = $matAmount;
        $result["w2H"]   = $w2H;
        $result["w2Opm"] = $w2Opm;

        return $result;
    }

}

   
?>
