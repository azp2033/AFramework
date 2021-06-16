<?php

namespace Core;

class Database {
    static $db;

    public static function Init()
    {
        self::$db = new \catfan\Medoo([
            'database_type' => 'mysql',
            'database_name' => "db",
            'server' => "localhost",
            'username' => "root",
            'password' => "root"
        ]);
    }

    public static function get() { return self::$db; }
}