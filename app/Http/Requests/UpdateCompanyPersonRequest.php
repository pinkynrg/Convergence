<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\CompanyPerson;
use Auth;

class UpdateCompanyPersonRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		$company_person = CompanyPerson::find($this->route('company_person_id'));	
		return 	(Auth::user()->can('update-contact') || 
				(Auth::user()->can('update-own-contact') && Auth::user()->active_contact->id == $this->route('company_person_id')) ||
				(!$company_person->isE80() && Auth::user()->can('update-customer-contact')));
	}

	public function forbiddenResponse()
	{
		return Request::get('company_id') == ELETTRIC80_COMPANY_ID ? redirect()->route('company_person.show',$this->route('id'))->withErrors(['You are not authorized to update employees']) : redirect()->route('company_person.show',$this->route('id'))->withErrors(['You are not authorized to update contacts']);
	}


	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'person_id' => 'numeric',
			'department_id' => 'numeric', 
			'title_id' => 'numeric',
			'phone' => 'numeric',
			'cellphone' => 'numeric',
			'extension' => 'numeric',
			'email' => 'required|email',
			'group_id' => 'required|numeric'
		];
	}

}
