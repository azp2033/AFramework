<?php

namespace Core;

use \Core\Database as Database;

class App {
    private $routes = [];
    private $notfoundcallback;

    public function on404($callback)
    {
        $this->notfoundcallback = $callback;
    }

    public function get($route, $callback)
    {
        $this->routes['GET'][$route] = $callback;
    }

    public function post($route, $callback)
    {
        $this->routes['POST'][$route] = $callback;
    }

    public function all($route, $callback)
    {
        $this->get($route, $callback);
        $this->post($route, $callback);
    }

    private function invoke($callback)
    {
        if(is_callable($callback))
        {
            $result = call_user_func($callback);
            if(is_string($result)) { echo $result; }
            if(is_array($result)) { echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK); }
            if(is_bool($result)) { echo json_encode(['response'=>$result], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK); }
            if(is_null($result)) { echo json_encode(['response'=>false], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK); }
            if(is_object($result) && get_class($result) == "Core\View") { echo $result->render(); }
        }
    }

    public function run()
    {
        Database::Init();
        $uri = '/' . trim(explode('?', $_SERVER['REQUEST_URI'])[0], '/');
        $method = $_SERVER['REQUEST_METHOD'];
        if(!isset($this->routes[$method][$uri])) { $this->invoke($this->notfoundcallback); }
            else $this->invoke($this->routes[$method][$uri]);
    }
}