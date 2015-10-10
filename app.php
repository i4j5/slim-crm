<?php

$loader = require_once  DOCROOT . 'vendor/autoload.php';
$loader->add('', DOCROOT . 'classes/');

$app = App::instance();

ORM::configure('sqlite:' . DOCROOT . 'database/crm.db');

$app->get('/', 'Http\Controllers\Main:index');

return $app;