<?php namespace App\Http\Controllers\API;

use App\Models\GroupType;

class GroupTypesController extends BaseController {

    public function all($params)
    {
    	$params['order'] = isset($params['order']) ? $params['order'] : ['display_name|ASC'];
    	
        $group_types = GroupType::select("group_types.*");
    	$group_types = parent::execute($group_types, $params);
        return $group_types;
    }

}
