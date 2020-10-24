<?php 
    include_once "header.php"; 
    include_once "leftColumn.php"; 
?>

<html>
<head>
</head>

<body id="checkpoints">
    <div id="LeftColumn2">         
    </div>

    <div id="RightColumn">
    <form name="form1" method="post" 
          action="<?php echo "Checkpoints/execute";?>"> 
        
        <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Controle punten</a>

        <div class="cudWidget"> 
            <button class="submitbtn" type="submit" name="delChkPoint">
                <img src="<?php echo WEBROOT; ?>/img/del.jpeg" width="35" height="35">
            </button>
        </div>
        <div class="cudWidget">
            <button class="submitbtn" type="submit" name="editChkPoint">
                <img src="<?php echo WEBROOT; ?>/img/edit.jpeg" width="35" height="35">
            </button>
        </div>    
        <div id="widgetBar">
            <input type="text" class="inputText" name="CheckPoint" maxlength="75" size="50">
            <div class="cudWidget">
                <button class="submitbtn" type="submit" name="addChkPoint" float="right">
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

        <table id="materialenTable2" >
            <tr class="theader">
                <th width="5%" ></th>
                <th ><strong>Omschrijving</strong></th>
            </tr>

        <?php
        while($rows=$checkpoints->fetch()){
        ?>
            <tr>
                <td width="5%" class="white2"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['Id']; ?>"></td>
                <td class = "white"><?php echo htmlentities($rows['Omschr']); ?></td>
            </tr>
        <?php
        }
        ?>
        </table>
    </form>
    </div>
</body>
</html>