<?php namespace App\Http\Controllers\API;

use App\Models\User;

class UsersController extends BaseController {

    public static function all($params)
    {
    	$params['order'] = isset($params['order']) ? $params['order'] : ['last_name|ASC','first_name|ASC'];
    	
        $users = User::select("users.*");
        $users->leftJoin('people','users.person_id','=','people.id');
        
    	$users = parent::execute($users, $params);

        return $users;
    }

}
