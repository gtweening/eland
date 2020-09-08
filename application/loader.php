<?php

function Loader($class) {
    
    //echo "class:".$class."<br>";
    $class_file = DIR.DS.$class.'.php';
    if(file_exists($class_file)){
        require_once($class_file);
    } else {
        foreach(AUTOLOAD_CLASSES as $path) {
            $class_file = $path.DS.$class.'.php';
            if(file_exists($class_file)) require_once($class_file);
            //echo $class_file."<br>";
        }
    }
}

spl_autoload_register('Loader');


?>