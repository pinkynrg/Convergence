<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class CreateUserRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Auth::user()->can('create-user');
	}

	public function forbiddenResponse()
	{
		return redirect()->route('users.index')->withErrors(['You are not authorized to create a user']);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'username' => 'required|string|unique:users',
			'person_id' => 'required|numeric|unique:users',
            'password' => 'required|string',
            'password2' => 'required|string|same:password',
		];
	}

}
