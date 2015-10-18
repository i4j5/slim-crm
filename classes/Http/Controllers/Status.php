<?php

namespace Http\Controllers;

use \Model;
use Carbon\Carbon;

class Status extends \AbstractController{

    public function index($request, $response, $args) {

    	$status = Model::factory('Models\Status')
        ->order_by_desc('id')
        ->find_many();

      $data = [];
      foreach ($status as $item) {

          $data[] = $item->as_array();
      }

      return $this->view->render($response, 'status/index.html', 
	    	[
	    		'data' => $data
	    	]
	    );

    }

    public function create($request, $response, $args) {

      $data = \Ohanzee\Helper\Arr::extract(
        $request->getParsedBody(),
        [
          'title',
          'description'
        ], ''
      );

      if ($request->isPost()) {

	    	$status = Model::factory('Models\Status')->create($data);

				$status->save();

				return $response->withRedirect('/settings/status/edit/' . $status->id);

      }

      return $this->view->render($response, 'status/create.html', 
	    	[
          'data' => $data
        ]
	    );
	    
    }

    public function edit($request, $response, $args) {

    	$id = (int) $args['id'];

    	$status = Model::factory('Models\Status')->find_one($id);

    	$data = $status->as_array();

      if ($request->isPost()) {

	      $data = \Ohanzee\Helper\Arr::extract(
	        $request->getParsedBody(),
	        [
	          'title',
            'description'
	        ], ''
	      );

	    	$status->values($data);

				$status->save();

        $data['id'] = $id;

      }

      return $this->view->render($response, 'status/edit.html', 
	    	[
          'data' => $data
        ]
	    );
	    
    }

    public function delete($request, $response, $args) {

    	$id = (int) $args['id'];

    	$order = Model::factory('Models\Status')->find_one($id);

    	$order->delete();

    	return $response->withRedirect('/settings/status');
	    
    }
    
}
