<?php namespace App\Http\Controllers\API;

use App\Models\Permission;
use DB;

class PermissionsController extends BaseController {

    public static function api($params)
    {
        $permissions = Permission::select("permissions.*");
    	$permissions = parent::execute($permissions, $params);
        return $permissions;
    }

}
