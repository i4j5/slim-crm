<?php

abstract class AbstractController {


    public function __construct () {
    	$container = \App::instance()->getContainer();

    	$this->view = $container->view;

    	$this->flash = $container->flash;
    }

}

