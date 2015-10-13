<?php

namespace Http\Controllers;

use \Model;

class Orders extends \AbstractController{

    public function index($request, $response, $args) {

    	$orders = Model::factory('Models\Order')->find_many();

      $data = [];
      foreach ($orders as $order) {
          $data[] = $order->as_array();
      }

      return $this->view->render($response, 'orders/index.html', 
	    	['orders' => $data]
	    );

    }
    
}
