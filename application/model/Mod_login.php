<?php

class Model{
    
    public function login($email, $password, $mysqli, $db){
        $query = "SELECT Id, Email, Password, salt 
                  FROM TblUsers 
                  WHERE Email = '".$email."' LIMIT 1" ;
        // Using prepared statements means that SQL injection is not possible. 
        if ($stmt = $mysqli->prepare($query)) {
            //$stmt->bind_param('s', $email);  // Bind "$email" to parameter.
            $stmt->execute();    // Execute the prepared query.
            $stmt->store_result();
     
            // get variables from result.
            $stmt->bind_result($user_id, $username, $db_password, $salt);
            $stmt->fetch();
            //generate random salt
            $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
            //echo "random salt:", $random_salt;
     
            // hash the password with the unique salt.       
            $password = hash('sha512', $password . $salt);
            //$password = hash('sha512', $password . $random_salt);
            //echo "Password:",$password;
            //echo "db_Password:",$db_password;
            
            if ($stmt->num_rows == 1) {
                // If the user exists we check if the account is locked
                // from too many login attempts 
     
                if ($this->checkbrute($user_id, $mysqli) == true) {
                    // Account is locked 
                    // Send an email to user saying their account is locked
                    return false;
                } else {
                    // Check if the password in the database matches
                    // the password the user submitted.
                    if ($db_password == $password) {
                        // Password is correct!
                        // Get the user-agent string of the user.
                        $user_browser = $_SERVER['HTTP_USER_AGENT'];
                        // XSS protection as we might print this value
                        $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                        //define ('USERID',$user_id);
                        //$GLOBALS['user_id'] = $user_id;
                        $_SESSION['user_id'] = $user_id;
                        // XSS protection as we might print this value
                        $username = preg_replace("/[^a-zA-Z0-9_\-@.]+/", 
                                                                    "", 
                                                                    $username);
                        $_SESSION['username'] = $username;
                        $_SESSION['login_string'] = hash('sha512', 
                                  $password . $user_browser);
                        
                        //als beheerder in Beheer menu inlogt dan overslaan
                        $url    = isset($_GET['url']) ? $_GET['url'] : NULL;
                        $beheer = 0;

                        if(substr($url,0,6) == 'Beheer'){
                            $beheer = 1;
                        }
                        
                        //check aantal terreinen bij gebruiker
                        $aantal = $this->countTerrainsbyUser($user_id, $db);

                        //als gebruiker meer dan 1 terrein beheert dan keuze laten maken
                        if ($aantal>1 && $beheer == 0) {
                            $_SESSION['Terreinid'] = 0;
                            header('Location:../Login/terreinselect');
                            exit;

                        } else {
                            //Get terreinnaam en terreinid
                            $Terrein = $this->getTerreinNaam($user_id, $mysqli);
                            $_SESSION['Terreinnaam'] = $Terrein[1]; //terreinnaam
                            $_SESSION['Terreinid'] = $Terrein[0]; //terreinid
                            $GLOBALS['userid'] = $user_id;
                            $GLOBALS['terreinNaam'] = $Terrein[1];
                            $GLOBALS['terreinId'] = $Terrein[0];
                            // Login successful.
                            //add to log
                            $login_time = date('Y-m-d h:i:sa');
                            $log_msg = $username.';'.$Terrein[1].';'.$login_time."\n";
                            file_put_contents('application/log/login.log', $log_msg, FILE_APPEND);
                            return true;
                        }
                        
                    } else {
                        // Password is not correct
                        // We record this attempt in the database
                        $now = time();
                        $mysqli->query("INSERT INTO login_attempts(user_id, time)
                                        VALUES ('$user_id', '$now')");
                        return false;
                    }
                }
            } else {
                // No user exists.
                return false;
            }
        }
    }


    function checkbrute($user_id, $mysqli) {
        // Get timestamp of current time 
        $now = time();
     
        // All login attempts are counted from the past 30 minutes. 
        $valid_attempts = $now - (30 * 60 );
    
        if ($stmt = $mysqli->prepare("SELECT time 
                                 FROM login_attempts 
                                 WHERE user_id = ? 
                                AND time > '$valid_attempts'")) {
            $stmt->bind_param('i', $user_id);
     
            // Execute the prepared query. 
            $stmt->execute();
            $stmt->store_result();
          
            // If there have been more than 5 failed logins 
            if ($stmt->num_rows > 5) {
                return true;
            } else {
                return false;
            }
        }
    }

    //check aantal terreinen bij gebruiker
    function countTerrainsbyUser($userid,$db){
        
        $qry3 = "SELECT count(Terrein_id) as aantal 
                 FROM TblTerreinUsers 
                 WHERE User_id = '$userid' 
                ";
        $STH = $db->query($qry3);
        $STH->setFetchMode(PDO::FETCH_ASSOC);
        $row = $STH->fetch();

        return $row['aantal'];
    }

    //Get terreinnaam
    function getTerreinNaam($userid,$mysqli){
        //Get terreinnaam
        $qry2 = "SELECT tt.Id, tt.Terreinnaam 
                 FROM TblTerreinUsers ttu, TblTerrein tt 
                 where ttu.Terrein_id = tt.Id and
                       ttu.User_id = '$userid'
                ";
        $stmt2 = $mysqli->prepare($qry2);
        $stmt2->execute();    // Execute the prepared query.
        $stmt2->store_result();
        // get variables from result.
        $stmt2->bind_result($Terreinid, $Terreinnaam);
        $stmt2->fetch();
        $result = array();
        array_push($result,$Terreinid);
        array_push($result,$Terreinnaam);
        return $result;
    }

}

?>