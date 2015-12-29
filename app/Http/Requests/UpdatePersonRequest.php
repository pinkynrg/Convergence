<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class UpdatePersonRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Auth::user()->can('update-person');
	}

	public function forbiddenResponse()
	{
		return redirect()->route('people.show',$this->route('id'))->withErrors(['You are not authorized to update people']);
	}


	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'first_name' => 'required|string',
			'last_name' => 'required|string'
		];
	}

}
