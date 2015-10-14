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

    public function create($request, $response, $args) {

      $data = \Ohanzee\Helper\Arr::extract(
        $request->getParsedBody(),
        [
          'name',
          'phone',
          'email',
          'title',
          'status_id'
        ], ''
      );

      $data['date'] = date("l dS of F Y h:i:s A");

      if ($request->isPost()) {

	    	$order = Model::factory('Models\Order')->create($data);

				$order->save();

				return $response->withRedirect('/edit/' . $order->id);

      }

      return $this->view->render($response, 'orders/create.html', 
	    	['data' => $data]
	    );
	    
    }

    public function edit($request, $response, $args) {

    	$id = (int) $args['id'];

    	$order = Model::factory('Models\Order')->find_one($id);

    	$data = $order->as_array();

      if ($request->isPost()) {

	      $data = \Ohanzee\Helper\Arr::extract(
	        $request->getParsedBody(),
	        [
	          'name',
	          'phone',
	          'email',
	          'title',
	          'status_id'
	        ], ''
	      );

	    	$order->values($data);

				$order->save();

      }

      return $this->view->render($response, 'orders/edit.html', 
	    	['data' => $data]
	    );
	    
    }

    public function delete($request, $response, $args) {

    	$id = (int) $args['id'];

    	$order = Model::factory('Models\Order')->find_one($id);

    	$order->delete();

    	return $response->withRedirect('/');
	    
    }
    
}
