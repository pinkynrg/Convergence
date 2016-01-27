<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class UpdateProfileEventsRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Auth::user()->can('update-escalation-profile-events');
	}

	public function forbiddenResponse()
	{
		return redirect()->route('escalation_profiles.show',$this->route('id'))->withErrors(['You are not authorized to update escalation profile events']);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'delay_time.' => 'required'
		];
	}

}
