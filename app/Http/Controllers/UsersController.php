<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Person;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Form;
use Auth;
use Input;
use Hash; 

class UsersController extends Controller {
	
	public function index() {
		if (Auth::user()->can('read-all-user')) {
	        $data['menu_actions'] = [Form::addItem(route('users.create'), 'Add user')];
			$data['active_search'] = true;	        
			$data['users'] = User::paginate(50);
        	$data['title'] = "Users";
			return view('users/index',$data);
        }
        else return redirect()->back()->withErrors(['Access denied to users index page']);		
	}

	public function create() {
		$people = Person::select("people.*");
		$people->leftJoin('users','users.person_id','=','people.id');
		$people->whereNull('users.id');
		$people->orderBy('people.last_name');
		$data['people'] = $people->get();

		$data['title'] = "Create User";
		return view('users/create', $data);	
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
		$data['title'] = "Edit User of ".$data['user']->owner->name();
		$data['user'] = User::find($id);
		return view('users/edit', $data);	
	}

	public function update($id, UpdateUserRequest $request) {
		$user = User::find($id);
		$user->password = Hash::make(Input::get('password')); 
        $user->save();
		return redirect()->route('people.show',$user->owner->id)->with('successes',['User updated successfully']);
	}

	public function ajaxUsersRequest($params = "") {

        parse_str($params,$params);

		$contacts = User::select("users.*");
		$contacts->leftJoin('people','users.person_id','=','people.id');
		
		if (isset($params['search'])) {
			$contacts->where(function($query) use ($params) {
	            $query->where('username','like','%'.$params['search'].'%');
	            $query->where('people.last_name','like','%'.$params['search'].'%');
	            $query->orWhere('people.first_name','like','%'.$params['search'].'%');;
			});
		}

        // apply ordering
        if (isset($params['order'])) {
    		$contacts->orderByRaw("case when ".$params['order']['column']." is null then 1 else 0 end asc");
            $contacts->orderBy($params['order']['column'],$params['order']['type']);
        }

		//paginate
		$data['users'] = $contacts->paginate(50);

        return view('users/users
        	',$data);
	}
}