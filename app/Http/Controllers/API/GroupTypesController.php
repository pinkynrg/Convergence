<?php namespace App\Http\Controllers\API;

use App\Models\GroupType;
use DB;

class GroupTypesController extends BaseController {

    public static function api($params)
    {
        $group_types = GroupType::select("group_types.*");
    	$group_types = parent::execute($group_types, $params);
        return $group_types;
    }

}
