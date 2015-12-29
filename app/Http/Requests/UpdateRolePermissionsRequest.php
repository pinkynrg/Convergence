<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class UpdateRolePermissionsRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Auth::user()->can('update-role-permissions');
	}

	public function forbiddenResponse()
	{
		return redirect()->route('roles.show',$this->route("id"))->withErrors(['You are not authorized to update role permissions']);
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
