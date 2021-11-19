<?php

use \Framework\View\View;

$router->get('/', function() {
    return View::render('index.html');
});