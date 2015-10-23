<?php

namespace Http\Controllers;

use \Model;
use Carbon\Carbon;

class Orders extends \AbstractController{

    public function index($request, $response, $args) {

      $count_orders = sizeof(Model::factory('Models\Order')->find_many());
      $limit = Model::factory('Models\Settings')->where('key', 'pagination')->find_one();
      $limit = (int) $limit->value;

      $messages = $this->flash->getMessages();

      if (isset($_GET['page'])) {
         $page = (int) $_GET['page'];
      } else if (isset($messages['page'])) {
        $page = (int) $messages['page'][0];
      } else {
        $page = 1;
      }

      if ($page == 0) {
        $page = 1;
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

      if ( (count($pages) < $page) && (count($pages) != 0)) {
        return $response->withRedirect('/?page=' . count($pages));
      }

      $this->flash->addMessage('page', $page);

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

      $all_status = Model::factory('Models\Status')->order_by_desc('id')->find_many();

      $status = [];
      foreach ($all_status as $one_status) {
          $status[] = $one_status->as_array();
      }

      $dt = Carbon::now();

      //$dt->subDays(400);

      $data['date'] = $dt->timestamp;

      if ($request->isPost()) {

	    	$order = Model::factory('Models\Order')->create($data);

				$order->save();

				return $response->withRedirect('/edit/' . $order->id);

      }

      return $this->view->render($response, 'orders/create.html', 
	    	[
          'data' => $data,
          'status' => $status
        ]
	    );
	    
    }

    public function edit($request, $response, $args) {

    	$id = (int) $args['id'];

      $messages = $this->flash->getMessages();
      $this->flash->addMessage('page', $messages['page'][0]);

    	$order = Model::factory('Models\Order')->find_one($id);

    	$data = $order->as_array();

      $all_status = Model::factory('Models\Status')->order_by_desc('id')->find_many();

      $status = [];

      $status[] = [
        'id' => '0',
        'title' => 'Новый'
      ];

      foreach ($all_status as $one_status) {
          $status[] = $one_status->as_array();
      }

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

        $data['id'] = $id;

      }

      return $this->view->render($response, 'orders/edit.html', 
	    	[
          'data' => $data,
          'status' => $status
        ]
	    );
	    
    }

    public function delete($request, $response, $args) {

    	$id = (int) $args['id'];

    	$order = Model::factory('Models\Order')->find_one($id);

    	$order->delete();

      $messages = $this->flash->getMessages();

      $page = 1;
      if (isset($messages['page'])) {
         $page = $messages['page'][0];
      }

    	return $response->withRedirect('/?page=' . $page);
	    
    }
    
}
