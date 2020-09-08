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
    <form name="form1" method="post"
            action="<?php echo "Sections/execute";?>"> 

        <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Hindernissecties</a>

        <div class="cudWidget">
            <button class="submitbtn" type="submit" name="delSection">
                <img src="<?php echo WEBROOT; ?>/img/del.jpeg" width="35" height="35">
            </button>
        </div>
        <div class="cudWidget">
            <button class="submitbtn" type="submit" name="editSection">
                <img src="<?php echo WEBROOT; ?>/img/edit.jpeg" width="35" height="35">
            </button>
        </div>
        <div id="widgetBar">
            <input type="text" class="inputText" name="sectienaam" maxlength="5" size="5">
            <input type="text" class="inputText" name="sectieomschr" maxlength="50" size="32">
            <div class="cudWidget">
                <button class="submitbtn" type="submit" name="addSection" float="right">
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
              <th class="dropdown" width="10%">
              	Naam
              	<div class="account-dropbox">
                      <a href="<?php echo WEBROOT; ?>/Sections">Standaard</a>
                      <a href="<?php echo WEBROOT; ?>/Sections/index/s=a">
                          <img src="<?php echo WEBROOT; ?>/img/sort_AZ.png" width="20" height="20">
                          Oplopend</a>
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
            
                <tr >
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
    </form>

    </div>
</body>
</html

