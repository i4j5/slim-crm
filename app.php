<?php

$loader = require_once  DOCROOT . 'vendor/autoload.php';
$loader->add('', DOCROOT . 'classes/');

setlocale(LC_TIME, 'Russian');

session_start();

ORM::configure('sqlite:' . DOCROOT . 'database/crm.db');

$app = App::instance();

$app->post('/api/orders/create', function ($request, $response) {
	$data = \Ohanzee\Helper\Arr::extract(
	  $request->getParsedBody(),
	  [
	    'name',
	    'phone',
	    'email',
	    'title',
	    'status_id'
	  ], ''
	);

	if ($data['status_id'] === '') {
		$data['status_id'] = 0;
	}

  $dt = Carbon\Carbon::now();
  $data['date'] = $dt->timestamp;

  $order = Model::factory('Models\Order')->create($data);
	$order->save();

	return $response->withStatus(200);
});


$app->map(['GET', 'POST'], '/login', 'Http\Controllers\Auth:login');
$app->map(['GET', 'POST'], '/forgot', 'Http\Controllers\Auth:forgot');
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