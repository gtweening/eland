<?php

class mod_helpers{


    function urldecoder($url){
        $urllen =  strlen($url);
        $encoded = $url;
        $decoded = base64_decode($encoded);

        $urlparts = array();

        //decoded string klopt met hash
        $strlen = strlen($decoded);
        $substr_count = substr_count($decoded,'/');

        $strpos = 0;
        $offset = 0;
        $counter = 0;
        //add url part to array
        while($offset <= $strlen && $counter < $substr_count+1) {
            //postion of seperator
            $strposnew = strpos($decoded,'/',$offset);

            //calculate url part length
            $urlpartlen = $strposnew-$strpos-$counter;
            if ($counter == $substr_count) {$urlpartlen = $strlen-$strposnew-1;}

            //add to array
            array_push($urlparts,substr($decoded,$offset,$urlpartlen));

            //re-init
            $strpos = $strposnew;
            $offset = $strpos+1;
            $counter++;
        }

        return $urlparts;
    } 

    function hashedurldecoder($url){
        $urllen =  strlen($url);
        $hash = substr($url,$urllen-32,32);
        $encoded = substr($url,0,$urllen-32);
        $decoded = base64_decode($encoded);
        $md5hash = md5($decoded);

        $urlparts = array();

        if($hash==$md5hash){
            //decoded string klopt met hash
            $strlen = strlen($decoded);
            $substr_count = substr_count($decoded,'/');

            $strpos = 0;
            $offset = 0;
            $counter = 0;
            //add url part to array
            while($offset <= $strlen && $counter < $substr_count+1) {
                //postion of seperator
                $strposnew = strpos($decoded,'/',$offset);

                //calculate url part length
                $urlpartlen = $strposnew-$strpos-$counter;
                if ($counter == $substr_count) {$urlpartlen = $strlen-$strposnew-1;}

                //add to array
                array_push($urlparts,substr($decoded,$offset,$urlpartlen));

                //re-init
                $strpos = $strposnew;
                $offset = $strpos+1;
                $counter++;
            }

            return $urlparts;
            
        } else {
            return false;
        }

    }

    /**
     * check uniqueness of name/omschr in table
     * Ids - selected Id
     * terreinid - Id of terrein
     * t - table name
     * c - column name
     * db - db credentials 
     */
    function checkUnique($Id, $Terreinid, $t, $c, $value, $db){
        //name needs to be unique, selected name not included
        $sql1 = 'SELECT '.$c.' FROM '.$t.' WHERE Id = '.$Id.' ';
        $STH1=$db->prepare($sql1);
        $STH1->execute();
        $result = $STH1->fetch(PDO::FETCH_ASSOC);
        $sNaamSel=$result[$c];

        //select other names
        $STH2 = $db->query('SELECT distinct '.$c.' FROM '.$t.'
                            WHERE Terrein_id = '.$Terreinid.' 
                              AND '.$c.' <>"'.$sNaamSel.'" ');
        $STH2->setFetchMode(PDO::FETCH_ASSOC);

        //loop through results
        while($rows = $STH2->fetch()){
            if($value == $rows[$c]){
                //names are equal => nok
                $result = 0;
                break;

            }else{
                // names are not equal => ok
                $result = 1;
            }
        }

        if ($result == 1){
            return TRUE;
        }else{
            return FALSE;
        }

    }

    
    //function to show picture in required size and ratio
    //parameters:
    //path: path to image
    //filename: filename of image
    //width: max width
    //height: max height
    //it is assumed that width and height have the expected ratio
    function showObsPic($path,$img,$maxb,$maxh){
        if (file_exists($path.$img)){
            //if there is an image, check ratio
            if($img!=''){
                //standaard ratio = 300*200 (maxb*maxh)
                $defaultratio=$maxb/$maxh;
                list($w,$h) = getimagesize($path.$img);
                $ratio = $w/$h;
                $str="";
                switch (TRUE){
                    case ($ratio<$defaultratio):
                        //breedte is kleiner dan 300/200 => max h = 200
                        $width=$maxh/$h*$w;
                        $str=' width="'.$width.'" height="'.$maxh.'" ';
                        break;
                    case ($ratio>$defaultratio):
                        //breedte is groter dan 300/200 => max b = 300
                        $height=$maxb/$w*$h;
                        $str=" width='".$maxb."' heigth='".$height."'";
                        break;
                }
                //result
                return $str;
            }
        }
    }

    function imgImport($db, $imgPath, $vimg, $hindId=NULL, $terreinId=NULL){
        $result = "";
        
        //tabel instellen
        if(isset($_POST['hindId'])){
            //herkomst = Obstacle
            $tbl = 'TblObstacles';
            $col = 'ImgPath';
            $where = "WHERE Id = $hindId";

        }else{
            //herkomst = terreinOverzicht
            $tbl = 'TblTerrein';
            $col = 'ImgFile';
            $where = "WHERE Id = $terreinId";
        }
    
        //toegestane extensies
        $allowedExts = array("gif", "jpeg", "jpg", "png","GIF", "JPEG", "JPG", "PNG","Gif", "Jpeg", "Jpg", "Png");
        $temp = explode(".", $_FILES["file"]["name"]);
        $extension = end($temp);
        if ((($_FILES["file"]["type"] == "image/gif")
            || ($_FILES["file"]["type"] == "image/jpeg")
            || ($_FILES["file"]["type"] == "image/jpg")
            || ($_FILES["file"]["type"] == "image/pjpeg")
            || ($_FILES["file"]["type"] == "image/x-png")
            || ($_FILES["file"]["type"] == "image/png"))
            && ($_FILES["file"]["size"] < 500000)
            && in_array($extension, $allowedExts))
        {
          if ($_FILES["file"]["error"] > 0) {
            $result = "Return Code: " . $_FILES["file"]["error"] . "<br>";
          }
          else {
            $result .= "Upload: " . $_FILES["file"]["name"] . "\\n";
            $result .= "Type: " . $_FILES["file"]["type"] . "\\n";
            $result .= "Size: " . ($_FILES["file"]["size"] / 1024) . " kB\\n";
            $result .= "Temp file: " . $_FILES["file"]["tmp_name"] . "\\n";
    
            if (file_exists($imgPath . $_FILES["file"]["name"])) {
                $_SESSION['errormessage'] = $_FILES["file"]["name"] . " bestaat al op de server. Raadpleeg de beheerder voor correctie!";
                return false;

            }
            else {
              //move file to server
              move_uploaded_file($_FILES["file"]["tmp_name"],
                                $imgPath . $_FILES["file"]["name"]);
              //echo "Opgeslagen in: " . $imgPath . $_FILES["file"]["name"];
              //$result .= "\\n" . $_FILES["file"]["name"] . " is opgeslagen op de server.";
              $FileName = $_FILES["file"]["name"];
              $sql = "UPDATE $tbl SET $col = '".$FileName."' $where";
              $STH = $db->prepare($sql);
              $STH->execute();
              }
            }
        }
        else {
            $message = "Ongeldig bestand. <br>";
            $message .= "Type: " . $_FILES["file"]["type"] . ". Bestand moet van het type: gif, jpeg, jpg of png zijn. ";
            $message .= "Grootte: " . ($_FILES["file"]["size"] / 1024) . " kB. Bestand moet kleiner zijn dan 500 Kb.\\n";
            $_SESSION['errormessage'] = $message;

            return false;
            
        }
    
        return TRUE;
    }

    //bestandsnaam uit db verwijderen
    //enkel als er een bestand aanwezig is.
    function imgDelete($db, $hindId=NULL, $vimg=NULL, $terreinId=NULL, $imgPath){
        
        //tabel instellen
        if(isset($hindId)){
            //herkomst = Obstacle
            $tbl = 'TblObstacles';
            $col = 'ImgPath';
            $where = "WHERE Id = $hindId";
        }else{
            //herkomst = terreinOverzicht
            $tbl = 'TblTerrein';
            $col = 'ImgFile';
            $where = "WHERE Id = $terreinId";
        }

        if(!empty($vimg)){
            $sqlUpdate = "Update $tbl Set $col = '' $where";
            $STH1=$db->prepare($sqlUpdate);
            $STH1->execute();
        }else{
            //geen bestand geselecteerd
            return FALSE;
        }
        //bestand verwijderen van server
        $fileName = $imgPath.$vimg;
        unlink($fileName);

        return TRUE;
    }

    function uploadFile($file,$imgPath, $tablename, $id, $db){
        $allowedExts = array("gif", "jpeg", "jpg", "png","GIF", "JPEG", "JPG", "PNG","Gif", "Jpeg", "Jpg", "Png");
        $temp = explode(".", $file["file"]["name"]);
        $extension = end($temp);

        if ((($file["file"]["type"] == "image/gif")
            || ($file["file"]["type"] == "image/jpeg")
            || ($file["file"]["type"] == "image/jpg")
            || ($file["file"]["type"] == "image/pjpeg")
            || ($file["file"]["type"] == "image/x-png")
            || ($file["file"]["type"] == "image/png"))
            && ($file["file"]["size"] < 500000)
            && in_array($extension, $allowedExts))
        {
            if ($file["file"]["error"] > 0) {
                $message =  "Return Code: " . $file["file"]["error"] . "<br>";
                $_SESSION['errormessage'] = $message;
                return false;
            } else {
                $message = "Upload: " . $file["file"]["name"] . "<br>";
               // $message .= "Type: " . $file["file"]["type"] . "<br>";
                //$message .= "Size: " . ($file["file"]["size"] / 1024) . " kB<br>";
                //$message .= "Temp file: " . $file["file"]["tmp_name"] . "<br>";
                if (file_exists($imgPath . $file["file"]["name"])) {
                    $message = $file["file"]["name"] . " bestaat al. ";
                    $_SESSION['errormessage'] = $message;
                    return false;
                }
                else {
                  //move file to server
                  move_uploaded_file($file["file"]["tmp_name"],
                        $imgPath . $file["file"]["name"]);
                  $message .= "Opgeslagen op server.";
                  $_SESSION['errormessage'] = $message;

                  $FileName = $file["file"]["name"];

                  $STH = $db->prepare("UPDATE $tablename SET ImgPath = '".$FileName."' WHERE Id = $id");
                  $STH->execute();
                  //echo "<meta http-equiv=\"refresh\" content=\"0;URL=obstacle.php?Id=".$_POST['hindId']."&Sec=".$_POST['hindSec']."&Vnr=".$_POST['hindVolgnr']."\">";
                  //header('Location:obstacle.php?Id='.$_POST['hindId'].'&Sec='.$_POST['hindSec'].'&Vnr='.$_POST['hindVolgnr'].'');
                }
            }
        } else {

            $message = "Ongeldig bestand. "."<br>";
            $message .= "Bestand moet van het type: gif, jpeg, jpg of png zijn " ;
            $message .= "en kleiner dan 500Kb (" . $file["file"]["size"] / 1024 .").";
            $_SESSION['errormessage'] = $message;
            return false;
        }

        
    }


    //bepaal op basis van opgegeven jaar en kwartaal
    //de controleperiode die beschouwd moet worden
    function getcheckperiod($jr, $kwartaal) {
        if(isset($jr)){
            $jaar = substr($jr, -2);
            $i=$kwartaal;
            switch ($i) {
                case 1:
                    $datbegin = $jaar."-01-01";
                    $datend = date("Y-m-d", strtotime($datbegin));
                    $ChkQ = 'tob.ChkQ1';
                    break;
                case 2:
                    $datend = date("Y-m-d", strtotime($jaar."-04-01"));
                    $ChkQ = 'tob.ChkQ2';
                    break;
                case 3:
                    $datend = date("Y-m-d", strtotime($jaar."-07-01"));
                    $ChkQ = 'tob.ChkQ3';
                    break;
                case 4:
                    $datend = date("Y-m-d", strtotime($jaar."-10-01"));
                    $ChkQ = 'tob.ChkQ4';
                    break;
            }
        }
        return array($ChkQ, $datend);
    }


}