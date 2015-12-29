<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class UpdateGroupRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Auth::user()->can('update-group');
	}

	public function forbiddenResponse()
	{
		return redirect()->route('groups.show',$this->route('id'))->withErrors(['You are not authorized to update groups']);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'name' => 'required|unique:groups,name,'.$this->route('id'),
			'display_name' => 'required',
			'description' => 'required',
			'group_type_id' => 'required'
		];
	}

}
