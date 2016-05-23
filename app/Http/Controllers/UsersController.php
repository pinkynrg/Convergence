<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Person;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Session;
use Request;
use Form;
use Auth;
use Input;
use Hash; 

class UsersController extends BaseController {
	
	public function index() {
		if (Auth::user()->can('read-all-user')) {
	    	$data['users'] = self::API()->all(Request::input());
			$data['menu_actions'] = [Form::addItem(route('users.create'), 'Create user',Auth::user()->can('create-user'))];
			$data['active_search'] = implode(",",['users.username','people.first_name','people.last_name']);
			$data['title'] = "Users";
			return Request::ajax() ? view('users/users',$data) : view('users/index',$data);
        }
        else return redirect()->back()->withErrors(['Access denied to users index page']);
	}

	public function create($id=null) {
		if (Auth::user()->can('create-user')) {
			$data['title'] = "Create User";
			$data['user'] = new User();
			$data['user']->person_id = $id;
			$people = Person::select("people.*");
			$people->leftJoin('users','users.person_id','=','people.id');
			$people->whereNull('users.id');
			$people->orderBy('people.last_name');
			$data['people'] = $people->get();
			return view('users/create', $data);
		}
		else return redirect()->back()->withErrors(['Access denied to users create page']);
	}

	public function store(CreateUserRequest $request) {
		
		$user = new User;
		$user->person_id = Input::get('person_id');
		$user->username = Input::get('username');
		$user->password = Hash::make(Input::get('password'));
		$user->save();

		return redirect()->route('users.index')->with('successes',['User created successfully']);
	}

	public function edit($id) {

		$data['user'] = User::find($id);

		if 	(Auth::user()->can('update-user') || 
			(Auth::user()->active_contact->person->user->id == $id && Auth::user()->can('update-own-user')) ||
			(!$data['user']->owner->isE80() && Auth::user()->can('update-customer-user'))) {

			$data['title'] = "Edit User of ".$data['user']->owner->name();
			$data['user'] = User::find($id);
			return view('users/edit', $data);
		}
		else return redirect()->back()->withErrors(['Access denied to users edit page']);
	}

	public function update($id, UpdateUserRequest $request) {
		$user = User::find($id);
		$user->password = Hash::make(Input::get('password')); 
        $user->save();
		return redirect()->route('people.show',$user->owner->id)->with('successes',['User updated successfully']);
	}

	public function switchCompanyPerson($id) {
		
		$valid_company_person = false;
		$company_person_id = Input::get('switch_company_person_id');
		$user = User::find($id);

		foreach ($user->owner->company_person as $company_person) {
			if ($company_person_id == $company_person->id) {
				$valid_company_person = true;
			}
		}

		if ($valid_company_person || Session::get('debug') == true) {
			$user->active_contact_id = $company_person_id;
			$user->save();
		}
		return redirect()->route('root');	
	}
}