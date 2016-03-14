<html>
    <head>
        <script type="text/javascript">

        </script>
    </head>
</html>

<?php
include_once "inc/base.php";
include_once "inc/functions.php";
$tbl_name1="TblObstacleChecks"; // Table name

if(isset($_POST['addCheck'])){
    //var_dump($_POST);
    if(isset($_POST['cstatus']) && $_POST['cstatus']==TRUE){
        $status="1";
    }else{
        $status="0";
    }
    if(!empty($_POST['checkbox'])){
        foreach($_POST['checkbox'] as $val){
            $ids[] = (int) $val;
        }

        if(!empty($_POST['cdatum'])){
            $i = 1;
            foreach($ids as $item) { //bind the values one by one
                $query = "INSERT INTO $tbl_name1 (Obstacle_id, DatCheck, ChkSt, CheckedBy, Note) VALUES " ;           
                $query .= "($item, '$_POST[cdatum]' , '$status', '$_POST[ccontroleur]', '$_POST[cnote]')";
                //echo $query;
                $stmt = $db -> prepare($query);
                $stmt->execute();
            }
        }
    }   
    // if successful redirect to CheckedList.php
    if($stmt){
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=CheckedList.php?ChkQ=".$_POST['ChkQ']."&datend=".$_POST['datend']."\">";
    }    
}else if(isset($_POST['delCheck'])){
    //var_dump($_POST);
    if(!empty($_POST['checkbox'])){
        foreach($_POST['checkbox'] as $val){
            $ids[] = (int) $val;
        }
        $ids = implode("','", $ids);
        $query5 = "DELETE FROM $tbl_name1 ";
        $query5 .= "WHERE Obstacle_id IN ('".$ids."') and ";
        $query5 .= "DatCheck between '".$_POST['datend']."' and DATE_ADD('".$_POST['datend']."', INTERVAL +3 MONTH) ";
        $STH = $db->prepare($query5);
        $STH->execute();
    }
    // if successful redirect to delete_multiple.php
    if($STH){
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=CheckedList.php?ChkQ=".$_POST['ChkQ']."&datend=".$_POST['datend']."\">";
    }
}

?>
