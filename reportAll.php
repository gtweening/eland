<?php
include_once "inc/base.php";
//bepaal op basis van opgegeven jaar en kwartaal
//de controleperiode die beschouwd moet worden
if(isset($_POST['jaar'])){
    $jaar = substr($_POST['jaar'], -2);
    //$jaar = $_POST['jaar'];    
    $i=$_POST['kwartaal'];
    switch ($i) {
        case 1:
            $datbegin = $jaar."-01-01";
            $datend = date("Y-m-d", strtotime($datbegin));
            $ChkQ = 'tob.ChkQ1';
            break;
        case 2:
            $datend = date("Y-m-d", strtotime($jaar."-04-01"));
            $ChkQ = 'tob.ChkQ2';
            break;
        case 3:
            $datend = date("Y-m-d", strtotime($jaar."-07-01"));
            $ChkQ = 'tob.ChkQ3';
            break;
        case 4:
            $datend = date("Y-m-d", strtotime($jaar."-10-01"));
            $ChkQ = 'tob.ChkQ4';
            break;
    }
}

$query1 ="select distinct ts.Naam,tob.* ";
$query1 .="from TblSections ts,TblObstacles tob left join TblObstacleChecks tobc on (tob.id=tobc.obstacle_id) ";
$query1 .="where tob.Section_id = ts.Id " ;
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
        .TableText{font-family: verdana;
                   font-size: small; 
                   text-align: left;
                   vertical-align: top;
        }
        footer {page-break-after:always;}
    </style>
</head>
<body>
    <h2 class="h2">Hindernis: <?php echo ($rows['Naam']),($rows['Volgnr']); ?></h2>
    <a id="main">Omschrijving: <?php echo ($rows['Omschr']); ?></a>
    <h3>Foto: </h3>
    <img src="<?echo $imgPath,($rows['ImgPath']);?>" alt="" width="250" height="160" >
    <table >
    <tr>
        <td id="Table" width ="350" >
             <table>
                <tr>
                    <th align="left">Materialen </th>
                </tr>
                <?
                //selecteer materialen van hindernis
                $vhindId = ($rows['Id']); 
                $query2 = "SELECT tm.Omschr, tom.Aantal from TblObstacleMaterials tom, TblMaterials tm ";
                $query2 .= "where tom.Material_id = tm.Id and tom.Obstacle_id =". $vhindId ." ";
                $STH2 = $db->query($query2);
                while($rows2=$STH2->fetch()){
                ?>
                <tr>
                    <td class="TableText">- <?php echo ($rows2['Omschr']); ?>; <?php echo ($rows2['Aantal']); ?>  </td>
                </tr>            
                <?}?>
             </table>
        </td>
        <td id="Table">
            <table width="350" >
                <tr>
                    <th align="left">Controlepunten </th>    
                </tr>  
                <?
                //selecteer controlepunten van hindernis
                $query3 = "SELECT tc.Omschr from TblObstacleCheckpoints toc, TblCheckpoints tc ";
                $query3 .= "where toc.Checkpoint_id = tc.Id and toc.Obstacle_id = ".$vhindId." ";
                $STH3 = $db->query($query3);
                while($rows3=$STH3->fetch()){
                ?>
                <tr>
                    <td class="TableText">- <?php echo ($rows3['Omschr']); ?></td>
                </tr>            
                <?}?>
            </table>
        </td>
    </tr>
    </table>

    <h3>Laatste controle </h3>
    <table>
        <tr align="left">
            <th width ="7%">Datum</th>
            <th width ="5%">Status</th>
            <th width ="10%">Controleur</th>
            <th width ="42%">Opmerking</th>
        </tr>
        <?
        //selecteer controles van hindernis
        $query4 = "select tob.*, tobc.DatCheck, tobc.ChkSt, tobc.CheckedBy,tobc.Note ";
        $query4 .="from TblObstacles tob left join TblObstacleChecks tobc on (tob.id=tobc.obstacle_id) ";
        $query4 .="where tob.Id =". $vhindId ." ";
        $STH4 = $db->query($query4);
        while($rows4=$STH4->fetch()){
 
        ?>
        <tr>
            <td class="TableText" width ="7%"><?php echo ($rows4['DatCheck']);?></td>
            <td class="TableText" width ="5%"><?if($rows4['ChkSt']== FALSE){?>
                      <img src="img/warning.jpeg" width="20" height="20"><?
                   }else{?>
                      <img src="img/ok.jpeg" width="20" height="20"><?
                   }; ?></td>
            <td class="TableText" width ="12%"><?php echo ($rows4['CheckedBy'])?></td>
            <td class="TableText" width ="40%"><?php echo nl2br(($rows4['Note'])) ; ?></td>
        </tr>
        <?}?>
    </table>
    <footer></footer>
</body>
</html>
<?
}
//close connection
$db = null;
?>
