
<?php

/**
Gives an overview of all checks performed and results for all obstacles.
For achive purposes.

copyright: 2013 Gerko Weening
20180227
added terreinnaam on output
20191213
materiaaltype toegevoegd aan report
*/

include_once "../inc/base.php";
include_once "../inc/functions.php";
include_once "../inc/queries.php";
sec_session_start(); 

//secure login
if(login_check($mysqli) == true) { 

    $whereTerrein = getterreinid();
    $query1 ="select distinct ts.Naam,tob.* ";
    $query1 .="from TblSections ts,TblObstacles tob left join TblObstacleChecks tobc on (tob.id=tobc.obstacle_id) ";
    $query1 .="where tob.Section_id = ts.Id ";
    $query1 .="and ts.".$whereTerrein;
    $query1 .="order by ts.Naam, tob.Volgnr ";
    $STH = $db->query($query1);
    $STH->setFetchMode(PDO::FETCH_ASSOC);

?>
    <!DOCTYPE html>

    <html>
    <head>
        <title>Eland - hindernislogboek rapport </title>
        <link rel="stylesheet" type="text/css" href="../css/StyleSheetReportAll.css">
        
        <script type="text/JavaScript">
            function printDiv(divName) {
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;

                document.body.innerHTML = printContents;

                window.print();

                document.body.innerHTML = originalContents;
            }
        </script>

    </head> 

    <body>
        <div class="navbar">
            <a id="afdrukbutton" onclick="printDiv('printableArea')">Afdrukken (naar bestand)</a>
        </div>
        <div id="printableArea">
            <div class="main">
                <?php
                while($rows=$STH->fetch()){
                    //var_dump($_POST);
                ?>
                    <a class="koptekst">Terrein: <?php echo $_SESSION['Terreinnaam'];?></a>
                    <hr>
                    <h2 class="h2">Hindernis: <?php echo ($rows['Naam'])."-".($rows['Volgnr']); ?></h2>
                    <a id="main">Omschrijving: <?php echo ($rows['Omschr']); ?></a>
                    <h3>Foto: </h3>
                    <?php //show picture at right format and ratio 
                        showObsPic($imgPath,$rows['ImgPath'],250,160); 
                    ?>
                    <table >
                        <tr>
                            <td id="Table" width ="350" >
                                <table>
                                    <tr>
                                        <th align="left">Materialen </th>
                                    </tr>
                                    <?php
                                    //selecteer materialen van hindernis
                                    $vhindId = ($rows['Id']); 
                                    $query2 = "SELECT tmt.Omschr as mata, tm.Omschr, tom.Aantal ";
                                    $query2 .= "from TblObstacleMaterials tom, TblMaterials tm, TblMaterialTypes tmt ";
                                    $query2 .= "where tom.Material_id = tm.Id ";
                                    $query2 .= "  and tm.MaterialType_id = tmt.Id ";
                                    $query2 .= "  and tmt.Terrein_id = ". $_SESSION['Terreinid']." ";
                                    $query2 .= "  and tom.Obstacle_id = ". $vhindId ." ";
                                    $STH2 = $db->query($query2);
                                    while($rows2=$STH2->fetch()){
                                    ?>
                                        <tr>
                                            <td class="TableText">- <?php echo (utf8_encode($rows2['mata'])) ." " . (utf8_encode($rows2['Omschr'])) . " " . ($rows2['Aantal']); ?>  </td>
                                        </tr>            
                                    <?php 
                                    }?>
                                </table>
                            </td>
                            <td id="Table">
                                <table width="350" >
                                    <tr>
                                        <th align="left">Controlepunten </th>    
                                    </tr>  
                                    <?php
                                    //selecteer controlepunten van hindernis
                                    $query3 = "SELECT tc.Omschr from TblObstacleCheckpoints toc, TblCheckpoints tc ";
                                    $query3 .= "where toc.Checkpoint_id = tc.Id and toc.Obstacle_id = ".$vhindId." ";
                                    $STH3 = $db->query($query3);
                                    while($rows3=$STH3->fetch()){
                                    ?>
                                        <tr>
                                            <td class="TableText">- <?php echo ($rows3['Omschr']); ?></td>
                                        </tr>            
                                    <?php 
                                    }?>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <hr>
                    <h3>Laatste controle </h3>
                    <table>
                        <tr align="left">
                            <th width ="7%">Datum</th>
                            <th width ="5%">Status</th>
                            <th width ="10%">Controleur</th>
                            <th width ="42%">Opmerking</th>
                        </tr>
                        <?php
                        //selecteer controles van hindernis
                        $query4 = "select tob.*, tobc.DatCheck, tobc.ChkSt, tobc.CheckedBy,tobc.Note ";
                        $query4 .="from TblObstacles tob left join TblObstacleChecks tobc on (tob.id=tobc.obstacle_id) ";
                        $query4 .="where tob.Id =". $vhindId ." ";
                        $STH4 = $db->query($query4);
                        while($rows4=$STH4->fetch()){
                
                        ?>
                        <tr>
                            <td class="TableText" width ="7%"><?php echo ($rows4['DatCheck']);?></td>
                            <td class="TableText" width ="5%">
                                <?php if($rows4['ChkSt']== FALSE){?>
                                    <img src="../img/warning.jpeg" width="20" height="20">
                                <?php
                                }else{
                                ?>
                                    <img src="../img/ok.jpeg" width="20" height="20">
                                <?php
                                } 
                                ?>
                            </td>
                            <td class="TableText" width ="12%"><?php echo ($rows4['CheckedBy'])?></td>
                            <td class="TableText" width ="40%"><?php echo nl2br(($rows4['Note'])) ; ?></td>
                        </tr>
                        <?php 
                        }?>
                    </table>
                    <footer><hr></footer>
                <?php
                }
                //close connection
                $db = null;
                ?>
                
            </div>
        </div>
    </body>

    </html>

<?php
} else { ?>
<br>
U bent niet geautoriseerd voor toegang tot deze pagina. 
<?php
}
?>
