<?php 
    include_once "header.php"; 
    include_once "leftColumn.php"; 
?>

<body id="sections">
    <div class="navobsbysection">       
        <?php 
        include_once "obstaclepersectionOverview_view.php";  
        ?>       
    </div>

    <div class="workarea">
        <div class="workarea-row"> 
            <a class="tableTitle2">Hindernis <?php echo $this->sectienaam,str_pad($this->volgnr,2,'0',STR_PAD_LEFT)?></a>

            <div class="cudWidget"> 
                <?php echo '<img src="'.WEBROOT.'/img/Obstacles/'.$this->img.'"'.$imgstyle.' >'; ?>
            </div>
        </div>
       
        <div class="workarea-row"> 
            <div id="widgetBar"> 
                <a class="tableTitle4">Onderhouden hinderniscontrolepunten</a>
            </div>
        </div>
        
        <form name="form1" method="post" action="<?php echo WEBROOT."/Obstacle/checkpoints/".$obstacleid;?>">
            <div class="containertable">    
                <div class="workarea-half">
                    <table >
                        <tr class="theader">
                            <th width="5%" ></th>
                            <th ><strong>Controlepunten</strong></th>
                            <th align="center">
                                <button type="submit" name="addCheckpoints" >
                                    <img src="<?php echo WEBROOT; ?>/img/forward.jpeg" width="40" height="40">
                                </button>    
                            </th>
                        </tr>

                        <?php
                        while($rows = $checkpoints->fetch()){
                        ?>
                        <tr>
                            <td width="5%" class="white"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['Id']; ?>"></td>
                            <td colspan="2" class = "white"><?php echo htmlentities($rows['Omschr']); ?>
                            <span><?php echo htmlentities($rows['Omschr']); ?></span>
                            </td>
                        </tr>

                        <?php
                        }
                        ?>
                    </table>
                </div>
           
                <div class="workarea-half">
                    <table>
                        <tr class="theader">
                            <th width="5%" ></th>
                            <th colspan="2"><strong>Controlepunten in deze hindernis</strong></th>
                            <th width="40%" align="center">
                                <button type="submit" name="delCheckpoints" >
                                    <img src="<?php echo WEBROOT; ?>/img/del.jpeg" width="40" height="40">
                                </button> 

                            </th>
                        </tr>
                        <?php
                        //hindernismaterialen tonen
                        while($rows = $ObstacleCheckpoints->fetch()){
                        ?>
                        <tr>
                            <td width="5%" class="white"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['tocId']; ?>"></td>
                            <td colspan ="3" class = "white"><?php echo htmlentities($rows['Omschr']); ?>
                            <span><?php echo htmlentities($rows['Omschr']); ?></span>
                            </td>
                        </tr>

                        <?php
                        }
                        ?>
                    </table>
                </div>
            </div>
        </form>
    </div>

</div>
</body>
</html>