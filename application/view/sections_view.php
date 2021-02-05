<?php 
    include_once "header.php"; 
    include_once "leftColumn.php"; 
?>


<body id="sections">
    <div class="navobsbysection">          
    </div>

    <div class="workarea">
        <form name="form1" method="post"
                action="<?php echo "Sections/execute";?>"> 

            <div class="workarea-row">
                <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Hindernissecties</a>
            </div>

            <div class="workarea-row">
                <div class="cudWidget">
                    <input type="image" name="editSection" src="<?php echo WEBROOT; ?>/img/edit.jpeg" 
                           value="Bewerken" width="45" height="45">
                    <input type="image" name="delSection" src="<?php echo WEBROOT; ?>/img/del.jpeg" 
                           value="Bewerken" width="45" height="45">
                </div>

                <div id="widgetBar">
                    <input type="text" class="inputText" name="sectienaam" maxlength="5" size="5">
                    <input type="text" class="inputText" name="sectieomschr" maxlength="50" size="32">
                    <div class="cudWidget">
                        <button class="submitbtn" type="submit" name="addSection" >
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
                        <th class="dropdown" width="10%">
                            Naam
                            <div class="table-dropbox">
                                <a href="<?php echo WEBROOT; ?>/Sections">Standaard</a>
                                <a href="<?php echo WEBROOT; ?>/Sections/index/s=a">
                                    <img src="<?php echo WEBROOT; ?>/img/sort_AZ.png" width="20" height="20">
                                    Oplopend
                                </a>
                                <a href="<?php echo WEBROOT; ?>/Sections/index/s=d">
                                <img src="<?php echo WEBROOT; ?>/img/sort_ZA.png" width="20" height="20">
                                Aflopend</a>
                            </div>
                        </th>
                        <th ><strong>Omschrijving</strong></th>
                    </tr>

                <?php
                    while($rows=$sections->fetch()){
                ?>
                        <tr class="trow" >
                            <td width="5%" class="white2"><input name="checkbox[]" type="checkbox" 
                                        id="checkbox[]" value="<?php echo $rows['Id']; ?>"></td>
                            <td width="5%" class="white" >
                                <a href = "<?php echo WEBROOT."/Sections/sn/".htmlentities($rows['Naam']); ?>">
                                <?php echo htmlentities($rows['Naam']); ?>
                            </td>
                            <td class = "white" >
                                <a href = "<?php echo WEBROOT."/Sections/sn/".htmlentities($rows['Naam']); ?>">
                                <?php echo htmlentities($rows['Omschr']); ?>
                            </td>
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
</html

