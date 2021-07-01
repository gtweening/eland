<?php 
    include_once "header.php"; 
    include_once "leftColumnBeheerder.php"; 
?>

<html>
    <head>
        <script type="text/JavaScript" src="<?php echo WEBROOT;?>/js/sha512.js"></script> 
        <script type="text/JavaScript" src="<?php echo WEBROOT;?>/js/forms.js"></script>
    </head>

    <body id="gebruikersbeheer">
        <div class="navobsbysection">          
        </div>

        <div class="workarea">
            <form name="form1" method="post" action="<?php echo WEBROOT.'/Beheer/materiaalLeveranciersBeheer'; ?>">

                <div class="workarea-row">
                    <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Materiaal leveranciers</a>
                </div>

                <div class="workarea-row">
                    <div class="cudWidget">
                    <button class="submitbtn" type="submit" name="editMatSup">
                            <img src="<?php echo WEBROOT;?>/img/edit.jpeg" width="35" height="35">
                        </button>
                        <button class="submitbtn" type="submit" name="delMatSup">
                            <img src="<?php echo WEBROOT;?>/img/del.jpeg" width="35" height="35">
                        </button>
                    </div>
                    <div id="widgetBar">
                        <select name="Material">
                            <?php
                            while($rows = $mat->fetch()){
                                echo "<option value='".htmlentities($rows['Omschr'])."'>".htmlentities($rows['Omschr']). "</option>";
                            }
                            ?>
                        </select>
                        <input type="text" class="inputText" name="Supplier"  maxlength="50" size="30">

                        <div class="cudWidget">
                            <button class="submitbtn" type="submit" name="addMatSup" float="right" >   
                                <img src="<?php echo WEBROOT;?>/img/add.jpeg" width="35" height="35">
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="containertable">
                    <table >
                        <tr class="theader">
                            <th width="5%" ></th>
                            <th width="45%"><strong>Materiaal</strong></th>
                            <th width="50%"><strong>Leverancier</strong></th>

                        </tr>
                    
                        <?php
                        while($rows = $lev->fetch()){
                        ?>

                        <tr >
                            <td width="5%" class="white2">
                                <input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['Id']; ?>">
                            </td>
                            <td width="45%" class="white" ><?php echo htmlentities($rows['MaterialType']); ?></td>
                            <td width="45%" class="white" ><?php echo htmlentities($rows['Supplier']); ?></td>
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
    </body>
</html>