<?php 
    include_once "header.php"; 
    include_once "leftColumn.php"; 
?>


<body id="gebruikersterreinbeheer">
    <div class="navobsbysection">          
    </div>

    <div class="workarea">
        <form name="form1" method="post" action="<?php echo WEBROOT; ?>/Login/terreinselectexecute">

            <div class="workarea-row">
                <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Gebruiker terreinen</a>
            </div>
            
            <div class="workarea-row">
                <div class="cudWidget">
                    <button class="submitbtn" type="submit" name="setGebrTerrein" float="right" >   
                            <img src="<?php echo WEBROOT; ?>/img/ok.jpeg" width="35" height="35">
                    </button>
                </div>
                
                <div id="widgetBar">
                    <br>
                    <a>U beheert meerdere terreinen. U kunt 1 terrein tegelijkertijd onderhouden. Kies terrein.</a>
                </div>
            </div>

            <div class="containertable">
                <table >
                    <tr class="theader">
                        <th width="5%" ></th>
                        <th width="50%"><strong>Gebruikersnaam</strong></th>
                            <th ><strong>Terrein</strong></th>
                    </tr>

                    <?php
                    while($rows = $terreinusers->fetch()){
                    ?>

                    <tr class="trow">
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

</div>
</body>
</html>


