<?php 
    include_once "header.php"; 
    include_once "leftColumn.php"; 
?>

<html>
<head>
</head>

<body id="sections">
    <div id="LeftColumn2">       
    </div>

    <div id="RightColumn">
        <form name="form1" method="post" action="<?php echo WEBROOT."/ObstaclesChecked/view";?>">
            <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Overzicht gecontroleerd</a>
            <div class="cudWidget"> 
                <button class="submitbtn" type="submit" name="report">
                    <img src="<?php echo WEBROOT; ?>/img/checklist.jpeg" width="35" height="35">
                </button>
            </div>
            <div id="widgetBar">
                    <br>
                    <a>Geef aan voor welk kwartaal en jaar de hindernissen gecontroleerd zijn.</a>
            </div>

            <table id="materialenTable2">
                <tr class="theader">
                    <th width="5%"><strong>Jaar</strong></th>
                    <th><strong>Kwartaal</strong></th>
                </tr>

                <tr>
                    <td>
                        <br><input type="text" name="jaar" maxlength="5" size="5">
                    </td>
                    <td width="5%"><br>
                        <select size="1" id="kwartaal" name="kwartaal">
                            <option value="">Kies kwartaal</option>
                            <option value="1">Kwartaal 1</option>
                            <option value="2">kwartaal 2</option>
                            <option value="3">Kwartaal 3</option>
                            <option value="4">kwartaal 4</option>
                        </select>
                    </td>
                </tr>
            </table>
        </form>
    </div>

</body>

</html>