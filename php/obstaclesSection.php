<?php>

/**
Shows the obstacles for the selected section.

copyright: 2013 Gerko Weening
*/

include_once "../inc/base.php";
include_once "../inc/functions.php";

sec_session_start();
include_once "../common/header.php"; 
include_once "../common/leftColumn.php";

$tbl_name="TblObstacles"; // Table name
   
//determine sectionname
if(isset($_GET['sectie'])){
    $vsectionname = $_GET['sectie'];
    $STH = $db->query('SELECT * from TblSections where Naam ="'.$vsectionname.'"');
    $STH->setFetchMode(PDO::FETCH_ASSOC);
    $row=$STH->fetch();
    $vsectionid=$row['Id'];
}else{
    $STH = $db->query('SELECT * from TblSections where Omschr ="'.$_GET['omschr'].'"');
    $STH->setFetchMode(PDO::FETCH_ASSOC);
    $row=$STH->fetch();
    $vsectionname=$row['Naam'];
    $vsectionid=$row['Id'];
}

//secure login
if(login_check($mysqli) == true) {
?>
<html>
    <head>
        <script type="text/javascript">
            function FunOmschr(e,Id,sectionname,volgnr){
                if(!e.target){
                    // alert(e.srcElement.innerHTML);
                } else {
                    var hindomschr =  e.target.innerHTML;
                    window.location.href = "obstacle.php?Id=" + Id +"&Sec="+sectionname+"&Vnr="+volgnr;
                }
            }
        </script>
    </head>    
    <body id="sections">
        <div id="LeftColumn2">
            
        </div>
        <div id="RightColumn">
        <table display:block>
        <tr >
        <td>
        <form name="form1" method="post" action="frmHandlingObst.php">
            <table id="materialenTable2">

               <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Hindernissen sectie: <?echo $vsectionname;?></a>
                    <div class="cudWidget"> 
                        <button type="submit" name="delObstacle">
                            <img src="../img/del.jpeg" width="35" height="35">
                        </button>
                    </div>
                    <div class="cudWidget">
                        <input type="hidden" name="sectionName" value="<?php echo $vsectionname;?>">
                        <button type="submit" name="editObstacle">
                            <img src="../img/edit.jpeg" width="35" height="35">
                        </button>
                    </div>

                    <div id="widgetBar">
                        <input type="hidden" name="sectionId" value="<?php echo $vsectionid;?>">
                        <input type="text" class="inputText" name="volgnr" maxlength="5" size="5">
                        <input type="text" class="inputText" name="hindernisOmschr" maxlength="32" size="32">
                        <div class="cudWidget">
                            <button type="submit" name="addObstacle" float="right">
                                <img src="../img/add.jpeg" width="35" height="35">
                            </button>
                        </div>
                    </div>


                <tr class="theader">
                    <th width="5%" ></th>
                    <th width="10%"><strong>Volgnr</strong></th>
                    <th ><strong>Omschrijving</strong></th>
                </tr>

                <?php
                //$query='Select * from TblObstacles'
                //    . 'where section_id = (select Id from TblSections where Naam = "'.$vsectionname .'") '

                $STH = $db->query('Select * from '.$tbl_name.' where section_id = (select Id from TblSections where Naam = "'.$vsectionname .'") order by Volgnr');
                $STH->setFetchMode(PDO::FETCH_ASSOC);
                while($rows=$STH->fetch()){
                ?>

                <tr>
                    <td width="5%" class="white2"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<? echo $rows['Id']; ?>"></td>
                    <td class = "white2"><? echo str_pad(htmlentities($rows['Volgnr']),2,'0',STR_PAD_LEFT); ?></td>
                    <td class = "white" onclick="FunOmschr(event,'<?echo $rows['Id'];?>','<?echo $vsectionname;?>','<?echo $rows['Volgnr'];?>')"><? echo htmlentities($rows['Omschr']); ?></td>
                </tr>

                <?php
                }
                
                if(isset($_GET['hindVolgnr'])){
                  $sVolgnr = $_GET['hindVolgnr'];
                  $sOmschr = $_GET['hindOmschr'];
                  $sId = $_GET['hindId'];
                  $ssectionNaam = $_GET['sectieNaam'];
                  $STH = $db->prepare("UPDATE $tbl_name SET Volgnr = '".$sVolgnr."', Omschr = '".$sOmschr."' WHERE Id = $sId");
                  $STH->execute();
                  // if successful redirect to delete_multiple.php
                    if($STH){
                        echo "<meta http-equiv=\"refresh\" content=\"0;URL=obstaclesSection.php?sectie=".$ssectionNaam."\">";
                    }
                }
                //close connection
                $db = null;
                ?>

            </table>
        </form>
        </td>
        </tr>
        </table>
        </div>
    </body>
</html>
<?php
} else { ?>
<br>
U bent niet geautoriseerd voor toegang tot deze pagina. <a href="index.php">Inloggen</a> alstublieft.
<?php
}
?>
