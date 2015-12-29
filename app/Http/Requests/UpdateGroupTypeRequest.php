<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class UpdateGroupTypeRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Auth::user()->can('update-group-type');
	}

	public function forbiddenResponse()
	{
		return redirect()->route('group_types.show',$this->route('id'))->withErrors(['You are not authorized to update group types']);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'name' => 'required|unique:group_types,name,'.$this->route('id'),
			'display_name' => 'required',
			'description' => 'required'
		];
	}

}
