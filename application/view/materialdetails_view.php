<?php 
    include_once "header.php"; 
    include_once "leftColumn.php"; 
?>

<script type="text/javascript">
    //populate dropdown based on materialtype
    function supplierChange(){
        var arrmattype = <?php echo json_encode($arrmattype); ?>;
        var arrmatsup  = <?php echo json_encode($arrmatsup); ?>;
        var supplier   = document.getElementById("supplier");
        var matid      = document.getElementById("mattype");
        var mat        = matid.options[matid.selectedIndex].text;
        document.getElementById("supplier").options.length = 0;

        for(const mattype of arrmattype){
            if(mat == mattype){
                document.getElementById("supplier").disabled = false;

                for(var index = 0; index < arrmatsup.length; index++){
                    if(arrmatsup[index]['MaterialType'] == mat){
                        var opt = arrmatsup[index]['Supplier'];
                        var el  = document.createElement("option");
                        el.textContent = opt;
                        el.value = arrmatsup[index]['Id'];
                        supplier.appendChild(el);
                    }
                }
                exit;
            }else{
                document.getElementById("supplier").disabled = true;
            }
        }

    }
</script>

<body id="materials">
    <div class="navobsbysection">          
    </div>

    <div class="workarea">
        <form name="form1" method="post"
                action="<?php echo "Materialdetails/execute";?>"> 

                <div class="containertable">
                <div class="widgetBartab">
                    <div class="basictab">
                        <a href="Materials">Materialen</a>
                    </div>
                </div>
                <div class="widgetBartab">
                    <div class="basictab selected">
                        <a href="Materialdetails">Materiaaldetails</a>
                    </div>
                </div>
            </div>


            <div class="workarea-row3x">
                <div class="cudWidget">
                    <input type="image" name="editMaterial" src="<?php echo WEBROOT; ?>/img/edit.jpeg" 
                           value="Bewerken" width="45" height="45">
                    <input type="image" name="delMaterial" src="<?php echo WEBROOT; ?>/img/del.jpeg" 
                           value="Bewerken" width="45" height="45">
                </div>
 
                <div id="widgetBar3x">
                    <label>Materiaal:</label> 
                    <select class='inputText' name='mattype' id='mattype' size=1 onchange="supplierChange()">
                    <?php
                        while($rows = $materials->fetch()){
                            echo "<option value=".$rows['Id'].">" .$rows['Omschr'] . "</option>";
                        }
                        echo "</select>"; 
                    ?>
                    <label>Leverancier:</label>
                    <select class='inputText' name='supplier' id='supplier' size=1 disabled>
                    <?php
                        while($rows = $matsup->fetch()){
                            echo "<option value=".$rows['Id'].">" .$rows['Supplier'] . "</option>";
                        }
                        echo "</select><br>"; 
                    ?>
                    
                    <lblgrey>Omschrijving: </lblgrey>
                    <input type="text" class="inputText" name="material" maxlength="32" size="32">
                    <br>
                    <lblgrey>Extra detail:   </lblgrey>
                    <input class="inputText" type="radio" name="rope" value="geen"> Geen        
                    <input class="inputText" type="radio" name="rope" value="securerope"> Veiligheidstouw
                    <input class="inputText" type="radio" name="rope" value="mainrope"> Hoofdtouw
                
                    <div class="cudWidget">
                        <button class="submitbtn" type="submit" name="addMaterial" >
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
                <table>
                    <tr class="theader">
                        <th width="5%" ></th>
                        <th width="20%"><strong>Materiaal type</strong></th>
                        <th width="55%"><strong>Omschrijving</strong></th>
                        <th ><strong>Optie</strong></th>
                    </tr>

                    <?php
                    //show materials
                    while($rows = $materialdetails->fetch()){
                        $isrope = htmlentities($rows['IndSecureRope']);
                        $imrope = htmlentities($rows['IndMainRope']);
                        $srope="";
                        $mrope="";
                        if($isrope==1){$srope="Veiligheidstouw";}
                        if($imrope==1){$srope="Hoofdtouw";}
                        if (!empty($rows['Supplier'])){
                            $mattypeview = htmlentities($rows['matType']) . ' ('.htmlentities($rows['Supplier']) . ')';
                        }else{
                            $mattypeview = htmlentities($rows['matType']);
                        }
                    ?>

                    <tr class="trow">
                        <td width="5%" class="white2"><input name="checkbox[]" type="checkbox" 
                            id="checkbox[]" value="<?php echo $rows['Id']; ?>"></td>
                        <td class = "white"><?php echo $mattypeview; ?></td>
                        <td class = "white"><?php echo htmlentities(utf8_encode($rows['Omschr'])); ?></td>
                        <td class = "white"><?php echo $srope.$mrope; ?></td>
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
