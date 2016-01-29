<?php namespace App\Http\Controllers;

use Input;
use Auth;
use Hash;
use Activity;
use App\Models\User;
use App\Models\CompanyPerson;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller {

	private function MD5ToHASHLaravelConversion($request, $user, $password) {
		$user->password = Hash::make($password);
		$user->save();				
		return $this->doLogin($request);
	}

	public function showLogin()
	{
		if (Auth::check()) {
			return redirect()->intended('defaultpage');
		}
		else {
			$data['title'] = 'login';
	    	return view('login/login',$data);
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

			if (!is_null($user)) {
				return $this->MD5ToHASHLaravelConversion($request, $user,$password);
			}
			else {
				return redirect()->route('login.index')->withErrors(['The username or the password are incorrect']);
			}
		}
	}

	public function doLogout() {
		$data['title'] = 'login';
		Activity::log('User Logout');
		Auth::logout();
		return view('login.login');
	}
}

?>