<?php
    define("CAN_REGISTER", "any");
    define("DEFAULT_ROLE", "member");
    
   //set environment    
    if (! defined('ENVIRONMENT') )
    {
    $domain = strtolower($_SERVER['HTTP_HOST']);

    switch($domain) {

        case 'hindernislogboek.survivalbond.nl' :
            include_once 'constants.prod.php';
            define('ENVIRONMENT', 'production');
            error_reporting(0);
            break;

        default :
            include_once 'constants.dev.php';
            define('ENVIRONMENT', 'development');
            error_reporting(E_ALL);
            break;
        }
    }

    //path to images
    $imgPath = "../img/Obstacles/";
    $imgTerrainPath = "../img/Terrain/";
?>
