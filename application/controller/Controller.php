<?php

class Controller {     
  
    public function __construct()    
    {    
        $this->base_path = $_SERVER['DOCUMENT_ROOT'];
        $this->app_path  = $_SERVER['DOCUMENT_ROOT']."/eland/application/";

        //path to images
        $this->imgPath = $this->base_path."/eland/img/";
        $this->obsPath = $this->base_path."/eland/img/Obstacles/";
        $this->imgTerrainPath = $this->base_path."/eland/img/Terrain/";  
        
        include_once($this->app_path."config/base.php");
        $this->db = $db;
        $this->dsn = $dsn;
        $this->mysqli = $mysqli;
 
        //url
        $url = isset($_GET['url']) ? $_GET['url'] : NULL;
        $url = explode('/',$url);
        $this->url = $url;

    }   
    
    public function sec_session_start() {
        //echo "session start<br>";
        //echo $session_name;
        $session_name = 'eland';   // Set a custom session name
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
        //echo "session started<br>";
    }

    public function checkPermission($mysqli){
        //echo "check permission<br>";
        //secure login
        $check = $this->login_check($mysqli);
  
        switch ($check) {
            case TRUE:
                return true;
            case FALSE:
                echo "U bent niet geautoriseerd voor toegang tot deze pagina. <a href='/eland/'>Inloggen</a> alstublieft.";
                exit;
        }
       
    }

    function login_check($mysqli){
        //echo "login check<br>";
        // Check if all session variables are set 
        //print_r($_SESSION);
       
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
                        // exit;
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

} 
?>