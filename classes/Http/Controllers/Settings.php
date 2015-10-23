<?php

namespace Http\Controllers;

use \Model;

class Settings extends \AbstractController{

    public function index($request, $response, $args) {

    	$settings = Model::factory('Models\Settings')->find_many();

      $data = [];
      foreach ($settings as $item) {
          $data[$item->key] = $item->value;
      }

      if ($request->isPost()) {

        $data = \Ohanzee\Helper\Arr::extract(
          $request->getParsedBody(),
          [
            'email',
            'pagination',
            'password',
            'passwordConfirm'
          ], ''
        );

        $errors = [];

        if ($data['password']) {

          if ($data['password'] === $data['passwordConfirm']) {

            $hash = password_hash($data['password'],  CRYPT_BLOWFISH);

            $password_hash = Model::factory('Models\Settings')
                              ->where('key', 'password_hash')
                              ->find_one();

            $password_hash->value = $hash;

            $password_hash->save();

          } else {
            $errors[] = 'Пароли не совпадают!';
          }

        }

        $pagination = Model::factory('Models\Settings')->where('key', 'pagination')->find_one();
        $pagination->value = $data['pagination'];
        $pagination->save();

        $email = Model::factory('Models\Settings')->where('key', 'email')->find_one();
        $email->value = $data['email'];
        $email->save();

      }

      return $this->view->render($response, 'settings/index.html', 
	    	[
	    		'data' => $data,
          'errors' => $errors
	    	]
	    );

    }
    
}
