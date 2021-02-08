<?php 
    include_once "header.php"; 
    include_once "leftColumnBeheerder.php"; 
?>

    <body id="gebruikersterreinbeheer">
        <div class="navobsbysection">          
        </div>

        <div class="workarea">
            <form name="form1" method="post" action="<?php echo WEBROOT;?>/Beheer/gebruikersterreinbeheer">

                <div class="workarea-row">
                    <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;GebruikersTerreinen</a>
                </div>
                
                <div class="workarea-row">
                    <div class="cudWidget">
                        <button class="submitbtn" type="submit" name="delGebrTerrein">
                            <img src="<?php echo WEBROOT;?>/img/del.jpeg" width="35" height="35">
                        </button>
                    </div>
                    
                    <div id="widgetBar">
                        <select name="User">
                            <?php
                            while($rows = $gebruikers->fetch()){
                                echo "<option value='".htmlentities($rows['Id'])."'>".htmlentities($rows['Email']). "</option>";
                            }
                            ?>
                        </select>

                        <select name="Terrein">
                            <?php
                            while($rows = $terreinen->fetch()){
                                echo "<option value='".htmlentities($rows['Id'])."'>".htmlentities($rows['Terreinnaam']). "</option>";
                            }
                            ?>
                        </select>

                        <div class="cudWidget">
                            <button class="submitbtn" type="submit" name="addGebrTerrein" float="right" 
                                    onclick="formhash(this.form, this.form.password)">   
                                <img src="<?php echo WEBROOT;?>/img/add.jpeg" width="35" height="35">
                            </button>
                        </div>
                    </div>
                </div>

                <div class="containertable">
                    <table  >
                        <tr class="theader">
                            <th width="5%" ></th>
                            <th width="50%"><strong>Gebruikersnaam</strong></th>
                            <th ><strong>Terrein</strong></th>
                        </tr>

                        <?php
                        while($rows = $terreingebruikers->fetch()){
                        ?>

                        <tr >
                            <td width="5%" class="white2">
                                <input name="checkbox[]" type="checkbox" id="checkbox[]" 
                                    value="<?php echo $rows['Id']; ?>"></td>
                            <td width="50%" class="white" ><?php echo htmlentities($rows['Email']); ?></td>
                            <td class="white"><?php echo htmlentities($rows['Terreinnaam']); ?> </td>
                        </tr>

                        <?php
                        }

                        //close connection
                        $db = null;
                        ?>

                    </table>
                </div>
            </form>
        </div>

</div>
</body>
</html>




