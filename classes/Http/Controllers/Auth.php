<?php

namespace Http\Controllers;

class Auth extends \AbstractController{

    public function login($request, $response, $args) {

      return $this->view->render($response, 'auth/login.html');

    }
    
}
