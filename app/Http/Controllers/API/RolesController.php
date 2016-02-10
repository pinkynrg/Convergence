<?php namespace App\Http\Controllers\API;

use App\Models\Role;
use DB;

class RolesController extends BaseController {

    public static function api($params)
    {
		$roles = Role::select("roles.*");
    	$roles = parent::execute($roles, $params);
        return $roles;
    }

}