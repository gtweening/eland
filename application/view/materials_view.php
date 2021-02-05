<?php 
    include_once "header.php"; 
    include_once "leftColumn.php"; 
?>

<body id="materials">
    <div class="navobsbysection">          
    </div>

    <div class="workarea">
        <form name="form1" method="post"
                action="<?php echo "Materials/execute";?>"> 

            <div class="containertable">
                <div class="widgetBartab">
                    <div class="basictab selected">
                        <a href="Materials">Materialen</a>
                    </div>
                </div>
                <div class="widgetBartab">
                    <div class="basictab">
                        <a href="Materialdetails">Materiaaldetails</a>
                    </div>
                </div>
            </div>
           
            <div class="workarea-row">
                <div class="cudWidget">
                    <input type="image" name="editMaterialType" src="<?php echo WEBROOT; ?>/img/edit.jpeg" 
                           value="Bewerken" width="45" height="45">
                    <input type="image" name="delMaterialType" src="<?php echo WEBROOT; ?>/img/del.jpeg" 
                           value="Bewerken" width="45" height="45">  
                </div>

                <div id="widgetBar">
                    <input type="text" class="inputText" name="materialtype" maxlength="32" size="32">
                    <div class="cudWidget">
                        <button class="submitbtn" type="submit" name="addMaterialType" float="right">
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
                    <th >	<strong>Omschrijving</strong></th>
                    </tr>
                
                    <?php
                        while($rows=$materials->fetch()){
                    ?>

                    <tr class="trow">
                        <td width="5%" class="white2">
                            <input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['Id']; ?>"></td>
                        <td class = "white">
                            <?php echo htmlentities($rows['Omschr']); ?></td>
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