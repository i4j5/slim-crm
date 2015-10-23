<?php

abstract class AbstractController {


    public function __construct () {
    	$app = App::instance();

    	$this->view = $app->view;

    	$this->flash = $app->flash;
    }

}

