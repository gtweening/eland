<?php 
    include_once "header.php"; 
    include_once "leftColumn.php"; 
?>

<html>
<head>
</head>
<body id="materials">
    <div id="LeftColumn2">          
    </div>

    <div id="RightColumn">
    <form name="form1" method="post"
            action="<?php echo "Materialdetails/execute";?>"> 

        <div id="widgetBartab">
            <ul class="basictab">
                <li ><a href="Materials">Materialen</a></li>
                <li class="selected"><a href="Materialdetails">Materiaaldetails</a></li>
            </ul>
        </div>

        <div class="cudWidget">
            <button class="submitbtn" type="submit" name="delMaterial">
                <img src="<?php echo WEBROOT; ?>/img/del.jpeg" width="35" height="35">
            </button>
        </div>
        <div class="cudWidget">
            <button class="submitbtn" type="submit" name="editMaterial">
                <img src="<?php echo WEBROOT; ?>/img/edit.jpeg" width="35" height="35">
            </button>
        </div>
        <div id="widgetBar3x">
              <label>Materiaal:</label> 
      		  <?php
    			$sSelect = "<select class='inputText' name='mattype' id='mattype' size=1>";
    			echo $sSelect;
    				while($rows = $materials->fetch()){
    				  echo "<option value=".$rows['Id'].">" .$rows['Omschr'] . "</option>";
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
        
        <?php if(isset($_SESSION['errormessage'])){
                echo '<div class="errormessage">
                        <a>'.$warning.'</a>
                    </div>';
            }
            unset($_SESSION['errormessage']);
        ?>

        <table id="materialenTable">
          <tr class="theader">
              <th width="5%" ></th>
              <th width="30%"><strong>Materiaal type</strong></th>
              <th width="45%"><strong>Omschrijving</strong></th>
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
          ?>
          <tr>
            <td width="5%" class="white2"><input name="checkbox[]" type="checkbox" 
                id="checkbox[]" value="<?php echo $rows['Id']; ?>"></td>
            <td class = "white"><?php echo htmlentities($rows['matType']); ?></td>
            <td class = "white"><?php echo htmlentities(utf8_encode($rows['Omschr'])); ?></td>
            <td class = "white"><?php echo $srope.$mrope; ?></td>
          </tr>
          <?php
          }
          ?>

    </form>
    </div>
</body>
</html>
