<html>
    <a class="tableTitle3">Sectie <?echo $vsectionname?></a>
        <div class="cudWidget">   
        </div>
    <table id="obstacleTableQuarter">
         <tr class="theader">
            <th colspan="2">Hindernis</th>
         </tr>
        <?php
        //hindernis ophalen
        $query5 =  "Select * from $tbl_name ";
        $query5 .= "where section_id = (select Id from TblSections where Naam = '$vsectionname') ";
        $query5 .= "order by Volgnr ";
        $STH5 = $db->query($query5);
        $STH5->setFetchMode(PDO::FETCH_ASSOC);
        while($rows=$STH5->fetch()){
        ?>
            <tr>
                <td width="15%" class = "white2"><? echo str_pad(htmlentities($rows['Volgnr']),2,'0',STR_PAD_LEFT); ?></td>
                <td class = "white" onclick="getObstacle(event,'<?echo $rows['Id'];?>','<?echo $vsectionname;?>','<?echo $rows['Volgnr'];?>')"><? echo htmlentities($rows['Omschr']); ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
    
</html>
            


