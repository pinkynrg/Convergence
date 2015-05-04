<?php namespace Convergence\Http\Controllers;

use Convergence\Http\Requests\LoginRequest;
use Input;
use Auth;
use Hash;
use Convergence\Models\User;

class LoginController extends Controller {

	private function MD5ToHASHLaravelConversion($request, $user, $password) {
		$user->password = Hash::make($password);
		$user->save();				
		return $this->doLogin($request);
	}

	public function showLogin()
	{
	    return view('login/login');
	}

	public function doLogin(LoginRequest $request)
	{

		$username = Input::get('username');
		$password = Input::get('password');

		if (Auth::attempt(array('username' => $username, 'password' => $password), true)) {
			return redirect()->route('tickets.index');
		}

		else {
			
			$user = User::where('username','=',$username)->where('password','=',md5($password))->first();

			if (!is_null($user))
				return $this->MD5ToHASHLaravelConversion($request, $user,$password);
			else
				return redirect()->route('login.index')->withErrors(array('The username or the password are incorrect'));
		}
	}

	public function doLogout() {
		Auth::logout();
		return view('login.login');
	}
}

?>