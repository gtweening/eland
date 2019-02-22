<?php
    // Database credentials
    define('DB_HOST', 'localhost');
    define('DB_USER', ''); //username
    define('DB_PASS', ''); //database acces password
    define('DB_NAME', '');  //databasename
    
    define("CAN_REGISTER", "any");
    define("DEFAULT_ROLE", "member");
    
    define("SECURE", FALSE);    // FOR DEVELOPMENT ONLY!!!!

    //Environment
    define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');
    
    if (! defined('ENVIRONMENT') )
    {
    $domain = strtolower($_SERVER['HTTP_HOST']);

    switch($domain) {

        case 'test.uvponline.nl' :
            define('ENVIRONMENT', 'staging');
            error_reporting(E_ALL);
            break;

        case 'hindernislogboek.survivalbond.nl' :
                define('ENVIRONMENT', 'production');
                error_reporting(0);
                break;

        default :
                define('ENVIRONMENT', 'development');
                error_reporting(E_ALL);
                break;
        }
    }

    //path to images
    $imgPath = "../img/Obstacles/";
?>
