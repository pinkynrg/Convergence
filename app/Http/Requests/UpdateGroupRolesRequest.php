<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class UpdateGroupRolesRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Auth::user()->can('update-group-roles');
	}

	public function forbiddenResponse()
	{
		return redirect()->route('groups.show',$this->route('id'))->withErrors(['You are not authorized to update group roles']);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [];
	}

}
