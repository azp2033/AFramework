<?php

require('vendor/autoload.php');
define('__ROOT__', __DIR__);

$app = new \Framework\Application();
$app->set('database', [
    'type' => 'mysql',
    'host' => 'localhost', //IP MYSQL сервера
    'database' => 'sqwore', //Название базы данных
    'username' => 'root', //Пользователь
    'password' => '' //Пароль
]);
$app->set('xor', [
    'key' => 'wZQmiFpKycTNSxFY' //Ключ шифровки (16, 32, 64 симв.)
]);
$app->set('app', [
    'cache' => false, //Кешируются ли View'сы
    'views_dir' => __DIR__ . '/app/views',
    'cache_dir' => __DIR__ . '/app/cache'
]);
$app->set('runner', __DIR__ . '/app/Bootstrap.php');
$app->pre();
$app->run();