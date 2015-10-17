<?php

namespace Http\Controllers;

use \Model;
use Carbon\Carbon;

class Orders extends \AbstractController{

    public function index($request, $response, $args) {

      $count_orders = sizeof(Model::factory('Models\Order')->find_many());
      $limit = 10;

      $page = 1;
      if (isset($_GET['page'])) {
         $page = $_GET['page'];
      }

      $offset = ($page - 1) * $limit;

    	$orders = Model::factory('Models\Order')
        ->offset($offset)
        ->limit($limit)
        ->order_by_desc('id')
        ->find_many();

  	 	$all_status = Model::factory('Models\Status')->order_by_desc('id')->find_many();

  	 	$status = [];
  	 	$status[] = 'Новый';
  	 	foreach ($all_status as $one_status) {
          $status[$one_status->id] = $one_status->title;
      }

      $data = [];
      foreach ($orders as $order) {
      		if ( isset($status[$order->status_id]) ) {
      			$order->status = $status[$order->status_id];
      		} else {
      			$order->status = '-';
      		}

          $data[] = $order->as_array();
      }

      $pages = [];
      for ($i = 0; $i < ceil($count_orders / $limit); $i++) {
        if ($i+1 == $page) {
          $pages[$i+1] = [
            'item' => $i+1,
            'class' => 'active'
          ];
        } else {
          $pages[$i+1] = [
            'item' => $i+1,
            'class' => ''
          ];
        }
      }

      return $this->view->render($response, 'orders/index.html', 
	    	[
	    		'orders' => $data,
	    		'status' => $status,
          'pages' => $pages
	    	]
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

      $dt = Carbon::now();

      //$dt->subDays(400);

      $data['date'] = $dt->timestamp;

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
