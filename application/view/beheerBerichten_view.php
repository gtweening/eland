<?php 
    include_once "header.php"; 
    include_once "leftColumnBeheerder.php"; 
?>

<html>
    <head>
    </head>

    <body id="berichten">
        <div id="LeftColumn2">
            <a href="#preview">
               <button onclick="submit();">
                  <img src="<?php echo WEBROOT;?>/img/preview.png" width="40" height="40">
               </button>   
            </a>
        </div>
        
        <div id="RightColumn">        
            <form name="form1" method="post" action="<?php echo WEBROOT;?>/Beheer/berichtenbeheer">
                <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Berichten</a>
                <div class="cudWidget">
                    <button class="submitbtn" type="submit" name="delBericht">
                        <img src="<?php echo WEBROOT;?>/img/del.jpeg" width="35" height="35">
                    </button>
                </div>
                <div class="cudWidget">
                    <button class="submitbtn" type="submit" name="pubBericht">
                        <img src="<?php echo WEBROOT;?>/img/publish.png" width="35" height="35">
                    </button>
                </div>

                <div id="widgetBar3x">
                    <input type="date" class="inputText2" name="datum" id="datum" maxlength="10" size="8"
                        value="<?php echo date('Y-m-d'); ?>">
                    <input type="text" class="inputText2" name="titel" id="titel" maxlength="50"
                        size="40" value="bericht titel"><br><br><br>
                    <textarea rows="3" cols="80" name="bericht" id="bericht">
                    </textarea>
                    <div class="cudWidget">
                        <button class="submit" type="submit" name="addBericht">   
                            <img src="<?php echo WEBROOT;?>/img/add.jpeg" width="35" height="35">
                        </button>
                    </div>
                </div>

                <table id="materialenTable2" >
                    <tr class="theader">
                        <th width="5%" ></th>
                        <th width="10%"><strong>Datum</strong></th>
                        <th width="5%" ><strong>Gepubliseerd</strong></th>
                        <th width="80%"><strong>Titel/Bericht</strong></th>
                    </tr>

                    <?php
                    while($rows = $berichten->fetch()){
                    ?>

                    <tr rowspan="2">
                        <td width="5%" class="white2">
                            <input name="checkbox[]" type="checkbox" id="checkbox[]" 
                                   value="<?php echo $rows['Id']; ?>"></td>
                        <td width="10%" class="white2"><?php echo htmlentities($rows['Datum']); ?></td>
                        <td width="5%" class="white2" >
                            <?php if (htmlentities($rows['Gepubliceerd'])=='1'){
                                    echo "Ja";
                                }else{
                                    echo "Nee";
                                }
                            ?>
                        </td>
                        <td width="20%" class="white2"><?php echo htmlentities($rows['Titel']); ?></td>
                    </tr>

                    <tr>
                        <td></td><td></td><td></td>
                        <td width="60%" class="white2"><?php echo htmlentities($rows['Bericht']); ?></td>
                    </tr>

                    <?php
                    }
        
                    //close connection
                    $db = null;
                    ?>

                </table>
            </form>
        </div>
        
        <!-- modal dialogue -->
    	<div id="preview" class="overlay">
    		<div class="popup">
    			<header class="container popup_header">
    			   <a class="close display-topright" href="">&times;</a>
    			   <h2>Nieuwsberichten</h2>
    			</header>
    			<div class="container">
    			   <a id="showdatum" style="float:right;">
    			   </a>
    			   <h3 id="showtitel"></h3>
    			   <p id="showbericht"></p>
    			</div>
    			<script>
    			    function submit(){
        			    var x = document.getElementById("datum").value;
        			    var y = document.getElementById("titel").value;
        			    var z = document.getElementById("bericht").value;
        				document.getElementById("showdatum").innerHTML = x;
        				document.getElementById("showtitel").innerHTML = y;
        				document.getElementById("showbericht").innerHTML = z;
        			}
    			</script>
    			<footer class="container popup_footer">
                  <p>.</p>
                </footer>
    		</div>
        </div>
        
    </body>
</html>




