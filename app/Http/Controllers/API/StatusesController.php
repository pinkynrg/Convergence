<?php namespace App\Http\Controllers\API;

use App\Models\Status;

class StatusesController extends BaseController {

    public function all($params)
    {
    	$params['order'] = isset($params['order']) ? $params['order'] : ['id|ASC'];
    	
        $statuses = Status::select("statuses.*");
    	$statuses = parent::execute($statuses, $params);
        return $statuses;
    }

}
