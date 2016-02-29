<?php namespace App\Http\Controllers\API;

use App\Models\Permission;

class PermissionsController extends BaseController {

    public function all($params)
    {
    	$params['order'] = isset($params['order']) ? $params['order'] : ['display_name|ASC'];
    	
        $permissions = Permission::select("permissions.*");
    	$permissions = parent::execute($permissions, $params);
        return $permissions;
    }

}
