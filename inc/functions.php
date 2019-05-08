<?php
include_once "constants.inc.php";

function sec_session_start() {
    $session_name = 'sec_session_id';   // Set a custom session name
    $secure = SECURE;
    // This stops JavaScript being able to access the session id.
    $httponly = true;
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"], 
        $cookieParams["domain"], 
        $secure,
        $httponly);
    // Sets the session name to the one set above.
    session_name($session_name);
    session_start();            // Start the PHP session
    session_regenerate_id();    // regenerated the session, delete the old one.
    
}


function login($email, $password, $mysqli) {
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
 
            if (checkbrute($user_id, $mysqli) == true) {
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
                    $_SESSION['user_id'] = $user_id;
                    // XSS protection as we might print this value
                    $username = preg_replace("/[^a-zA-Z0-9_\-@.]+/", 
                                                                "", 
                                                                $username);
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512', 
                              $password . $user_browser);
						  //check aantal terreinen bij gebruiker
						  $qry3 = "Select count(Terrein_id) as aantal 
									  from TblTerreinUsers 
									  where User_id = '$user_id' 
									 ";
						  $stmt3 = $mysqli->prepare($qry3);
						  $stmt3->execute();
						  $stmt3->store_result();
						  $stmt3->bind_result($aantal);
						  $stmt3->fetch();
					     //als gebruiker meer dan 1 terrein beheert dan keuze laten maken
						  if ($aantal>1) {
								$_SESSION['Terreinid'] = 0;
								echo "<meta http-equiv=\"refresh\" content=\"0;URL=../php/selectGebruikersTerrein.php?Id=".$user_id."\">";
								exit;
						  } else {
							  //Get terreinnaam
							  $qry2 = "Select tt.Id, tt.Terreinnaam 
										  from TblTerreinUsers ttu, TblTerrein tt 
										  where ttu.Terrein_id = tt.Id and
											     ttu.User_id = '$user_id'
										 ";
							  $stmt2 = $mysqli->prepare($qry2);
							  $stmt2->execute();    // Execute the prepared query.
							  $stmt2->store_result();
							  // get variables from result.
							  $stmt2->bind_result($Terreinid, $Terreinnaam);
							  $stmt2->fetch();
							  $_SESSION['Terreinnaam'] = $Terreinnaam;
							  $_SESSION['Terreinid'] = $Terreinid;
                              // Login successful.
                              //add to log
                              $login_time = date('Y-m-d h:i:sa');
                              $log_msg = $username.';'.$Terreinnaam.';'.$login_time."\n";
                              file_put_contents('../img/login.log', $log_msg, FILE_APPEND);
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
    echo "end";
}

function loginbeheerder($email, $password, $mysqli) {
    $query = "SELECT Id, Email, Password, salt, Admin 
              FROM TblUsers 
              WHERE Email = '".$email."' LIMIT 1" ;
    // Using prepared statements means that SQL injection is not possible. 
    if ($stmt = $mysqli->prepare($query)) {
        //$stmt->bind_param('s', $email);  // Bind "$email" to parameter.
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();
 
        // get variables from result.
        $stmt->bind_result($user_id, $username, $db_password, $salt, $admin);
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
 
            if (checkbrute($user_id, $mysqli) == true) {
                // Account is locked 
                // Send an email to user saying their account is locked
                return false;
            } else {
                // check if user is Administrator
                if ($admin == 0) {
                    //user is not administrator
                    return false;
                } else {
                    // user is adminstrator
		        // Check if the password in the database matches
		        // the password the user submitted.
		        if ($db_password == $password) {
		            // Password is correct!
		            // Get the user-agent string of the user.
		            $user_browser = $_SERVER['HTTP_USER_AGENT'];
		            // XSS protection as we might print this value
		            $user_id = preg_replace("/[^0-9]+/", "", $user_id);
		            $_SESSION['user_id'] = $user_id;
		            // XSS protection as we might print this value
		            $username = preg_replace("/[^a-zA-Z0-9_\-@.]+/", 
		                                                        "", 
		                                                        $username);
		            $_SESSION['username'] = $username;
		            $_SESSION['login_string'] = hash('sha512', 
		                      $password . $user_browser);
		            // Login successful.
		            return true;
		        } else {
		            // Password is not correct
		            // We record this attempt in the database
		            $now = time();
		            $mysqli->query("INSERT INTO login_attempts(user_id, time)
		                            VALUES ('$user_id', '$now')");
		            return false;
		        }
                }
            }
        } else {
            // No user exists.
            return false;
        }
    }
    echo "end";
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

function login_check($mysqli) {
    // Check if all session variables are set 
    if (isset($_SESSION['user_id'], 
                        $_SESSION['username'], 
                        $_SESSION['login_string'])) {
 
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];
 
        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
 
        if ($stmt = $mysqli->prepare("SELECT Password 
                                      FROM TblUsers 
                                      WHERE Id = ? LIMIT 1")) {
            // Bind "$user_id" to parameter. 
            $stmt->bind_param('i', $user_id);
            $stmt->execute();   // Execute the prepared query.
            $stmt->store_result();
 
            if ($stmt->num_rows == 1) {
                // If the user exists get variables from result.
                $stmt->bind_result($password);
                $stmt->fetch();
                $login_check = hash('sha512', $password . $user_browser);
 
                if ($login_check == $login_string) {
                    // Logged In!!!! 
                    return true;
                } else {
                    // Not logged in 
                    return false;
                }
            } else {
                // Not logged in 
                return false;
            }
        } else {
            // Not logged in 
            return false;
        }
    } else {
        // Not logged in 
        return false;
    }
}

function esc_url($url) {
 
    if ('' == $url) {
        return $url;
    }
 
    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
 
    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;
 
    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }
 
    $url = str_replace(';//', '://', $url);
 
    $url = htmlentities($url);
 
    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);
 
    if ($url[0] !== '/') {
        // We're only interested in relative links from $_SERVER['PHP_SELF']
        return '';
    } else {
        return $url;
    }
}

//function to show picture in required size and ratio
//parameters:
//path: path to image
//filename: filename of image
//width: max width
//height: max height
//it is assumed that width and height have the expected ratio
function showObsPic($path,$img,$maxb,$maxh){
    //if there is an image, check ratio
    if($img!=''){
        //standaard ratio = 300*200 (maxb*maxh)
        $defaultratio=$maxb/$maxh;
        list($w,$h) = getimagesize($path.$img);
        $ratio = $w/$h;
        $str="";
        switch (TRUE){
            case ($ratio<$defaultratio):
                //breedte is kleiner dan 300/200 => max h = 200
                $width=$maxh/$h*$w;
                $str=' width="'.$width.'" height="'.$maxh.'" ';
                break;
            case ($ratio>$defaultratio):
                //breedte is groter dan 300/200 => max b = 300
                $height=$maxb/$w*$h;
                $str=" width='".$maxb."' heigth='".$height."'";
                break;
        }
        //result
        //show image
        echo '<img src="'.$path.$img.'"'.$str.' >';
    }
}

function imgImport($db, $STH){
    $result = "";
    
    //controleer wat we gaan uploaden
    $imgPath = $_POST["imgPath"];
    $vimg = $_POST["vimg"];

    //tabel instellen
    if(isset($_POST['hindId'])){
    //herkomst = Obstacle
    $tbl = 'TblObstacles';
    $col = 'ImgPath';
    $hindId = $_POST["hindId"];
    $where = "WHERE Id = $hindId";
    }else{
    //herkomst = terreinOverzicht
    $tbl = 'TblTerrein';
    $col = 'ImgFile';
    $terreinId = $_POST["terreinId"];
    $where = "WHERE Id = $terreinId";
    }

    //toegestane extensies
    $allowedExts = array("gif", "jpeg", "jpg", "png","GIF", "JPEG", "JPG", "PNG","Gif", "Jpeg", "Jpg", "Png");
    $temp = explode(".", $_FILES["file"]["name"]);
    $extension = end($temp);
    if ((($_FILES["file"]["type"] == "image/gif")
    || ($_FILES["file"]["type"] == "image/jpeg")
    || ($_FILES["file"]["type"] == "image/jpg")
    || ($_FILES["file"]["type"] == "image/pjpeg")
    || ($_FILES["file"]["type"] == "image/x-png")
    || ($_FILES["file"]["type"] == "image/png"))
    && ($_FILES["file"]["size"] < 500000)
    && in_array($extension, $allowedExts))
      {
      if ($_FILES["file"]["error"] > 0) {
        $result = "Return Code: " . $_FILES["file"]["error"] . "<br>";
      }
      else {
        $result .= "Upload: " . $_FILES["file"]["name"] . "\\n";
        $result .= "Type: " . $_FILES["file"]["type"] . "\\n";
        $result .= "Size: " . ($_FILES["file"]["size"] / 1024) . " kB\\n";
        $result .= "Temp file: " . $_FILES["file"]["tmp_name"] . "\\n";

        if (file_exists($imgPath . $_FILES["file"]["name"])) {
          $result .= $_FILES["file"]["name"] . " bestaat al op de server. Raadpleeg de beheerder voor correctie!";
        }
        else {
          //move file to server
          move_uploaded_file($_FILES["file"]["tmp_name"],
                $imgPath . $_FILES["file"]["name"]);
          //echo "Opgeslagen in: " . $imgPath . $_FILES["file"]["name"];
          $result .= "\\n" . $_FILES["file"]["name"] . " is opgeslagen op de server.";
          $FileName = $_FILES["file"]["name"];
          $sql = "UPDATE $tbl SET $col = '".$FileName."' $where";
          $STH = $db->prepare($sql);
          $STH->execute();
          }
        }
      }
    else {
      $result =  "Ongeldig bestand. \\n";
      $result .= "Type: " . $_FILES["file"]["type"] . ". Bestand moet van het type: gif, jpeg, jpg of png zijn. \\n";
      $result .= "Grootte: " . ($_FILES["file"]["size"] / 1024) . " kB. Bestand moet kleiner zijn dan 500 Kb.\\n";
        
    }

    return $result;
}

//bestandsnaam uit db verwijderen
//enkel als er een bestand aanwezig is.
function imgDelete($db, $STH){
    //tabel instellen
    if(isset($_POST['hindId'])){
        //herkomst = Obstacle
        $tbl = 'TblObstacles';
        $col = 'ImgPath';
        $hindId = $_POST["hindId"];
        $where = "WHERE Id = $hindId";
    }else{
        //herkomst = terreinOverzicht
        $tbl = 'TblTerrein';
        $col = 'ImgFile';
        $terreinId = $_POST["terreinId"];
        $where = "WHERE Id = $terreinId";
    }

    if(!empty($_POST['vimg'])){
        $sqlUpdate = "Update $tbl Set $col = '' $where";
        $STH1=$db->prepare($sqlUpdate);
        $STH1->execute();
    }else{
        return "Geen bestand geselecteerd!";
    }
    //bestand verwijderen van server
    $fileName = $_POST['imgPath'].$_POST['vimg'];
    unlink($fileName);

    return "Bestand succesvol verwijderd.";
}

?>
