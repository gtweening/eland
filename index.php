<?php

require_once 'application/Routes.php';
require_once 'application/loader.php';

$url = isset($_GET['url']) ? $_GET['url'] : NULL;
$url = explode('/',$url);
//print_r($url)."<br>";

if ($url[0]==''){
    require_once 'application/controller/Login.php';
    $controller = new Login;

}else{

    $controller = new $url[0];
}

if (isset($url[1])){
    $controller->{$url[1]}();

} else {
    $controller->index();
}
/**
if(isset($url[2])){
    $controller->{$url[1]}($url[2]);
}else if (isset($url[1])){
    $controller->{$url[1]}();
} else {
    $controller->index();
}
*/


?>