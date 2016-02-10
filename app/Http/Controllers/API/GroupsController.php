<?php namespace App\Http\Controllers\API;

use App\Models\Group;
use DB;

class GroupsController extends BaseController {

    public static function api($params)
    {
        $groups = Group::select("groups.*");
    	$groups = parent::execute($groups, $params);
        return $groups;
    }

}
