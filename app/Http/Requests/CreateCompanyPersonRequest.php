<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class CreateCompanyPersonRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		$authorize = Request::get('company_id') == 1 ? Auth::user()->can('create-employee') : Auth::user()->can('create-contact');
		return $authorize;
	}

	public function forbiddenResponse()
	{
		return Request::get('company_id') == 1 ? redirect()->route('company_person.employees')->withErrors(['You are not authorized to create a new employee']) : redirect()->route('companies.show',Request::get('company_id'))->withErrors(['You are not authorized to create a new company contact']);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'person_fn' => 'required',
			'person_ln' => 'required',
			'group_type_id' => 'required|numeric',
			'company_id' => 'required|numeric',
			'person_id' => 'numeric',
			'department_id' => 'required|numeric', 
			'title_id' => 'required|numeric',
			'phone' => 'numeric',
			'cellphone' => 'numeric',
			'extension' => 'numeric',
			'email' => 'email'
		];
	}

}
