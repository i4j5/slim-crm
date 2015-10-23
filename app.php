<?php

$loader = require_once  DOCROOT . 'vendor/autoload.php';
$loader->add('', DOCROOT . 'classes/');

setlocale(LC_TIME, 'Russian');

session_start();

ORM::configure('sqlite:' . DOCROOT . 'database/crm.db');

$app = App::instance();

$app->map(['GET', 'POST'], '/login', 'Http\Controllers\Auth:login');
$app->get('/logout', 'Http\Controllers\Auth:logout');

$app->group('/', function () {

	$this->get('', 'Http\Controllers\Orders:index');
	$this->map(['GET', 'POST'], 'create', 'Http\Controllers\Orders:create');
	$this->map(['GET', 'POST'], 'edit/{id}', 'Http\Controllers\Orders:edit');
	$this->get('delete/{id}', 'Http\Controllers\Orders:delete');

	$this->get('settings/status', 'Http\Controllers\Status:index');
	$this->map(['GET', 'POST'], 'settings/status/create', 'Http\Controllers\Status:create');
	$this->map(['GET', 'POST'], 'settings/status/edit/{id}', 'Http\Controllers\Status:edit');
	$this->get('settings/status/delete/{id}', 'Http\Controllers\Status:delete');

	$this->map(['GET', 'POST'], 'settings', 'Http\Controllers\Settings:index');

})->add( new Http\Middleware\Auth() );

return $app;