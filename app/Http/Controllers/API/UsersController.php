<?php namespace App\Http\Controllers\API;

use App\Models\User;
use DB;

class UsersController extends BaseController {

    public static function api($params)
    {
    	
        $users = User::select("users.*");
        $users->leftJoin('people','users.person_id','=','people.id');
        
    	$users = parent::execute($users, $params);

        return $users;
    }

}
