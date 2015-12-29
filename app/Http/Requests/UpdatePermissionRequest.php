<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class UpdatePermissionRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Auth::user()->can('update-permission');
	}

	public function forbiddenResponse()
	{
		return redirect()->route('permissions.show',$this->route('id'))->withErrors(['You are not authorized to update permissions']);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'name' => "required|unique:permissions,name,".$this->route('id'),
			'display_name' => 'required',
			'description' => 'required'
		];
	}

}
