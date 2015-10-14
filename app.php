<?php

$loader = require_once  DOCROOT . 'vendor/autoload.php';
$loader->add('', DOCROOT . 'classes/');

$app = App::instance();

ORM::configure('sqlite:' . DOCROOT . 'database/crm.db');

$app->get('/', 'Http\Controllers\Orders:index');
$app->map(['GET', 'POST'], '/create', 'Http\Controllers\Orders:create');
$app->map(['GET', 'POST'], '/edit/{id}', 'Http\Controllers\Orders:edit');
$app->get('/delete/{id}', 'Http\Controllers\Orders:delete');

$app->get('/login', 'Http\Controllers\Auth:login');

return $app;