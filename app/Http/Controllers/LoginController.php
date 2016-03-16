<?php namespace App\Http\Controllers;

use Input;
use Auth;
use Hash;
use Session;
use Activity;
use App\Models\User;
use App\Models\CompanyPerson;
use App\Models\Division;
use App\Models\Department;
use App\Models\Title;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StartRequest;


class LoginController extends Controller {

	public function showLogin()
	{
		if (!Auth::check()) {
			$data['title'] = 'login';
	    	return view('login/login',$data);
		}
		else {
			return redirect()->intended();
		}
	}

	public function doLogin(LoginRequest $request)
	{

		$username = Input::get('username');
		$password = Input::get('password');

		if (Auth::attempt(array('username' => $username, 'password' => $password), true)) {
			
			if (is_null(Auth::user()->active_contact_id)) {
				$user = User::find(Auth::user()->id);
				$contact = CompanyPerson::where('person_id',Auth::user()->person_id)->first();
				$user->active_contact_id = $contact->id;
				$user->save();
			}
			
			Activity::log('User Login');

			return redirect()->intended()->with('successes',['Accessed successfully']);
		}

		else {
			
			$user = User::where('username','=',$username)->where('password','=',md5($password))->first();

			if ($user) {
				
				if ($this->safeEnough($password)) {
					Session::set('password',Hash::make($password));
				}

				Session::set('user_id',$user->id);
				return redirect()->route('login.start');
			}
			else {
				return redirect()->route('login.index')->withErrors(['The username or the password are incorrect']);
			}
		}
	}

	public function doLogout() {
		if (Auth::check()) {
			Activity::log('User Logout');
			Session::flush();
			Auth::logout();
		}
		return redirect()->route('login.index');
	}

	public function start() {

		if (Session::get('user_id')) {

			$data['title'] = "Welcome";
			$user = User::find(Session::get('user_id'));
			$profile = new \stdClass();
			$profile->id = $user->id;
			$profile->first_name = $user->owner->first_name;
			$profile->last_name = $user->owner->last_name;
			$profile->username = $user->username;
			$profile->contacts = new \stdClass();
			$profile->contact = $user->active_contact;

			foreach ($user->owner->company_person as $contact) {
				$profile->contacts->{$contact->id} = $contact;
				if (!isset($profile->first_contact)) { 
					$profile->first_contact = $contact->id; 
				}
			}

			$data['profile'] = $profile;
			$data['departments'] = Department::orderBy("name")->get();
			$data['titles'] = Title::orderBy("name")->get();

			return view('login.start',$data);
		}
		else {
			return redirect()->intended();
		}
	}

	public function storeInfo($id, StartRequest $request) {

		$user = User::find($id);
		$person = $user->owner;

		$password = $request->get('password') ? Hash::make($request->get('password')) : Session::get('password');
		$user->password = $password;
		$user->save();

		$person->first_name = $request->get('first_name');
		$person->last_name = $request->get('last_name');
		$person->save();

		if ($request->get('use_info_all_contacts') == "true") {

			$contact = $request->get('contact');

			foreach ($person->company_person as $contact) {
				$contact->phone = $contact['email'];
				$contact->extension = $contact['extension'];
				$contact->cellphone = $contact['cellphone'];
				$contact->email = $contact['email'];
				$contact->department_id = $contact['department_id'];
				$contact->title_id = $contact['title_id'];
				$contact->save();
			}
		}
		else {

			$contacts = $request->get('contacts');

			foreach ($contacts as $key => $new_contact) {
				$contact = CompanyPerson::find($key);
				$contact->phone = $new_contact['phone'];
				$contact->extension = $new_contact['extension'];
				$contact->cellphone = $new_contact['cellphone'];
				$contact->email = $new_contact['email'];
				$contact->department_id = $new_contact['department_id'];
				$contact->title_id = $new_contact['title_id'];
				$contact->save();
			}
		}

		Session::flush();

		return redirect()->route('login.login')->withErrors(['Please, login again']);
	}

	private function safeEnough($password) {
		$valid = true;
		$valid = strlen($password) >= 10 ? $valid : false;				// length greater or equal than 10
		$valid = preg_match('/[A-Z]/', $password) ? $valid : false;		// at least 1 uppercase
		$valid = preg_match('/[a-z]/', $password) ? $valid : false;		// at least 1 lowercase
		$valid = preg_match('/[1-9]/', $password) ? $valid : false;		// at least 1 digit
    	return $valid;
	}
}

?>