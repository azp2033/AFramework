<?php

use \Framework\Application;
use \Framework\Router\Router;

foreach(scandir(__ROOT__.'/app/routes') as $route)
{
    $app = Application::$instance;
    $router = Router::$instance;
    if(substr($route, strlen($route) - 3, 3) == 'php')
        require(__ROOT__.'/app/routes/'.$route);
}