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
            'pagination'
          ], ''
        );

        echo $data['pagination'];

        $pagination = Model::factory('Models\Settings')->where('key', 'pagination')->find_one();
        $pagination->value = $data['pagination'];
        $pagination->save();

        $email = Model::factory('Models\Settings')->where('key', 'email')->find_one();
        $email->value = $data['email'];
        $email->save();

      }

      return $this->view->render($response, 'settings/index.html', 
	    	[
	    		'data' => $data
	    	]
	    );

    }
    
}
