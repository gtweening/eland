<!DOCTYPE html>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo WEBROOT; ?>/css/StyleSheetReportPC.css">
    <title>Controlelijst Parcours Commissie</title>
    
</head>

<body>
    <div class="navbar">
        <a href="<?php echo WEBROOT.'/ReportControlelijstPC/ExportPDF/'.$Terreinnaam;?>">Exporteer rapport naar PDF</a>
        <a href="<?php echo WEBROOT.'/ReportControlelijstPC/ExportXLS/'.$Terreinnaam;?>">Exporteer rapport naar XLS<font size="2"> (zonder opmaak)</font> </a>
        <!--
        <a href="<?php echo WEBROOT.'/ReportControlelijstPC/ExportRTF/'.$Terreinnaam;?>">Exporteer rapport naar RTF</a>
        -->
    </div>

    <div class="main">
        <!--header-->
        <table width=100% >
            <tr>
                <td colspan=2 valign="top">	
                    <h2>Controlelijst Parcours Commissie</h2>
                </td>
                <td rowspan=2 align="right">
                    <img src="<?php echo WEBROOT; ?>/img/LogoSBN.png" width="250" >
                </td>
            </tr>
            <tr>
                <td class="headerlocatie" width=15%>Trainingslocatie:</td>
                <td class="headerlocatie">
                    <?php echo $Terreinnaam ?>
                </td>
            </tr>
        </table>
        <hr>

        <!--2nd part of header-->
        <table id="Table">
            <tr >		
                <td class="TopInfo">Plaats:</td>
                <td></td>
                <td class="TopInfo">Controleurs terrein:</td>
                <td width=40%></td>
            </tr>
            <tr>
                <td class="TopInfo">Trainingstijden:</td>
                <td></td>
                <td class="TopInfo">Controleurs SBN:</td>
                <td></td>
            </tr>
            <tr>	
                <td class="TopInfo">BHV / EHBO aanwezig tijdens trainingen:</td>
                <td class="TopInfo">Ja / Nee</td>
                <td class="TopInfo">Datum controle:	</td>
                <td></td>
            </tr>
            <tr>	
                <td></td>
                <td></td>
                <td class="TopInfo">Algemeen oordeel:	</td>
                <td></td>
            </tr>
            
        </table>

        <!--body of report-->
        <table width="100%">
            <tr>
                <th align="center" rowspan=3>Id </th>
                <th align="center" rowspan=3>Omschrijving </th>
                <th align="center" rowspan=3>Hoofdtouw </th>
                <th align="center" rowspan=3>Zekeringstouw </th>
                <!-- -->
                <?php
                //counted one to many => correct
                $i=$i-1;
                echo '<th align="center" colspan='.$i.'>Soort materiaal </th>';
                ?>
                <th align="center" rowspan=3>Hoogte [m]</th>
                <th align="center" rowspan=3>Gecontroleerd </th>
                <th align="center" rowspan=3>status </th>
                <th align="center" colspan=5>Controle PC </th>
            </tr>
            <tr >
                <!-- add dynamic materialheaders -->
                <?php
                for ($x=1; $x<=$i; $x++) {
                    echo '<th class="matheader" align="left" rowspan=2><div><span>'.${'m'.$x}.'</span></div></th>';
                }
                ?>
                <th align="center" rowspan=2>Opmerking </th>
                <th align="center" colspan=2>kleine rep. </th>
                <th align="center" colspan=2>Afgekeurd </th>
            </tr>
            <tr>

                <th align="center">Ja </th>
                <th align="center">Nee </th>
                <th align="center">Ja </th>
                <th align="center">Nee </th>
            </tr>
                <?php
                    foreach($data as $rows){
                ?>
                    <tr>
                        <td class="TableText" width="5%"><?php echo ($rows['SHId']); ?></td>
                        <td class="TableText" width="17%"><?php echo ($rows['HOmschr']); ?></td>
                        <td class="TableText" width="7%"><?php echo ($rows['hoofdtouw']) ; ?></td>
                        <td class="TableText" width="7%"><?php echo ($rows['zekeringstouw']) ; ?></td>
                        <!-- add dynamic material -->
                        <?php
                        for ($x=1; $x<=$i; $x++) {
                            $m = "m_".${'m'.$x};
                            $val = $rows[$m];
                            if ($val == 1){
                                $val = "*";
                            } 
                            echo '<td class="matText">'.$val.'</td>';
                        }
                        ?>
                        <td class="TableText"><?php echo ($rows['MaxH']); ?></td>
                        <td class="TableText"><?php echo ($rows['DatCheck']); ?></td>
                        <td class="matText" width ="5%">
                            <?php if($rows['ChkSt']== FALSE){?>
                                <img src="<?php echo WEBROOT; ?>/img/warning.jpeg" width="20" height="20"><?php
                            }else{?>
                                <img src="<?php echo WEBROOT; ?>/img/ok.jpeg" width="20" height="20"><?php
                            }; ?></td>
                        <td width="25%"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php
                } //end while
            
            //close connection
            $db = null;
            ?>
        </table>

    </div>

</body>

</html>
