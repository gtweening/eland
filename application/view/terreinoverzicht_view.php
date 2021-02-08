<?php 
    include_once "header.php"; 
    include_once "leftColumn.php"; 
?>

<body id="terreinoverzicht">
  <div class="navobsbysection">          
  </div>

  <div class="workarea">
    <?php if(isset($_SESSION['errormessage'])){
                echo '<div class="errormessage">
                        <a>'.$warning.'</a>
                    </div>';
            }
            unset($_SESSION['errormessage']);
    ?>
    
    <form action="<?php echo "TerreinOverzicht/execute";?>" method="post" enctype="multipart/form-data">
      <div class="workarea-row">
        <label for="file">Bestand:</label>
        <input type="hidden" name="terreinId" value="<?php echo $terreinid;?>">
        <input type="hidden" name="imgPath" value="<?php echo $this->imgTerrainPath;?>">
        <input type="hidden" name="vimg" value="<?php echo $vimg;?>">
        <input type="file" name="file" id="file" >
        <div class="cudWidget">
          <input type="image" name="fileImport" src="<?php echo WEBROOT; ?>/img/save.jpeg" 
                  value="Opslaan" width="45" height="45">
          <input type="image" name="fileDelete" src="<?php echo WEBROOT; ?>/img/del.jpeg" 
                  value="Verwijderen" width="45" height="45">
        </div>
      </div>
    </form>
    <br>
    
    <div class="containertable">
      <div class="workarea-picture">
        <?php 
            if($vimg != ''){
              echo '<img src="'.WEBROOT.'/img/Terrain/'.$vimg.'"'.$imgstyle.' >';
          }else{
              $str = '<a id="main">geen terreinafbeelding.</a><br><br>';
              echo $str;
          }
        ?>
      </div>
    </div>

    <div class="workarea-row">>
      <?php include "footer.php"; ?>
    </div>

  </div>

  

</div>
</body>
</html>