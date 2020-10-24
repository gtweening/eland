<?php 
    include_once "header.php"; 
    include_once "leftColumn.php"; 
?>

<html>
<head>
</head>

<body id="terreinoverzicht">
  <div id="LeftColumn2">      
  </div>

  <div id="RightColumn">
    <?php if(isset($_SESSION['errormessage'])){
                echo '<div class="errormessage">
                        <a>'.$warning.'</a>
                    </div>';
            }
            unset($_SESSION['errormessage']);
    ?>
    <form action="<?php echo "TerreinOverzicht/execute";?>" method="post" enctype="multipart/form-data">
        <label for="file">Bestand:</label>
        <input type="hidden" name="terreinId" value="<?php echo $terreinid;?>">
        <input type="hidden" name="imgPath" value="<?php echo $this->imgTerrainPath;?>">
        <input type="hidden" name="vimg" value="<?php echo $vimg;?>">
        <input type="file" name="file" id="file" >
        <input class="cudWidget" type="image" name="fileDelete" src="<?php echo WEBROOT; ?>/img/del.jpeg" 
                value="Verwijderen" >
        <input class="cudWidget" type="image" name="fileImport" src="<?php echo WEBROOT; ?>/img/save.jpeg" 
                value="Opslaan" >
    </form><br>
    <?php 
        if($vimg != ''){
          echo '<img src="'.WEBROOT.'/img/Terrain/'.$vimg.'"'.$imgstyle.' >';
      }else{
          $str = '<a id="main">geen hindernisafbeelding.</a><br><br>';
          echo $str;
      }
    ?>
  </div>

  <div>
    <?php include "footer.php"; ?>
  </div>
</body>


</html>