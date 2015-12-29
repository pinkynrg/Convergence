<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class CreateRoleRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Auth::user()->can('create-role');
	}

	public function forbiddenResponse()
	{
		return redirect()->route('roles.index')->withErrors(['You are not authorized to create a new role']);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'name' => 'required|unique:roles',
			'display_name' => 'required',
			'description' => 'required'
		];
	}

}
