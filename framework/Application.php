<?php

namespace Framework;

class Application {
    private $data = [];
    public static $instance = NULL;

    public function __construct()
    {
        self::$instance = $this;
    }

    public function set($key, $data)
    {
        $this->data[$key] = $data;
    }

    public function get($key)
    {
        if(!isset($this->data[$key])) return NULL;
        return $this->data[$key];
    }

    public function pre()
    {
        \Framework\Router\Router::init();
        \Framework\Database\Database::init($this->get('database'));
    }

    public function run()
    {
        require($this->get('runner'));
        \Framework\Router\Router::$instance->run();
    }
}