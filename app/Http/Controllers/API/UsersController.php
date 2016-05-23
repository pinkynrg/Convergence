<?php namespace App\Http\Controllers\API;

use Auth;
use App\Models\User;

class UsersController extends BaseController {

    public function all($params)
    {
    	$params['order'] = isset($params['order']) ? $params['order'] : ['last_name|ASC','first_name|ASC'];
    	$users = $this->query();
    	$users = parent::execute($users, $params);

        return $users;
    }

    public function find() {
    	$users = $this->query();
    	$users = $users->where("users.id","=",$params['id']);
        $user = $user->get()->first() ? $user->get()->first() : [];
    	return $user;
    }

    private function query() {
    	$users = User::select("users.*");
        $users->leftJoin('people','users.person_id','=','people.id');

        if (Auth::check() && !Auth::user()->active_contact->isE80()) {
        	$users->leftJoin('company_person','company_person.person_id','=','people.id');
            $users->where("company_person.company_id","=",Auth::user()->active_contact->company_id);
        }
		
		return $users;        
    }

}
