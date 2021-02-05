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
                <div class="basictab">
                 <a href="<?php echo WEBROOT.'/Obstacle/view/'.$obstacleid;?>">Hindernisdetails</a>
                </div>
            </div>
            <div class="widgetBartab">
                <div class="basictab selected">
                 <a href="<?php echo WEBROOT.'/ObstacleChecks/view/'.$obstacleid;?>">Hindernis controles</a>
                </div>
            </div>
        </div>

        <form name="form1" method="post"
              action="<?php echo WEBROOT.'/ObstacleChecks/execute/'.$obstacleid;?>"> 

            <div class="workarea-row"> 
                <div class="cudWidget">
                    <input type="image" name="editObstacleCheck" src="<?php echo WEBROOT; ?>/img/edit.jpeg" 
                           value="Bewerken" width="45" height="45">
                    <input type="image" name="delObstacleCheck" src="<?php echo WEBROOT; ?>/img/del.jpeg" 
                           value="Bewerken" width="45" height="45">
                </div>

                <div id="widgetBar">
                    <input type="date" class="inputText2" name="datum" maxlength="10" size="8"
                            value="<?php echo date('Y-m-d'); ?>">
                    <input type="checkbox" class="inputText2" name="status" 
                        <?php if(isset($status)){
                                if($status==1){echo "checked";}
                            }
                        ?> 
                    >
                    <input type="text" class="inputText2" name="controleur" maxlength="15"
                            size="15" value="<?php echo $controleur;?>">
                    <textarea rows="2" cols="40" name="note" ><?php echo $note;?>
                    </textarea>

                    <div class="cudWidget">
                        <button class="submitbtn" type="submit" name="addObstacleCheck" float="right">
                            <img src="<?php echo WEBROOT; ?>/img/add.jpeg" width="35" height="35">
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
                <table >
                    <tr class="theader">
                        <th width="5%" ></th>
                        <th width="15%"><strong>Datum</strong></th>
                        <th width="10%"><strong>Status</strong></th>
                        <th width="15%"><strong>Controleur</strong></th>
                        <th ><strong>Notitie</strong></th>
                    </tr>

                    <?php
                        while($rows=$obstaclechecks->fetch()){
                    ?>
                        <tr class="trow">
                            <td width="5%" class="white">
                                <a href="<?php echo WEBROOT.'/ObstacleChecks/view/'.$obstacleid.'/'.$rows['Id'] ?>">
                                <input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['Id']; ?>"
                                    <?php if(isset($obstacleid)){
                                        if($rows['Id']==$obstaclecheckid){echo "checked";}
                                        }
                                ?> 
                                >
                            </td>
                            <td class = "white"><?php echo htmlentities($rows['DatCheck']); ?></td>
                            <td class = "white" >
                                    <?php if($rows['ChkSt']== FALSE){?>
                                            <img src="<?php echo WEBROOT; ?>/img/warning.jpeg" width="20" height="20"><?php
                                        }else{?>
                                            <img src="<?php echo WEBROOT; ?>/img/ok.jpeg" width="20" height="20"><?php
                                        }; ?>
                            </td>
                            <td class = "white" ><?php echo htmlentities($rows['CheckedBy']); ?></td>
                            <td class = "white" ><?php echo nl2br(htmlentities($rows['Note'])); ?></td>
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