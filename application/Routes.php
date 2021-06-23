<?php

$domain = strtolower($_SERVER['HTTP_HOST']);

switch($domain) {
    case 'hindernislogboek.survivalbond.nl' :
        define( 'WEBROOT', 'http://'.$domain);
        define( 'DIR', $_SERVER['DOCUMENT_ROOT']);
        break;

    default :
        define( 'WEBROOT', 'http://'.$domain.'/eland');
        define( 'DIR', $_SERVER['DOCUMENT_ROOT'].'/eland');
        break;
}

define( 'DS' , DIRECTORY_SEPARATOR );
define( 'CONTROLLERS', DIR.DS.'application/controller');
define( 'MODELS', DIR.DS.'application/model');
define( 'VIEWS', DIR.DS.'application/view');

//serialize speciaal voor PHP5.6. In 7.x niet meer nodig.
if (substr(PHP_VERSION,0,1) < 7){
    $LOAD_CLASSES = serialize(array(CONTROLLERS, MODELS, VIEWS));
    define( LOAD_CLASSES, $LOAD_CLASSES);
} else {
    $LOAD_CLASSES = array(CONTROLLERS, MODELS, VIEWS);
    define( 'LOAD_CLASSES', $LOAD_CLASSES);
}



?>