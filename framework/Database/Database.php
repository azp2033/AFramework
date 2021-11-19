<?php

namespace Framework\Database;

class Database {
    public static $instance = NULL;

    public static function init($configuration)
    {
        self::$instance = new \Medoo\Medoo($configuration);
    }
}