<?php

/**
The collection of obstacles are devided into section.
Using this page you can maintain Terrain overview picture.
required: _updateElandTabellen7

copyright: 2013-2019 Gerko Weening



*/

include_once "../inc/base.php";
include_once "../inc/functions.php";
include_once "../inc/queries.php";

sec_session_start(); 
include_once "../common/header.php"; 

//secure login
if(login_check($mysqli) == true) { 

include_once "../common/leftColumn.php";
$Terreinid = $_SESSION['Terreinid']; //sessions terreinid
$tbl_name="TblTerrein"; // Table name

//terreinafbeelding ophalen
$STH = $db->query('SELECT * from '.$tbl_name.' WHERE Id ="'.$Terreinid.'"');
$STH->setFetchMode(PDO::FETCH_ASSOC);
$row=$STH->fetch();
$vimg=$row['ImgFile'];
$imgPath = $imgTerrainPath;

//resultaat tonen na klikken import/delete
function showResult($result){
  echo '<script language="javascript">';
  echo 'alert("'.$result.'")';
  echo '</script>'; 

  echo "<meta http-equiv=\"refresh\" content=\"0;URL=terreinOverzicht.php\">";
}
?>

<html>
<head>
</head>

<body id="terreinoverzicht">
  <div id="LeftColumn2">      
  </div>

  <div id="RightColumn">
  <table display:block>
  <tr >
  <td>
    <table id="obstacleTable" >
        <tr>
            <td class = "hwhite" colspan="2">
                <form action="" method="post" enctype="multipart/form-data">
                <label for="file">Bestand:</label>
                <input type="hidden" name="terreinId" value="<?php echo $Terreinid;?>">
                <input type="hidden" name="imgPath" value="<?php echo $imgPath;?>">
                <input type="hidden" name="vimg" value="<?php echo $vimg;?>">
                <input type="file" name="file" id="file" >
                <input class="cudWidget" type="image" name="fileDelete" src="../img/del.jpeg" 
                        value="Verwijderen" >
                <input class="cudWidget" type="image" name="fileImport" src="../img/save.jpeg" 
                        value="Opslaan" >

                <?php
                   if(isset($_POST['fileImport_x'])){
                      //controle op aanwezigheid bestand
                      $sql = "select imgFile from TblTerrein where Id = $Terreinid";
                      $stmt3 = $mysqli->prepare($sql);
						          $stmt3->execute();
						          $stmt3->store_result();
						          $stmt3->bind_result($img);
                      $stmt3->fetch();
                      if(strlen($img) <> 0) {
                        $result = "Er is al een overzichtsafbeelding voor dit terrein. \\n";
                        $result .="Verwijder eerst het bestaande bestand voordat u een nieuwe opslaat!";
                        showResult($result);

                      }else{
                        //geen bestaand bestand gevonden. toevoegen.
                        $result = imgImport($db, $STH);
                        showResult($result);
                      }
                      
                   }elseif(isset($_POST['fileDelete_x'])){
                      $result = imgDelete($db, $STH);
                      showResult($result);
                      $vimg='';
                   }
                ?>
                </form><br>
            </td>
        </tr>
        <tr>
            <td class="hwhite">
                <br>
                <?php showObsPic($imgPath,$vimg,650,650); ?>
                <br>
            </td>
        </tr>
        
    </table>
  </td>
  </tr>
  </table>
  </div>

  <div>
    <?php include "../common/footer.php"; ?>
  </div>

</body>


</html>
<?php
} else { ?>
<br>
U bent niet geautoriseerd voor toegang tot deze pagina. <a href="../index.php">Inloggen</a> alstublieft.
<?php
}
?>


