<?php 
    include_once "header.php"; 
    include_once "leftColumn.php"; 
?>

<html>
    <head>
    </head>

    <body id="gebruikersterreinbeheer">
        <div id="LeftColumn2">
        </div>

        <div id="RightColumn">
            <form name="form1" method="post" action="<?php echo WEBROOT; ?>/Login/terreinselectexecute">
                <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Gebruiker terreinen</a>
                <div class="cudWidget">
                    <button class="submitbtn" type="submit" name="setGebrTerrein" float="right" >   
                            <img src="<?php echo WEBROOT; ?>/img/ok.jpeg" width="35" height="35">
                    </button>
                </div>
                
                <div id="widgetBar">
                    <br>
                    <a>U beheert meerdere terreinen. U kunt 1 terrein tegelijkertijd onderhouden. Kies terrein.</a>
                </div>

                <table id="materialenTable" >
                    <tr class="theader">
                        <th width="5%" ></th>
                        <th width="50%"><strong>Gebruikersnaam</strong></th>
                            <th ><strong>Terrein</strong></th>
                    </tr>

                    <?php
                    while($rows = $terreinusers->fetch()){
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
            </form>
        </div>
    </body>
</html>


