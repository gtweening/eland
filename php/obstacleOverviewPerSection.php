<html>
<!--/**
shows overview of obstacles of selected section

copyright: 2013 Gerko Weening
20171011
solved issue with php short code
solved issue showing obstacles of section

*/-->

    <a class="tableTitle3">Sectie <?php echo $vsectionname?></a>
        <div class="cudWidget">   
        </div>
    <table id="obstacleTableQuarter">
         <tr class="theader">
            <th colspan="2">Hindernis</th>
         </tr>
        <?php
        //hindernis ophalen
        $query5 =  "Select * from $tbl_name ";
        //$query5 .= "where section_id = (select Id from TblSections where Naam = '$vsectionname') ";
        $query5 .= "where section_id = (select Id 
                                        from TblSections 
                                        where Naam = '$vsectionname' 
                                              and Terrein_id = '".$_SESSION['Terreinid']."' ) ";
        
        $query5 .= "order by Volgnr ";
        $STH5 = $db->query($query5);
        $STH5->setFetchMode(PDO::FETCH_ASSOC);
        while($rows=$STH5->fetch()){
        ?>
            <tr>
                <td width="15%" class = "white2"><?php echo str_pad(htmlentities($rows['Volgnr']),2,'0',STR_PAD_LEFT); ?></td>
                <td class = "white" onclick="getObstacle(event,'<?php echo $rows['Id'];?>','<?php echo $vsectionname;?>','<?php echo $rows['Volgnr'];?>')"><?php echo htmlentities($rows['Omschr']); ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
    
</html>

