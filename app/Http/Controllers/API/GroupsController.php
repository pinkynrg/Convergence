<?php namespace App\Http\Controllers\API;

use App\Models\Group;

class GroupsController extends BaseController {

    public static function all($params)
    {
    	$params['order'] = isset($params['order']) ? $params['order'] : ['display_name|ASC'];
    	
        $groups = Group::select("groups.*");
    	$groups = parent::execute($groups, $params);
        return $groups;
    }

}
