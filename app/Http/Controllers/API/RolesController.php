<?php namespace App\Http\Controllers\API;

use App\Models\Role;

class RolesController extends BaseController {

    public static function all($params)
    {
 		$params['order'] = isset($params['order']) ? $params['order'] : ['display_name|ASC'];

		$roles = Role::select("roles.*");
    	$roles = parent::execute($roles, $params);
        return $roles;
    }

}