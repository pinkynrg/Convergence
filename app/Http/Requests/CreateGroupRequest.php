<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class CreateGroupRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		// return Auth::user()->can('create-group');
		return true;
	}

	public function forbiddenResponse()
	{
		return redirect()->route('groups.index')->withErrors(['You are not authorized to create a new group']);
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
			'group_type_id' => 'required'
		];
	}

}
