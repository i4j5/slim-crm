<?php

namespace Http\Controllers;

class Auth extends \AbstractController{

    public function login($request, $response, $args) {

    	if ($request->isPost()) {
    		if ( \Auth::instance()->login($request->getParsedBody()['password']) ) {
    			return $response->withRedirect('/');
    		}
    	}

      return $this->view->render($response, 'auth/login.html');

    }

    public function logout($request, $response, $args) {

  		if ( \Auth::instance()->logout() ) {
  			return $response->withRedirect('/login');
  		}

    }
    
}
