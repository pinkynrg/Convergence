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

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'name' => 'required',
			'display_name' => 'required',
			'description' => 'required',
		];
	}

}
