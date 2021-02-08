<?php 
    include_once "header.php"; 
    include_once "leftColumn.php"; 
?>

<body id="sections">
    <div class="navobsbysection">          
    </div>

    <div class="workarea">
        <form name="form1" method="post" action="<?php echo WEBROOT.'/ObstaclesChecked/execute/'.$jaar.'/'.$kwartaal;?>"> 

            <div class="workarea-row">
                <a class="tableTitle2">&nbsp;&nbsp;Gecontroleerde hindernissen</a>
            </div>
            
            <div class="workarea-row">
                <div class="cudWidget">
                    <button class="submitbtn" type="submit" name="delObstacleChecked">
                        <img src="<?php echo WEBROOT; ?>/img/del.jpeg" width="35" height="35">
                    </button>
                </div>
                
                <div id="widgetBar">
                    <input type="date" class="inputText2" name="datum" maxlength="10" size="8">
                    <input type="checkbox" class="inputText2" name="cstatus" >
                    <input type="text" class="inputText2" name="controleur" maxlength="15" size="10" >
                    <input type="hidden" name="periode" value="<?php echo $periode;?>">
                    <input type="hidden" name="datend" value="<?php echo $datend;?>">
                    <textarea rows="2" cols="30" name="note" > </textarea>

                    <div class="cudWidget">
                        <button class="submitbtn" type="submit" name="refreshObstacleChecked" float="right">
                            <img src="<?php echo WEBROOT; ?>/img/refresh.jpeg" width="35" height="35">
                        </button>
                    </div>
                </div>
            </div>

            <?php if(isset($_SESSION['errormessage'])){
                    echo '<div class="errormessage">
                            <a>'.$warning.'</a>
                        </div>';
                }
                unset($_SESSION['errormessage']);
            ?>

            <div class="containertable">
                <table  >
                    <tr class="theader">
                        <th width="5%" ></th>
                        <th width="15%"><strong>Naam</strong></th>
                        <th width="30%"><strong>Omschrijving</strong></th>
                        <th width="10%"><strong>Datum</strong></th>
                        <th width="5%"><strong>Status</strong></th>
                        <th width="15%"><strong>Controleur</strong></th>
                        <th width="20%"><strong>Opmerking</strong></th>
                    </tr>


                    <?php
                    $obstacles = array_keys($obstaclesToCheckArray);
                    foreach($obstacles as $key){ 
                    ?>
                        <tr class="trow">
                            <td width="5%" class="white"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $key; ?>"></td>
                            <td width="15%" class = "white" ><?php echo htmlentities($obstaclesToCheckArray[$key]['Sectie']),htmlentities($obstaclesToCheckArray[$key]['Volgnr']); ?>
                                <span class="white-text" style="margin-left: 1em;">
                                <img src="<?php echo WEBROOT.'/img/Obstacles/'.$obstaclesToCheckArray[$key]['ImgPath'];?>" alt="" width="55" height="38" ></td>
                            <td  width="30%" class = "white" ><?php echo htmlentities($obstaclesToCheckArray[$key]['Omschr']); ?></td>

                            <?php
                            //selecteer controles van hindernis
                            if(isset($obstacleChecksArray[$key])){
                                $obstacleChecks = $obstacleChecksArray[$key];
                                foreach($obstacleChecks as $rows4){
                            ?>
                                
                                    
                                    <td class = "white"><?php echo htmlentities($rows4['datum']); ?></td>
                                    <td class = "white" >
                                            <?php if($rows4['status']== FALSE){?>
                                                    <img src="<?php echo WEBROOT; ?>/img/warning.jpeg" width="20" height="20"><?php
                                                }else{?>
                                                    <img src="<?php echo WEBROOT; ?>/img/ok.jpeg" width="20" height="20"><?php
                                                }; ?>
                                    </td>
                                    <td class = "white" ><?php echo htmlentities($rows4['controleur']); ?></td>
                                    <td class = "white" ><?php echo nl2br(htmlentities($rows4['notitie'])); ?></td>
                                <?php
                                }
                            }    
                            ?>
                        </tr>
                    <?php
                        }
                    ?>
                </table>
            </div>
        </form>
    </div>

</div>
</body>
</html>