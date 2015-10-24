<?php

namespace Http\Controllers;

class Auth extends \AbstractController{

    public function login($request, $response, $args) {

      $errors = [];

    	if ($request->isPost()) {
    		if ( \Auth::instance()->login($request->getParsedBody()['password']) ) {
    			return $response->withRedirect('/');
    		}

        $errors[] = 'Неправильный пароль!';
    	}

      return $this->view->render($response, 'auth/login.html', ['errors' => $errors]);

    }

    public function logout($request, $response, $args) {

  		if ( \Auth::instance()->logout() ) {
  			return $response->withRedirect('/login');
  		}

    }

    public function forgot($request, $response, $args) {

      $errors = [];

      $email = \Model::factory('Models\Settings')
                ->where('key', 'email')
                ->find_one()
                ->value;

      $data['email'] = $request->getParsedBody()['email']; 

      if ($request->isPost()) {
        if ( $data['email'] === $email ) {

          $headers = "Content-type: text/html; charset=utf-8";

          $password = \Auth::instance()->generate_password(10);

          $url = 'http://' . $_SERVER['HTTP_HOST'];

          $message = "
            <h3>Здравствуйте!</h3>
            Пароль доступа был изменен <br><br>
            <b>Панель управления:</b> <a href='$url'>$url</a><br>
            <b>Пароль:</b> $password
          ";

          mail ($email, 'slimCRM', $message, $headers);
              
          $hash = password_hash($password,  CRYPT_BLOWFISH);

          $password_hash = \Model::factory('Models\Settings')
                              ->where('key', 'password_hash')
                              ->find_one();
          $password_hash->value = $hash;
          $password_hash->save();

          return $response->withRedirect('/login');
        }

        $errors[] = 'Неправильный E-mail';
      }

      return $this->view->render($response, 'auth/forgot.html', [
        'errors' => $errors,
        'data' => $data
      ]);

    }
    
}
