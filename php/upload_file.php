<?php
include_once "../inc/base.php";

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
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
  }
  else {
    echo "Upload: " . $_FILES["file"]["name"] . "<br>";
    echo "Type: " . $_FILES["file"]["type"] . "<br>";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
    if (file_exists("../img/Obstacles/" . $_FILES["file"]["name"])) {
      echo $_FILES["file"]["name"] . " bestaat al. ";
    }
    else {
      //move file to server
      move_uploaded_file($_FILES["file"]["tmp_name"],
            "../img/Obstacles/" . $_FILES["file"]["name"]);
      echo "Opgeslagen in: " . "img/Obstacles/" . $_FILES["file"]["name"];
      $FileName = $_FILES["file"]["name"];
      $STH = $db->prepare("UPDATE TblObstacles SET ImgPath = '".$FileName."' WHERE Id = $_POST[hindId]");
      $STH->execute();
      //echo "<meta http-equiv=\"refresh\" content=\"0;URL=obstacle.php?Id=".$_POST['hindId']."&Sec=".$_POST['hindSec']."&Vnr=".$_POST['hindVolgnr']."\">";
      //header('Location:obstacle.php?Id='.$_POST['hindId'].'&Sec='.$_POST['hindSec'].'&Vnr='.$_POST['hindVolgnr'].'');
      }
    }
  }
else {
  echo "Ongeldig bestand. "."<br>";
  echo "Type: " . $_FILES["file"]["type"] . " Bestand moet van het type: gif, jpeg, jpg of png zijn." . "<br>";
  echo "Grootte: " . ($_FILES["file"]["size"] / 1024) . " kB. Bestand moet kleiner zijn dan 500 Kb.<br>";
    
  }
?>
