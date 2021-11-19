<?php

namespace Framework\Router;

class Router {
    private $routes = [], $beforeRoutes = [], $notfoundCallback;
    public static $instance = NULL;

    public static function init()
    {
        self::$instance = new \Framework\Router\Router();
    }

    public function set404($callback)
    {
        $this->notfoundCallback = $callback;
    }

    public function before($route, $callback)
    {
        $this->beforeRoutes[] = ['route'=>$route, 'callback'=>$callback];
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

    private function handle($result)
    {
        if(is_string($result)) { return $result; }
        if(is_array($result)) { return $this->handle(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK)); }
        if(is_bool($result)) { return $this->handle(['response'=>$result]); }
        if(is_null($result)) { return $this->handle(false); }
    }

    public function run()
    {
        $uri = '/' . trim(explode('?', $_SERVER['REQUEST_URI'])[0], '/');
        $method = $_SERVER['REQUEST_METHOD'];
        if(!isset($this->routes[$method][$uri])) {
            exit($this->handle(call_user_func($this->notfoundCallback)));
            return;
        }
        foreach($this->beforeRoutes as $before)
        {
            if($before['route'] == $uri) {
                $result = call_user_func($before['callback']);
                if(!is_null($result)) { exit($this->handle($result)); }
            }
        }
        exit($this->handle(call_user_func($this->routes[$method][$uri])));
    }
}