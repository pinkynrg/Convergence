<?php namespace App\Http\Controllers\API;

use App\Models\Priority;

class PrioritiesController extends BaseController {

    public function all($params)
    {
    	$params['order'] = isset($params['order']) ? $params['order'] : ['id|ASC'];
    	
        $priorities = Priority::select("priorities.*");
    	$priorities = parent::execute($priorities, $params);
        return $priorities;
    }

}
