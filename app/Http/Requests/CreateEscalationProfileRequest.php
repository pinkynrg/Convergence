<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class CreateEscalationProfileRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Auth::user()->can('create-escalation-profiles');
	}

	public function forbiddenResponse()
	{
		return redirect()->route('escalation_profiles.index',$this->get('id'))->withErrors(['You are not authorized to create an escalation profile']);
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
			'description' => 'required'
		];
	}

}
