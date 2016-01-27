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
		return Auth::user()->can('create-contact');
	}

	public function forbiddenResponse()
	{
		return redirect()->route('companies.show',Request::get('company_id'))->withErrors(['You are not authorized to create a new contact']);
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
			'phone' => 'numeric',
			'extension' => 'numeric',
			'cellphone' => 'numeric',
			'email' => 'email',
			'department_id' => 'required|numeric', 
			'title_id' => 'required|numeric',
			'group_type_id' => 'required|numeric',
			'company_id' => 'required|numeric',
			'person_id' => 'numeric'
		];
	}

}
