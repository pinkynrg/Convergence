<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;
use URL;

class UpdateEscalationProfileEventsRequest extends Request {

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

	public function response(array $errors)
	{
		$id = $this->route('id');
		$num = $this->get('num');
		$url = URL::route('escalation_profiles.show',['id' => $id,'num' => $num]);

        return $this->redirector->to($url)->withInput($this->except($this->dontFlash))->withErrors($errors, $this->errorBag);    	
    }

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$rules = [];

		for ($i = 0; $i < Request::get('num'); $i++) {
			$rules['delay_time.'.$i] = 'numeric|required';
			$rules['event_id.'.$i] = 'numeric|required';
			$rules['fallback_contact_id.'.$i] = 'numeric|required';
		}

		return $rules;
	}

}
