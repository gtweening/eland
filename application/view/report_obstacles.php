<html>
<head>
    <title><?php echo $title.' '.$_SESSION['Terreinnaam'] ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo WEBROOT; ?>/css/StyleSheetReportAll.css">
    <script type="text/JavaScript">
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
    </script>

    <style>
        #main { background: white;
                font-family: arial;
        }
        #Table{vertical-align: top;
            
        }
        .h2 {font-family: arial;
             vertical-align: top;
             background: darkblue;
             color: white;
        }
        .TableText{font-family: arial;
                   font-size: small; 
                   text-align: left;
                   vertical-align: top;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <a id="afdrukbutton" onclick="printDiv('printableArea')">Afdrukken (naar bestand)</a>
    </div>

    <div id="printableArea">
        <div class="main">
            <h2 class="h2"><?php echo $title.' '.$_SESSION['Terreinnaam'] ?></h2>
            <?php if(isset($jaar)){ ?>
                <h3> <?php echo $jaar.', kwartaal '.$kwartaal; ?></h3>
            <?php } ?>
            <?php echo '<img src="'.WEBROOT.'/img/Terrain/'.$terreinPic.'"'.$imgstyle.' >'; ?>
            <span style="page-break-after: always;"></span>

            <!-- overzicht te controleren hindernissen -->
            
            <?php
                $obstacles = array_keys($obstaclesToCheckArray);
                foreach($obstacles as $key){ 
            ?>
                    <h2 class="h2">Hindernis: <?php echo ($obstaclesToCheckArray[$key]['Sectie']),($obstaclesToCheckArray[$key]['Volgnr']); ?></h2>
                    <a id="main">Omschrijving: <?php echo ($obstaclesToCheckArray[$key]['Omschr']); ?></a>
                    <h3>Foto: </h3>
                    <div>
                        <?php echo '<img src="'.WEBROOT.'/img/Obstacles/'.$obstaclesToCheckArray[$key]['ImgPath'].'" alt="" width="250" height="160"  '; ?>
                    </div>

                    <div>
                        <div id="Table" style="width=350; display:inline-block;" >
                            <table>
                                <tr>
                                    <th align="left">Materialen </th>
                                </tr>
                                <?php
                                //selecteer materialen van hindernis
                                if(isset($obstacleMaterialsArray[$key])){
                                    $obstacleMaterials = $obstacleMaterialsArray[$key];
                                    foreach($obstacleMaterials as $rows2 ){
                                    ?>
                                    <tr>
                                        <td class="TableText">- <?php echo (utf8_encode($rows2['material'])) ." " . (utf8_encode($rows2['omschrijving'])) . " " . ($rows2['toelichting']); ?>  </td>
                                    </tr>            
                                <?php }
                                }?>
                            </table>
                        </div>

                        <div id="Table" style="width=350; display:inline-block;" >
                            <table >
                                <tr>
                                    <th align="left">Controlepunten </th>    
                                </tr>  
                                <?php
                                //selecteer controlepunten van hindernis
                                if(isset($obstacleCheckpointsArray[$key])){
                                    $obstacleCheckpoints = $obstacleCheckpointsArray[$key];
                                    foreach($obstacleCheckpoints as $rows3){
                                    ?>
                                    <tr>
                                        <td class="TableText">- <?php echo ($rows3['omschrijving']); ?></td>
                                    </tr>            
                                <?php }
                                }?>
                            </table>
                        </div>
                    </div>

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
                        if(isset($obstacleChecksArray[$key])){
                            $obstacleChecks = $obstacleChecksArray[$key];
                            foreach($obstacleChecks as $rows4){
                            ?>
                            <tr>
                                <td class="TableText" width ="7%"><?php echo ($rows4['datum'])?></td>
                                <td class="TableText" width ="5%"><?php if($rows4['status']== FALSE){?>
                                        <img src="<?php echo WEBROOT ?>/img/warning.jpeg" width="20" height="20"><?php
                                    }else{?>
                                        <img src="<?php echo WEBROOT ?>/img/ok.jpeg" width="20" height="20"><?php
                                    }; ?></td>
                                <td class="TableText" width ="12%"><?php echo ($rows4['controleur'])?></td>
                                <td class="TableText" width ="40%"><?php echo nl2br(($rows4['notitie'])) ; ?></td>
                            </tr>
                        <?php }
                        }
                        ?>
                    </table>
                    
                    <span style="page-break-after: always;"></span>
            <?php
                } //end while
            ?>
        </div>
    </div>

</body>

</html>