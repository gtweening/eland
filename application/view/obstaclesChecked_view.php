<?php 
    include_once "header.php"; 
    include_once "leftColumn.php"; 
?>

<body id="sections">
    <div class="navobsbysection">          
    </div>

    <div class="workarea">
        <form name="form1" method="post" action="<?php echo WEBROOT."/ObstaclesChecked/view";?>">

            <div class="workarea-row">
                <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Overzicht gecontroleerd</a>
            </div>

            <div class="workarea-row">
                <div class="cudWidget"> 
                    <button class="submitbtn" type="submit" name="report">
                        <img src="<?php echo WEBROOT; ?>/img/checklist.jpeg" width="35" height="35">
                    </button>
                </div>
                <div id="widgetBar">
                        <br>
                        <a>Geef aan voor welk kwartaal en jaar de hindernissen gecontroleerd zijn.</a>
                </div>
            </div>

            <div id="widgetBar2x">
                <table>
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
            </div>
        </form>
    </div>

</div>
</body>
</html>