<?php 
    include_once "header.php"; 
    include_once "leftColumn.php"; 
?>

<body id="sections">
    <div class="navobsbysection">          
    </div>

    <div class="workarea">
        <form name="form1" method="post" action="<?php echo WEBROOT."/ObstacleCheckCalender/execute";?>"> 
            <div class="workarea-row">
                <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Agenda hinderniscontrole</a>
            </div>

            <div class="workarea-row">
                <div class="cudWidget"> 
                    <button class="submitbtn" type="submit" name="save">
                        <img src="<?php echo WEBROOT; ?>/img/save.jpeg" width="35" height="35">
                    </button>
                </div>
                <div id="widgetBar">
                    <br>
                    <a>Geef aan in welk(e) kwarta(a)l(en) de hindernis gecontroleerd wordt!</a>
                </div>
            </div>

            <div class="containertable">
                <table >
                    <tr class="theader">
                        <th width="10%"><strong>Hindernis</strong></th>
                        <th width="30%"><strong>Omschrijving</strong></th>
                        <th ><strong>Kwartaal 1</strong></th>
                        <th ><strong>Kwartaal 2</strong></th>
                        <th ><strong>Kwartaal 3</strong></th>
                        <th ><strong>Kwartaal 4</strong></th>
                    </tr>

                    <?php
                        while($rows = $calender->fetch()){
                    ?>
                        <tr>
                            <td width="10%" class = "white"><?php echo htmlentities($rows['naam']).'-'.htmlentities($rows['Volgnr']); ?>
                                <span class="white-text" style="margin-left: 1em;">
                                <img src="<?php echo WEBROOT.'/img/Obstacles/'.$rows['ImgPath'];?>" alt="" width="55" height="38" ></td>
                            <td width="30%" class = "white"><?php echo htmlentities($rows['Omschr']); ?></td>
                            <td width="15%" class="white"><input name="checkQ1[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['Id']; ?>" <?php echo $rows["ChkQ1"] ? 'checked="checked"' : ''; ?>></td>
                            <td width="15%" class="white"><input name="checkQ2[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['Id']; ?>" <?php echo $rows["ChkQ2"] ? 'checked="checked"' : ''; ?>></td>
                            <td width="15%" class="white"><input name="checkQ3[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['Id']; ?>" <?php echo $rows["ChkQ3"] ? 'checked="checked"' : ''; ?>></td>
                            <td width="15%" class="white"><input name="checkQ4[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['Id']; ?>" <?php echo $rows["ChkQ4"] ? 'checked="checked"' : ''; ?>></td>
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