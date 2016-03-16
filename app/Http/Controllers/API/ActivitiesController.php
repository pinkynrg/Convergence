<?php namespace App\Http\Controllers\API;

use App\Models\Activity;

class ActivitiesController extends BaseController {

    public function all($params)
    {
        $params['order'] = isset($params['order']) ? $params['order'] : ['activity_log.created_at|DESC'];
        
        $activities = Activity::select("activity_log.*");
        $activities->leftJoin('users','users.id','=','activity_log.user_id');

    	$activities = parent::execute($activities, $params);
        return $activities;
    }

}
