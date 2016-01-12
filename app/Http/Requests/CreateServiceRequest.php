<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class CreateServiceRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Auth::user()->can('create-service');
	}

	public function forbiddenResponse()
	{
		return redirect()->route('companies.show', Request::get('company_id'))->withErrors(['You are not authorized to create a new service']);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$rules = [
			'company_id' => 'required|numeric',
			'hotel_id' => 'numeric',
			'external_contact_id' => 'required|numeric',
			'internal_contact_id' => 'required|numeric',
			'job_number_internal' => 'required_if:has_internal,1',
			'job_number_remote' => 'required_if:has_remote,1',
			'job_number_onsite' => 'required_if:has_onsite,1'
		];

		for ($i = 0; $i < Request::get('technician_number'); $i++) {
			$rules['technician_id.'.$i] = 'required';
			$rules['division_id.'.$i] = 'required';
			$rules['work_description.'.$i] = 'required';
			
			$rules['tech_internal_hours.'.$i] = 'required_if:tech_has_internal.'.$i.',1|numeric|min:1';
			$rules['tech_internal_start.'.$i] = 'required_if:tech_has_internal.'.$i.',1|date';
			$rules['tech_internal_end.'.$i] = 'required_if:tech_has_internal.'.$i.',1|date|after_equal:tech_internal_start.'.$i;
			
			$rules['tech_remote_hours.'.$i] = 'required_if:tech_has_remote.'.$i.',1|numeric|min:1';
			$rules['tech_remote_start.'.$i] = 'required_if:tech_has_remote.'.$i.',1|date';
			$rules['tech_remote_end.'.$i] = 'required_if:tech_has_remote.'.$i.',1|date|after_equal:tech_remote_start.'.$i;
			
			$rules['tech_onsite_hours.'.$i] = 'required_if:tech_has_onsite.'.$i.',1|numeric|min:1';
			$rules['tech_onsite_start.'.$i] = 'required_if:tech_has_onsite.'.$i.',1|date';
			$rules['tech_onsite_end.'.$i] = 'required_if:tech_has_onsite.'.$i.',1|date|after_equal:tech_onsite_start.'.$i;
		}

		return $rules;
	}

}
