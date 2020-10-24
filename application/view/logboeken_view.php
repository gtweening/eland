<?php 
    include_once "header.php"; 
   // include_once "leftColumn.php"; 
?>

<html>
    <head>
    </head>

    <body id="gebruikersterreinbeheer">
        <div id="LeftColumn2">
        </div>

        <div id="RightColumn">
                <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Beschikbare terreinen</a>
                
                <div id="widgetBar">
                    <br>
                    <a>Voor welk terrein wilt u het logboek bekijken. Kies terrein.</a>
                </div>

                <table id="materialenTable" >
                    <tr class="theader">
                        <th width="5%" ></th>
                        <th ><strong>Terrein</strong></th>
                    </tr>

                    <?php
                    while($rows = $terreinen->fetch()){
                        ?>
                        <tr >
                            <td width="5%" class="white2">
                            <td class="white">
                                <a href = "<?php echo WEBROOT."/ReportAll/view/".$rows['Id']; ?>">
                                <?php echo htmlentities($rows['Terreinnaam']); ?> 
                            </td>
                        </tr>
    
                        <?php
                    }

                    //close connection
                    $db = null;
                    ?>

                </table>
          
        </div>
    </body>
</html>


