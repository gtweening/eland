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
        </div>

        <div class="containertable">
            <div class="widgetBartab">
                <div class="basictab selected">
                 <a href="<?php echo WEBROOT.'/Obstacle/view/'.$obstacleid;?>">Hindernisdetails</a>
                </div>
            </div>
            <div class="widgetBartab">
                <div class="basictab">
                 <a href="<?php echo WEBROOT.'/ObstacleChecks/view/'.$obstacleid;?>">Hindernis controles</a>
                </div>
            </div>
        </div>

        <div class="workarea-row3x">        
            <table >  
                <tr valign="top">
                    <td class="hwhite" width ="50%">
                        <br><br>
                        <label>Sectie:</label> <?php echo $this->sectienaam; ?><br>
                        <label>Volgnummer:</label> <?php echo str_pad($this->volgnr,2,'0',STR_PAD_LEFT);?> <br>
                        <label>Gebouwd op:</label> <?php echo $this->Dat; ?><br>
                        <label>Hoogte:</label> <?php echo $this->maxH; ?>m<br>
                        <label>Veilig door:</label> <?php echo $veiligdoor; ?><br>
                        <label>Omschrijving:</label><br> <?php  echo $this->Omschr;?><br><br>
                        <lblgrey>Hindernis controleren in:</lblgrey><br>
                        <?php if($this->ChkQ1 == True){?> Kwartaal 1 <?php }?>
                        <?php if($this->ChkQ2 == True){?> Kwartaal 2 <?php }?>
                        <?php if($this->ChkQ3 == True){?> Kwartaal 3 <?php }?>
                        <?php if($this->ChkQ4 == True){?> Kwartaal 4 <?php }?>
                    </td>
                    <td class="hwhite">
                        <br><br>
                        <?php 
                            if($this->img != ''){
                                echo '<img src="'.WEBROOT.'/img/Obstacles/'.$this->img.'"'.$imgstyle.' > ';
                            }else{
                                $str = '<a id="main">geen hindernisafbeelding.</a><br><br>';
                                echo $str;
                            }
                        ?>
                        <br><br>
                    </td>
                    </tr>
                <tr>
                    <td class = "hwhite" colspan="2">
                        <form action="<?php echo WEBROOT; ?>/Obstacle/uploadfile" method="post" enctype="multipart/form-data">
                            <label for="file">Bestand:</label>
                            <input type="hidden" name="hindId" value="<?php echo $obstacleid;?>">
                            <input type="hidden" name="hindSec" value="<?php echo $this->sectienaam;?>">
                            <input type="hidden" name="hindVolgnr" value="<?php echo $this->volgnr;?>">
                            <input type="hidden" name="imgPath" value="<?php echo $this->obsPath;?>">
                            <input type="hidden" name="vimg" value="<?php echo $this->img;?>">
                            <input type="file" name="file" id="file" >
                            <div class="cudWidget">
                                <input  type="image" name="fileImport" src="<?php echo WEBROOT; ?>/img/save.jpeg" 
                                value="Opslaan"  width="45" height="45">
                                <input type="image" name="fileDelete" src="<?php echo WEBROOT; ?>/img/del.jpeg" 
                                value="Verwijderen"  width="45" height="45">
                            </div>
                        </form><br>
                    </td>
                </tr>
            </table>
        <div>

        <?php if(isset($_SESSION['errormessage'])){
                echo '<div class="errormessage">
                        <a>'.$warning.'</a>
                    </div>';
            }
            unset($_SESSION['errormessage']);
        ?>

        <div class="containertable">
            <div class="workarea-half">
                <form name="form1" method="post" action="<?php echo WEBROOT."/Obstacle/edit/materials/".$obstacleid;?>">
                    <div class="workarea-row">
                        <table >
                            <tr class="theader">
                                <th colspan="2"><strong>Hindernismaterialen</strong></th>
                                <th></th>
                                <th  align="center">
                                <button type="submit" name="editHindMaterials" >
                                    <img src="<?php echo WEBROOT; ?>/img/edit.jpeg" width="30" height="30">
                                </button>    
                                </th>
                            </tr>  
                            
                            <?php
                        
                            //hindernismaterialen tonen
                            while($rows = $ObstacleMaterials->fetch()){
                                $isrope = htmlentities($rows['IndSecureRope']);
                                $imrope = htmlentities($rows['IndMainRope']);
                                $srope="";
                                $mrope="";
                                if($isrope==1){$srope="Veiligheidstouw";}
                                if($imrope==1){$mrope="Hoofdtouw";}
                                if (!empty($rows['Supplier'])){
                                    $mattypeview = htmlentities($rows['tmtomschr']) . ' ('.htmlentities($rows['Supplier']) . ')';
                                }else{
                                    $mattypeview = htmlentities($rows['tmtomschr']);
                                }
                            ?>
                            
                            <tr>
                                <td class = "white"><?php echo $mattypeview; ?>
                                <span><?php echo $mattypeview; ?></span>
                                </td>
                                <td class = "white"><?php echo htmlentities(utf8_encode($rows['Omschr'])); ?>
                                <span><?php echo htmlentities(utf8_encode($rows['Omschr'])); ?></span>
                                </td>
                                <td class = "white"><?php echo htmlentities($rows['Aantal']); ?>
                                <span><?php echo htmlentities(utf8_encode($rows['Aantal'])); ?></span>
                                </td>
                                <td class = "white"><?php echo $srope.$mrope; ?>
                                <span><?php echo $srope.$mrope; ?></span>
                                </td>            
                                </tr>

                            <?php
                            }

                            ?>
                        </table>
                    </div>
                </form>
            </div>

            <div class="workarea-half">
                <form name="form1" method="post" action="<?php echo WEBROOT."/Obstacle/edit/checks/".$obstacleid;?>">
                    <div class="workarea-row">
                        <table >
                            <tr class="theader">
                                <th ><strong>Hinderniscontrolepunten</strong></th>
                                <th align="center">
                                <button type="submit" name="editHindChecks" >
                                    <img src="<?php echo WEBROOT; ?>/img/edit.jpeg" width="30" height="30">
                                </button>  
                                </th>

                            </tr>
                            
                            <?php
                            
                            //hindernismaterialen tonen
                            while($rows = $ObstacleCheckpoints->fetch()){
                            ?>
                            
                            <tr>
                                <td colspan="2" class = "white"><?php echo htmlentities($rows['Omschr']); ?>
                                <span><?php echo htmlentities($rows['Omschr']); ?></span>
                                </td>
                            </tr>

                            <?php
                            }
                            ?>
                        </table>

                        <?php
                        //close connection
                        $db = null;
                        ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
</body>
</html