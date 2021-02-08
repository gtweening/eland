<?php 
    include_once "header.php"; 
    include_once "leftColumn.php"; 
?>

<body id="checkpoints">
    <div class="navobsbysection">          
    </div>

    <div class="workarea">
    <form name="form1" method="post" 
          action="<?php echo "Checkpoints/execute";?>"> 
        
        <div class="workarea-row">
            <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Controle punten</a>
        </div>

        <div class="workarea-row">
            <div class="cudWidget">
                <input type="image" name="editChkPoint" src="<?php echo WEBROOT; ?>/img/edit.jpeg" 
                        value="Bewerken" width="45" height="45">
                <input type="image" name="delChkPoint" src="<?php echo WEBROOT; ?>/img/del.jpeg" 
                        value="Bewerken" width="45" height="45">
            </div>
 
            <div id="widgetBar">
                <input type="text" class="inputText" name="CheckPoint" maxlength="75" size="50">
                <div class="cudWidget">
                    <button class="submitbtn" type="submit" name="addChkPoint" float="right">
                        <img src="<?php echo WEBROOT; ?>/img/add.jpeg" width="35" height="35">
                    </button>
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
                        <th ><strong>Omschrijving</strong></th>
                    </tr>

                    <?php
                    while($rows=$checkpoints->fetch()){
                    ?>
                        <tr class="trow">
                            <td width="5%" class="white2"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['Id']; ?>"></td>
                            <td class = "white"><?php echo htmlentities($rows['Omschr']); ?></td>
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
