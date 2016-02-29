<?php namespace App\Http\Controllers\API;

use App\Models\Person;

class PeopleController extends BaseController {

    public function all($params)
    {
        $params['order'] = isset($params['order']) ? $params['order'] : ['last_name|ASC','first_name|ASC'];
        
        $users = Person::select("people.*");
    	$users = parent::execute($users, $params);
        return $users;
    }

}
