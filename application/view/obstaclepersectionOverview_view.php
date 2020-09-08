<html>
<!--/**
shows overview of obstacles of selected section

copyright: 2013 Gerko Weening
20171011
solved issue with php short code
solved issue showing obstacles of section

*/-->

    <a class="tableTitle3">Sectie <?php echo $this->sectienaam?></a>
    <div class="cudWidget">   
    </div>
        
    <table id="obstacleTableQuarter">
         <tr class="theader">
            <th colspan="2">Hindernis</th>
         </tr>
        <?php
        while($rows = $obstacles->fetch()){
        ?>
            <tr>
                <td width="15%" class = "white2"><?php echo str_pad(htmlentities($rows['Volgnr']),2,'0',STR_PAD_LEFT); ?></td>
                <td class = "white" >
                    <a href="<?php echo WEBROOT.'/Obstacle/view/'.$rows['Id']; ?>">
                    <?php echo htmlentities($rows['Omschr']); ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
    
</html>

