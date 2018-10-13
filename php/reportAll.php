<?php

/**
Gives an overview of all checks performed and results for all obstacles.
For achive purposes.

copyright: 2013 Gerko Weening
20180227
added terreinnaam on output
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
while($rows=$STH->fetch()){
    //var_dump($_POST);
?>

<!DOCTYPE html>

<html>
<head>
    <title>Survivalhindernis Report</title>
    <style>
        #main { background: white;
                font-family: verdana;
        }
        #Table{vertical-align: top;
            
        }
        .h2 {font-family: verdana;
             vertical-align: top;
             background: darkblue;
             color: white;
        }
        .koptekst{font-family: arial;
                  font-size: small;
                  text-align: right;
                  display:block;
        }
        .TableText{font-family: verdana;
                   font-size: small; 
                   text-align: left;
                   vertical-align: top;
        }
        footer {page-break-after:always;}
    </style>
</head>
<body>
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
                $query2 = "SELECT tm.Omschr, tom.Aantal from TblObstacleMaterials tom, TblMaterials tm ";
                $query2 .= "where tom.Material_id = tm.Id and tom.Obstacle_id =". $vhindId ." ";
                $STH2 = $db->query($query2);
                while($rows2=$STH2->fetch()){
                ?>
                <tr>
                    <td class="TableText">- <?php echo (utf8_encode($rows2['Omschr'])); ?>; <?php echo ($rows2['Aantal']); ?>  </td>
                </tr>            
                <?php }?>
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
                <?php }?>
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
            <td class="TableText" width ="5%"><?php if($rows4['ChkSt']== FALSE){?>
                      <img src="../img/warning.jpeg" width="20" height="20"><?php
                   }else{?>
                      <img src="../img/ok.jpeg" width="20" height="20"><?php
                   }; ?></td>
            <td class="TableText" width ="12%"><?php echo ($rows4['CheckedBy'])?></td>
            <td class="TableText" width ="40%"><?php echo nl2br(($rows4['Note'])) ; ?></td>
        </tr>
        <?php }?>
    </table>
    <footer><hr></footer>
</body>
</html>
<?php
}
//close connection
$db = null;
} else { ?>
<br>
U bent niet geautoriseerd voor toegang tot deze pagina. 
<?php
}
?>
