<?php

abstract class AbstractController {

		private static $app;

    public function __construct () {
    	self::$app = App::instance();

    	$this->view = self::$app->view;
    }

}
