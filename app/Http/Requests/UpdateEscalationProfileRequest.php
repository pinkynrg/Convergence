<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class UpdateEscalationProfileRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Auth::user()->can('update-escalation-profiles');
	}

	public function forbiddenResponse()
	{
		return redirect()->route('escalation_profiles.show',$this->get('id'))->withErrors(['You are not authorized to update escalation profiles']);
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
			'description' => 'required',  
			// 'delay_time' => 'required|integer',  
			// 'event_id' => 'integer',
			// 'fallback_company_person_id' => 'integer', // required only if event_id is null
		];
	}

}
