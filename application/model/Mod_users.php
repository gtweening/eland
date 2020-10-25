<?php
        
class mod_users{

    function getUsers($db){
        $STH = $db->query('SELECT * from TblUsers order by Id');
        $STH->setFetchMode(PDO::FETCH_ASSOC);
        return $STH;
    }

    //ophalen contact email adres van gebruiker obv terreinid
    function getUserEmail($db,$terreinId){
        $STH = $db->prepare('SELECT tu.ema from TblUsers tu
                                           inner join TblTerreinUsers ttu on tu.Id = ttu.User_id
                             WHERE ttu.Terrein_id = '.$terreinId
                            );
        $STH->execute();
        $row = $STH->fetch();
        return htmlentities($row['ema']); 
    }

    function editUserEmail($db, $terreinId, $contactEma){
        $sql = "Select User_id from TblTerreinUsers ";   
        $sql.= "WHERE Terrein_id = $terreinId";
        $STH = $db->prepare($sql);
        $STH->execute();

        $row = $STH->fetch();
        $id  = htmlentities($row['User_id']);
        $sql = "UPDATE TblUsers SET ema = '".$contactEma."' ";    
        $sql.= "WHERE Id = $id";  
                                
        $STH = $db->prepare($sql);
        $STH->execute();
    }
    
    function getGebruikersTerreinen($db){
        $STH = $db->query('SELECT ttu.*, tt.Terreinnaam, tu.Email
						   from TblTerreinUsers ttu, TblTerrein tt, TblUsers tu
						   where ttu.Terrein_id = tt.Id and
								ttu.User_id = tu.Id 
						   order by tu.Id');
        $STH->setFetchMode(PDO::FETCH_ASSOC);
        return $STH;
    }

    function addTerreinUser($user, $terrein, $db){
        $STH = $db->prepare("INSERT INTO TblTerreinUsers (User_id, Terrein_id) VALUES ('$user','$terrein')");
        $STH->execute();
    }

    function delTerreinUsers($ids, $db){
        $STH = $db->prepare("DELETE FROM TblTerreinUsers WHERE Id IN ('".$ids."')");
        $STH->execute();
    }
    

    function usernamecheck($username,$db){
        $sql = "SELECT Email 
                FROM TblUsers 
                WHERE Email = '".$username."' LIMIT 1" ;
        $stmt = $db->prepare($sql);
        $stmt->execute();               // Execute the prepared query.
        if ($stmt->rowCount() == 1) {
            //user exists
            return true;
        }else{
            //user doen not exists
            return false;
        }
    }

    function settemppwd($username, $db){
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
    
    function temppwdavailable($userid,$db){
        $sql = "SELECT Id 
                FROM TblUsers
                WHERE Id = '".$userid."' 
                  and temppwd <> '' ";
        $stmt = $db->prepare($sql);
        $stmt->execute();               // Execute the prepared query.
        if ($stmt->rowCount() == 1) {
            //user exists
            return true;
        }else{
            //user doen not exists
            return false;
        }
    }
    
    function temppwdvalid($userid, $pwd, $time, $db){
        $sql = "SELECT *
                FROM TblUsers
                WHERE Email = '".$userid."' 
                  and temppwd = '".$pwd."'
                  and validuntil >= '".$time."' ";
        $stmt = $db->prepare($sql);
        $stmt->execute();     
        if ($stmt->rowCount() == 1) {
            //user exists
            return true;
        }else{
            //user doen not exists
            return false;
        }
    
    }
    
    function getuser($userid,$db){
        $sql = "SELECT Email
                FROM TblUsers
                WHERE Id = '".$userid."' ";
    
        $stmt = $db->prepare($sql);
        $stmt->execute();  
        $result = $stmt->fetch();
        $username = $result['Email'];
    
        if ($stmt->rowCount() == 1) {
            //user exists
            return $username;
        }else{
            //user doen not exists
            return false;
        }
    }
    
    function getuserid($email,$db){
        $sql = "SELECT Id
                FROM TblUsers
                WHERE Email = '".$email."' ";
    
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        $Id = $result['Id'];
    
        if ($stmt->rowCount() == 1) {
            //user exists
            return $Id;
        }else{
            //user doen not exists
            return false;
        }
    }
    
    function getpwd($userid,$db){
        $sql = "SELECT temppwd
                FROM TblUsers
                WHERE Id = '".$userid."' ";
    
        $stmt = $db->prepare($sql);
        $stmt->execute();  
        $result = $stmt->fetch();
        $temppwd = $result['temppwd'];
    
        if ($stmt->rowCount() == 1) {
            //user exists
            return $temppwd;
        }else{
            //user doen not exists
            return false;
        }
    }
    
    function getema($userid,$db){
        $sql = "SELECT ema
                FROM TblUsers
                WHERE Id = '".$userid."' ";
    
        $stmt = $db->prepare($sql);
        $stmt->execute();  
        $result = $stmt->fetch();
        $ema = $result['ema'];
    
        if ($stmt->rowCount() == 1) {
            //user exists
            return $ema;
        }else{
            //user doen not exists
            return false;
        }
    }

    function getrole($email,$db){
        $sql = "SELECT role
                FROM TblUsers
                WHERE Email = '".$email."' ";
    
        $stmt = $db->prepare($sql);
        $stmt->execute();  
        $result = $stmt->fetch();
        $role   = $result['role'];
    
        if ($stmt->rowCount() == 1) {
            //user exists
            return $role;

        }else{
            //user doen not exists
            return false;
        }
    }

    function editTempPwd($pwd, $endtime, $uname, $db){
        $stmt = $db->prepare("UPDATE TblUsers 
                              SET temppwd = '".$pwd."' , validuntil = '".$endtime."' 
                              WHERE email = '".$uname."' ");
        $stmt->execute();
    }

    function resetPwd($userid, $password, $random_salt, $db){
        //insert new pwd
        $STH = $db->prepare("UPDATE TblUsers 
                            SET Password = '$password', salt = '$random_salt', temppwd = '', validuntil = '' 
                            WHERE Id = '$userid'");
        $STH->execute();

        return true;
    }

    function addUser($email, $password, $salt, $indadmin, $ema, $role, $db){
        $STH = $db->prepare("INSERT INTO TblUsers (Email, Password, salt, Admin, ema, role) VALUES
                            ('$email','$password', '$salt', '$indadmin', '$ema', '$role')");
        $STH->execute();
    }

    function delUsers($ids, $db){
        $STH = $db->prepare("DELETE FROM TblUsers WHERE Id IN ('".$ids."')");
        $STH->execute();
    }
    
    
     

}

?>