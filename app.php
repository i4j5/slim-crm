<?php

$loader = require_once  DOCROOT . 'vendor/autoload.php';
$loader->add('', DOCROOT . 'classes/');

setlocale(LC_TIME, 'Russian'); 

$app = App::instance();

ORM::configure('sqlite:' . DOCROOT . 'database/crm.db');

$app->get('/', 'Http\Controllers\Orders:index');
$app->map(['GET', 'POST'], '/create', 'Http\Controllers\Orders:create');
$app->map(['GET', 'POST'], '/edit/{id}', 'Http\Controllers\Orders:edit');
$app->get('/delete/{id}', 'Http\Controllers\Orders:delete');

$app->get('/login', 'Http\Controllers\Auth:login');

$app->get('/settings/status', 'Http\Controllers\Status:index');
$app->map(['GET', 'POST'], '/settings/status/create', 'Http\Controllers\Status:create');
$app->map(['GET', 'POST'], '/settings/status/edit/{id}', 'Http\Controllers\Status:edit');
$app->get('/settings/status/delete/{id}', 'Http\Controllers\Status:delete');

return $app;