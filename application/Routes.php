<?php

$domain = strtolower($_SERVER['HTTP_HOST']);
switch($domain) {
    case 'hindernislogboek.survivalbond.nl' :
        define( 'WEBROOT', 'http://'.$_SERVER['HTTP_HOST']);
        define( 'DIR', $_SERVER['DOCUMENT_ROOT']);
        break;

    default :
        define( 'WEBROOT', 'http://localhost/eland');
        define( 'DIR', $_SERVER['DOCUMENT_ROOT'].'/eland');
        break;
}

define( 'DS' , DIRECTORY_SEPARATOR );
define( 'CONTROLLERS', DIR.DS.'application/controller');
define( 'MODELS', DIR.DS.'application/model');
define( 'VIEWS', DIR.DS.'application/view');

define( 'AUTOLOAD_CLASSES', array(CONTROLLERS, MODELS, VIEWS));


?>