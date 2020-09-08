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
        <div id="LeftColumn2" >
        </div>

        <div id="RightColumn">
            <form name="form1" method="post" action="<?php echo WEBROOT.'/Beheer/terreinbeheer'; ?>">
                <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Terreinen</a>
                <div class="cudWidget">
                    <button class="submitbtn" type="submit" name="delTerrein">
                        <img src="<?php echo WEBROOT;?>/img/del.jpeg" width="35" height="35">
                    </button>
                </div>
                <div class="cudWidget">
                    <button class="submitbtn" type="submit" name="editTerrein">
                        <img src="<?php echo WEBROOT;?>/img/edit.jpeg" width="35" height="35">
                    </button>
                </div>
                <div id="widgetBar">
                    <input type="text" class="inputText" name="Terreinnaam" value = "<?php echo $terreinnaam;?>" maxlength="50" size="18">
                    <div class="cudWidget">
                        <button class="submitbtn" type="submit" name="addTerrein" float="right" 
  				                onclick="formhash(this.form, this.form.password)">   
                            <img src="<?php echo WEBROOT;?>/img/add.jpeg" width="35" height="35">
                        </button>
                    </div>
                </div>
                
                <table id="materialenTable" >
                    <tr class="theader">
                        <th width="5%" ></th>
                        <th width="70%"><strong>Terreinnaam</strong></th>
                    </tr>
                
                    <?php
                    while($rows = $terreinen->fetch()){
                    ?>

                    <tr >
                        <td width="5%" class="white2">
                            <a href = "<?php echo WEBROOT."/Beheer/terreinen/".htmlentities($rows['Id']); ?>">
                            <input name="checkbox[]" type="checkbox" id="checkbox[]" 
                                   value="<?php echo $rows['Id']; ?>"
                                   <?php if(isset($terreinid)){
                    	                    if($rows['Id'] == $terreinid){echo "checked";}
                                        }
                                    ?> 
                            >
                        </td>
                        <td width="70%" class="white" ><?php echo htmlentities($rows['Terreinnaam']); ?></td>

                    </tr>
                    <?php
                    }
                    //close connection
                    $db = null;
                    ?>
                </table>
            </form>
        </div>
    </body>
</html>