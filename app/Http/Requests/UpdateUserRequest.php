<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class UpdateUserRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Auth::user()->can('update-user');
	}

	public function forbiddenResponse()
	{
		return redirect()->route('users.index')->withErrors(['You are not authorized to update users']);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
            'password' => 'required|string',
            'password2' => 'required|string|same:password',
		];
	}

}
