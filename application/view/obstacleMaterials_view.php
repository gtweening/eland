<?php 
    include_once "header.php"; 
    include_once "leftColumn.php"; 
?>

<html>
<head>
</head>

<body id="sections">
    <div id="LeftColumn2">       
        <?php 
            include_once "obstaclepersectionOverview_view.php";  
        ?>
    </div>
    
    <div id="RightColumn">
        <a class="tableTitle2">Hindernis <?php echo $this->sectienaam,str_pad($this->volgnr,2,'0',STR_PAD_LEFT)?></a>   
                 
        <div class="cudWidget">
            <?php echo '<img src="'.WEBROOT.'/img/Obstacles/'.$this->img.'"'.$imgstyle.' >'; ?>
        </div>

        <div id="widgetBar">    
            <a class="tableTitle4">Onderhouden hindernismaterialen</a>          
        </div>

        <?php if(isset($_SESSION['errormessage'])){
                echo '<div class="errormessage">
                        <a>'.$warning.'</a>
                    </div>';
            }
            unset($_SESSION['errormessage']);
        ?>

        <form name="form1" method="post" action="<?php echo WEBROOT."/Obstacle/material/".$obstacleid;?>">
            <div id="RightColumnHalf">
            <table id="obstacleTableHalf">
                <tr class="theader">
                    <th width="5%" ></th>
                    <th colspan="2"><strong>Materialen</strong></th>
                    <th></th>
                    <th align="center">
                        <button type="submit" name="addMaterials" >
                            <img src="<?php echo WEBROOT; ?>/img/forward.jpeg" width="35" height="35">
                        </button>    
                    </th>
                </tr>
                <?php
                    //show materials
                    while($rows = $materials->fetch()){
                        $isrope = htmlentities($rows['IndSecureRope']);
                        $imrope = htmlentities($rows['IndMainRope']);
                        $srope="";
                        $mrope="";
                        if($isrope==1){$srope="Veiligheidstouw";}
                        if($imrope==1){$mrope="Hoofdtouw";}
                ?>

                <tr>
                    <td width="5%" class="white"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['Id']; ?>"></td>
                    <td colspan="2" class = "white"><?php echo htmlentities($rows['matType']); ?>
                        <span><?php echo htmlentities($rows['matType']); ?></span>
                    </td>
                    <td class = "white"><?php echo htmlentities(utf8_encode($rows['Omschr'])); ?>
                        <span><?php echo htmlentities($rows['Omschr']); ?></span>
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

            <div id="RightColumnHalf">
                <table id="obstacleTableHalf">
                    <tr class="theader">
                        <th width="5%" ></th>
                        <th colspan="2" width="35%"><strong>Materialen in deze hindernis</strong></th>
                        <th >
                            
                            <div class="cudWidget">
                                <button type="submit" name="delMaterials" >
                                    <img src="<?php echo WEBROOT; ?>/img/del.jpeg" width="35" height="35">
                                </button>
                                <div class="cudWidget dropdown">
                                    <a><img src="<?php echo WEBROOT; ?>/img/edit.jpeg" width="40" height="40"> </a>
                                    <div class="account-dropbox">
                                        <a>
                                        <label>Verander toelichting</label><br>
                                            <input type="text" name="toelichting" maxlength="15" size="15" value=""><br>
                                            <div style="background-color: #CEF6F5;">
                                                <button type="submit" name="editMaterialdescr" >
                                                    <img src="<?php echo WEBROOT; ?>/img/save.jpeg" width="35" height="35">
                                                </button>
                                            </div>
                                        </a>
                                    </div>
                                </div> 
                                
                            </div>
                        </th>
                       
                    </tr>
                </table>

                <?php
                //hindernismaterialen tonen
                while($rows = $ObstacleMaterials->fetch()){
                    $isrope = htmlentities($rows['IndSecureRope']);
                    $imrope = htmlentities($rows['IndMainRope']);
                    $srope="";
                    $mrope="";
                    if($isrope==1){$srope="Veiligheidstouw";}
                    if($imrope==1){$mrope="Hoofdtouw";}
                ?>
                <table id="obstacleTableHalf">
                    <tr>
                        <td width="5%" class="white"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['tomId']; ?>"></td>
                        <td width="25%" class = "white"><?php echo htmlentities($rows['tmtomschr']); ?>
                            <span><?php echo htmlentities($rows['tmtomschr']); ?></span>
                        </td>
                        <td width="25%"class = "white"><?php echo htmlentities(utf8_encode($rows['Omschr'])); ?>
                            <span><?php echo htmlentities(utf8_encode($rows['Omschr'])); ?></span>
                        </td>
                        <td width="25%"class = "white"><?php echo htmlentities($rows['Aantal']); ?>
                            <span><?php echo htmlentities(utf8_encode($rows['Aantal'])); ?></span>
                        </td>
                        <td width="15%" class = "white" ><?php echo $srope.$mrope; ?>
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

</body>
</html