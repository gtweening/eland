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

    
    //upload obstacle image to server
    function uploadFile($file,$imgPath, $hindId=NULL, $terreinId=NULL, $db){
        
        $message     = "";
        $allowedExts = array("jpeg", "jpg", "png", "JPEG", "JPG", "PNG", "Jpeg", "Jpg", "Png");
        $temp        = explode(".", $file["file"]["name"]);
        $extension   = end($temp);

        //tabel instellen
        if(isset($_POST['hindId'])){
            //herkomst = Obstacle
            $tbl   = 'TblObstacles';
            $col   = 'ImgPath';
            $where = "WHERE Id = $hindId";

        }else{
            //herkomst = terreinOverzicht
            $tbl   = 'TblTerrein';
            $col   = 'ImgFile';
            $where = "WHERE Id = $terreinId";
        }

        if ((($file["file"]["type"] == "image/gif")
            || ($file["file"]["type"] == "image/jpeg")
            || ($file["file"]["type"] == "image/jpg")
            || ($file["file"]["type"] == "image/pjpeg")
            || ($file["file"]["type"] == "image/x-png")
            || ($file["file"]["type"] == "image/png"))
           // && ($file["file"]["size"] < 9000000)
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
                    move_uploaded_file($file["file"]["tmp_name"], $imgPath . $file["file"]["name"]);

                    //change image scale
                    $image      = $file["file"]["name"];
                    $type       = $file["file"]["type"];
                    list($w,$h) = getimagesize($imgPath . $image);
                    $ratio      = $w/$h; //>1: widht>height, <1: width < height
                    switch (TRUE){
                        case ($ratio <=1):
                            $h = 500;
                            $w = 500 * $ratio;
                            break;
                        case ($ratio > 1):
                            $w = 500;
                            $h = 500 / $ratio;
                            break; 
                    }

                    //create image and save to server - existing file is overwritten
                    switch ($type){
                        case ("image/jpeg"):
                        case ("image/jpg"):
                        case ("image/pjpeg"):
                            $tempimage    = imagecreatefromjpeg($imgPath.$image);
                            $imageResized = imagescale($tempimage, $w, $h);
                            $saved        = imagejpeg($imageResized,$imgPath.$image);
                            break;
                        case ("image/x-png"):
                        case ("image/png"):
                            $tempimage    = imagecreatefrompng($imgPath.$image);
                            $imageResized = imagescale($tempimage, $w, $h);
                            $saved        = imagepng($imageResized,$imgPath.$image);
                            break;
                    }

                  $message .= "Opgeslagen op server.";
                  $_SESSION['errormessage'] = $message;

                  $FileName = $file["file"]["name"];
                  $sql = "UPDATE $tbl SET $col = '".$FileName."' $where";
                  $STH = $db->prepare($sql);
                  $STH->execute();
                }
            }
        } else {

            $message = "Ongeldig bestand. "."<br>";
            $message .= "Bestand moet van het type: jpeg, jpg of png zijn, niet:  " ;
            $message .= $file["file"]["type"];
            //$message .= "en kleiner dan 9 MB (" . $file["file"]["size"] / 1024 ." KB).";
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