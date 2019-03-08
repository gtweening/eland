<?php
    define("CAN_REGISTER", "any");
    define("DEFAULT_ROLE", "member");
    
    //Environment
    define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');
    
    if (! defined('ENVIRONMENT') )
    {
    $domain = strtolower($_SERVER['HTTP_HOST']);

    switch($domain) {

        case 'hindernislogboek.survivalbond.nl' :
            include 'constants.prod.php';
            define('ENVIRONMENT', 'production');
            error_reporting(0);
            break;

        default :
            include 'constants.dev.php';
            define('ENVIRONMENT', 'development');
            error_reporting(E_ALL);
            break;
        }
    }

    //path to images
    $imgPath = "../img/Obstacles/";
?>
