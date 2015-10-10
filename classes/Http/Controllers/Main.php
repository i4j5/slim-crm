<?php

namespace Http\Controllers;

class Main extends \AbstractController{

    public function index($request, $response, $args) {

      return $this->view->render($response, 'main/index.html');

    }
    
}
