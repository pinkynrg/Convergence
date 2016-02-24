<?php namespace App\Http\Controllers\API;

use App\Models\Person;
use DB;

class PeopleController extends BaseController {

    public static function read($params)
    {
        $params['order'] = isset($params['order']) ? $params['order'] : ['last_name|ASC','first_name|ASC'];
        $users = Person::select("people.*");
    	$users = parent::execute($users, $params);
        return $users;
    }

}
