<?php

/**
maintenance page for messages

copyright: 2018 Gerko Weening
*/

include_once "../inc/base.php";
include_once "../inc/functions.php";

sec_session_start(); 
include_once "../common/header.php"; 
include_once "../common/leftColumnBeheerder.php";

//$tbl_name="TblSections"; // Table name
//secure login
if(login_check($mysqli) == true) { 
?>
<html>
    <head>
       <link rel="stylesheet" type="text/css" href="../css/popup.css">
    </head>
    <body id="berichten">
        <div id="LeftColumn2">
            <a href="#preview">
               <button onclick="submit();">
                  <img src="../img/preview.png" width="40" height="40">
               </button>   
            </a>
        </div>
        
        <div id="RightColumn">
        <table display:block>
        <tr >
        <td>
        
        <form name="form1" method="post" action="frmHandling.php">
            <table id="materialenTable2" >
                <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Berichten</a>
                <div class="cudWidget">
                    <button class="submitbtn" type="submit" name="delBericht">
                        <img src="../img/del.jpeg" width="35" height="35">
                    </button>
                </div>
                <div class="cudWidget">
                    <button class="submitbtn" type="submit" name="pubBericht">
                        <img src="../img/publish.png" width="35" height="35">
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
                            <img src="../img/add.jpeg" width="35" height="35">
                        </button>
                    </div>
                </div>
                <tr class="theader">
                    <th width="5%" ></th>
                    <th width="10%"><strong>Datum</strong></th>
                    <th width="5%" ><strong>Gepubliseerd</strong></th>
                    <th width="80%"><strong>Titel/Bericht</strong></th>
                </tr>

                <?php
                $STH = $db->query('SELECT * from TblMessages order by Id');
                $STH->setFetchMode(PDO::FETCH_ASSOC);
                while($rows=$STH->fetch()){
                ?>

                <tr rowspan="2">
                    <td width="5%" class="white2">
	                <input name="checkbox[]" type="checkbox" id="checkbox[]" 
                               value="<?php echo $rows['Id']; ?>"></td>
                    <td width="10%" class="white2"><?php echo htmlentities($rows['Datum']); ?></td>
                    <td width="5%" class="white2" ><?php if (htmlentities($rows['Gepubliceerd'])=='1'){
                        echo "Ja";
                      }else{
                        echo "Nee";
                      } ?>
                    </td>
                    <td width="20%" class="white2"><?php echo htmlentities($rows['Titel']); ?></td>
                </tr>
                <tr>
                <td>
                    </td><td></td><td></td>
                    <td width="60%" class="white2"><?php echo htmlentities($rows['Bericht']); ?></td>
                </tr>

                <?php
                }
    
                //close connection
                $db = null;
                ?>

            </table>
        </form>
        </td>
        </tr>
        </table>
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
<?php
} else { ?>
<br>
U bent niet geautoriseerd voor toegang tot deze pagina. <a href="../index.php">Inloggen</a> alstublieft.
<?php
}
?>



