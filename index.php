<?php

session_start();

define('APP_NOCACHE', true);
require('autoload.php');

$app = new \Core\App();

$app->get('/', function() {
    return new \Core\View("index", ["world"=>"world epta!"]);
});

$app->run();