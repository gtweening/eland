<?php 
    include_once "header.php"; 
    include_once "leftColumn.php"; 
?>

<body id="sections">
    <div class="navobsbysection">          
    </div>

    <div class="workarea">
        <form name="form1" method="post" 
				action="<?php echo WEBROOT."/Sections/executeObstacleSection";?>">
            <div class="workarea-row">
                <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Hindernissen sectie: <?php echo $this->vsectionname;?></a>
            </div>

            <div class="workarea-row3x">
                <div class="cudWidget"> 
                    <input type="hidden" name="sectionName" value="<?php echo $this->vsectionname;?>">
                    <input type="image" name="editObstacle" src="<?php echo WEBROOT; ?>/img/edit.jpeg" 
                           value="Bewerken" width="45" height="45">
                    <a href="#Delpopup">
                        <img src="<?php echo WEBROOT; ?>/img/del.jpeg" width="45" height="45">
                    </a> 
                </div>

                <div id="widgetBar3x">
                    <input type="hidden" name="sectionId" value="<?php echo $this->vsectionid;?>">
                    <label>Volgnr.</label>
                    <input type="text" class="inputText" name="volgnr" 
                            maxlength="5" size="5" value="<?php echo $inputvolgnr;?>">
                    <lblgrey>Gebouwd op</lblgrey>
                    <input type="date"  name="datcreate" 
                            maxlength="10" size="7" value="<?php echo $inputdate;?>">
                    <lblgrey>Hoogte</lblgrey> 
                    <input type="text" name="maxh" 
                            maxlength="3" size="3" value="<?php echo $inputh;?>">
                    <br>
                    <label>Omschrijving</label>
                    <input type="text" class="inputText" name="hindernisOmschr" 
                            maxlength="32" size="38" value="<?php echo $inputomschr;?>">
                    <br>
                    <div class="cudWidget">
                        <button type="submit" name="addObstacle" float="right">
                            <img src="<?php echo WEBROOT; ?>/img/add.jpeg" width="35" height="35">
                        </button>
                    </div>
                    <br>    
                    <lblgrey>Veiligheid gewaarborgd door:</lblgrey>
                    <select name="obsSec">
                        <?php
                        foreach($optObsSec as $key => $value):
                            if ($key==$inputobssec){
                                echo '<option value="'.$key.'" selected>'.$value.'</option>';
                            }else {
                                echo '<option value="'.$key.'">'.$value.'</option>';
                            }
                        endforeach;
                        ?>
                    </select>
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
                        <th width="10%"><strong>Volgnr</strong></th>
                        <th width="15%"><strong>Datum</strong></th>
                        <th width="5%"><strong>H</strong></th>
                        <th ><strong>Omschrijving</strong></th>
                    </tr>

                    <?php
                    while($rows = $obstacles->fetch()){
                    ?>

                    <tr class="trow">
                        <td width="5%" class="white2">
                            <a href="<?php echo WEBROOT.'/Sections/sn/'.$this->vsectionname.'/'.$rows['Id'] ?>">
                            <input name="checkbox[]" type="checkbox" id="checkbox[]" 
                                value="<?php echo $rows['Id']; ?>" 
                                <?php if(isset($this->obstacleid)){
                                        if($rows['Id']==$this->obstacleid){echo "checked";}
                                        }
                                ?> 
                            >
                        </td>
                        <td class = "white2"><?php echo str_pad(htmlentities($rows['Volgnr']),2,'0',STR_PAD_LEFT); ?></td>
                        <td class = "white2"><?php echo str_pad(htmlentities($rows['DatCreate']),2,'0',STR_PAD_LEFT); ?></td>
                        <td class = "white2"><?php echo str_pad(htmlentities($rows['MaxH']),2,'0',STR_PAD_LEFT); ?></td>
                        <td class = "white" >
                            <a href="<?php echo WEBROOT."/Obstacle/view/".$rows['Id'];?>">
                            <?php echo htmlentities($rows['Omschr']); ?>
                        </td>
                    </tr>

                    <?php
                    }
                    ?>
                </table>
            </div>

            <!-- modal dialogue -->
            <div id="Delpopup" class="overlay">
                <div class="popup">
                    <header class="popupcontainer popup_header">
                    <a class="close" href="">&times;</a>
                    <h2>Hindernis verwijderen</h2>
                    </header>

                    <div class="popupcontainer popup_body">
                    <p> <?php echo 'Weet u zeker dat u de geselcteerde hindernis(sen) wilt verwijderen?'; ?></p>
                    </div>

                    <footer class="popupcontainer popup_footer">
                    <form name="form1" method="post" 
                            action="<?php echo WEBROOT."/Sections/executeObstacleSection";?>">

                        <button class="btnRead" type="submit" name="delObstacle" >
                        Ja, verwijderen
                        </button>
                        </form>
                    </footer>
                </div>
            </div>

        </form>
    </div>



</div>
</body>
</html>

