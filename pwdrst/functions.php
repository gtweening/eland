<?php

//resultaat tonen na versturen
function showResult($result, $refresh){
    echo '<script language="javascript">';
    echo 'alert("'.$result.'")';
    echo '</script>'; 
  
    switch ($refresh) {
        case 1:
            echo "<meta http-equiv=\"refresh\" content=\"0;URL=../index.php\">";
            break;
        default:
        //nothing
    }
}

function usernamecheck($username,$mysqli){
    $sql = "SELECT Email 
            FROM TblUsers 
            WHERE Email = '".$username."' LIMIT 1" ;
    $stmt = $mysqli->prepare($sql);
    $stmt->execute();               // Execute the prepared query.
    $stmt->store_result();

    // get variables from result.
    $stmt->bind_result($username);
    $stmt->fetch();
    if ($stmt->num_rows == 1) {
        //user exists
        return true;
    }else{
        //user doen not exists
        return false;
    }
}
    
function settemppwd($username, $mysqli){
    //generate password
    $length=10;
    $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!^()<>?~';
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        //$pieces []= $keyspace[random_int(0, $max)];
        $pieces []= $keyspace[rand(0, $max)];
    }
    $pwd =  implode('', $pieces);

    return $pwd;
}

function temppwdavailable($userid,$mysqli){
    $sql = "SELECT Id 
            FROM TblUsers
            WHERE Id = '".$userid."' 
              and temppwd <> '' ";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute();               // Execute the prepared query.
    $stmt->store_result();

    // get variables from result.
    $stmt->bind_result($Id);
    $stmt->fetch();
    if ($stmt->num_rows == 1) {
        //user exists
        return true;
    }else{
        //user doen not exists
        return false;
    }
}

function temppwdvalid($userid, $pwd, $time, $mysqli){
    $sql = "SELECT *
            FROM TblUsers
            WHERE Email = '".$userid."' 
              and temppwd = '".$pwd."'
              and validuntil >= '".$time."' ";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute();  
    $stmt->store_result();

    $stmt->fetch();
    if ($stmt->num_rows == 1) {
        //user exists
        return true;
    }else{
        //user doen not exists
        return false;
    }

}

function getuser($userid,$mysqli){
    $sql = "SELECT Email
            FROM TblUsers
            WHERE Id = '".$userid."' ";

    $stmt = $mysqli->prepare($sql);
    $stmt->execute();  
    $stmt->store_result();
    $stmt->bind_result($username);
    $stmt->fetch();

    if ($stmt->num_rows == 1) {
        //user exists
        return $username;
    }else{
        //user doen not exists
        return false;
    }
}

function getuserid($email,$mysqli){
    $sql = "SELECT Id
            FROM TblUsers
            WHERE Email = '".$email."' ";

    $stmt = $mysqli->prepare($sql);
    $stmt->execute();  
    $stmt->store_result();
    $stmt->bind_result($Id);
    $stmt->fetch();

    if ($stmt->num_rows == 1) {
        //user exists
        return $Id;
    }else{
        //user doen not exists
        return false;
    }
}

function getpwd($userid,$mysqli){
    $sql = "SELECT temppwd
            FROM TblUsers
            WHERE Id = '".$userid."' ";

    $stmt = $mysqli->prepare($sql);
    $stmt->execute();  
    $stmt->store_result();
    $stmt->bind_result($temppwd);
    $stmt->fetch();

    if ($stmt->num_rows == 1) {
        //user exists
        return $temppwd;
    }else{
        //user doen not exists
        return false;
    }
}

function getema($userid,$mysqli){
    $sql = "SELECT ema
            FROM TblUsers
            WHERE Id = '".$userid."' ";

    $stmt = $mysqli->prepare($sql);
    $stmt->execute();  
    $stmt->store_result();
    $stmt->bind_result($ema);
    $stmt->fetch();

    if ($stmt->num_rows == 1) {
        //user exists
        return $ema;
    }else{
        //user doen not exists
        return false;
    }
}
?>