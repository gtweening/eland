<?php

class Logout extends Controller {

        public function __construct()
        {
                $this->sec_session_start();

                // Unset all session values 
                $_SESSION = array();
                
                // get session parameters 
                $params = session_get_cookie_params();
                
                // Delete the actual cookie.                 
                if (isset($_COOKIE['eland'])) {
                        unset($_COOKIE['eland']); 
                        setcookie('eland', null, -1, '/'); 
                        
                } else {
                        return false;
                }
                
                setcookie(session_name(), '', time() - 3600, 
                        $params["path"], 
                        $params["domain"], 
                        $params["secure"], 
                        $params["httponly"]);
                
                // Destroy session 
                session_unset();
                session_destroy();
                
                header('Location:/');
                exit();
                
        }
}

?>
