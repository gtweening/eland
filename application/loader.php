<?php

function Loader($class) {
    
    $class_file = WEBROOT.DS.$class.'.php';
  
    if(file_exists($class_file)){
        require_once($class_file);
    } else {
	//serialize speciaal voor PHP5.6. In 7.x niet meer nodig.
        foreach( unserialize(LOAD_CLASSES) as $path) {
            $class_file = $path.DS.$class.'.php';
            if(file_exists($class_file)) require_once($class_file);
         }
    }
}

spl_autoload_register('Loader');


?>