<?php

spl_autoload_register(function($a){
    $path = __DIR__ . "\\" . $a . ".php";
    if(file_exists($path))
    {
        require($path);
    } else {
        $path_libs = __DIR__ . "\\libs\\" . $a . ".php";
        if(file_exists($path_libs)) require($path_libs);
    }
});