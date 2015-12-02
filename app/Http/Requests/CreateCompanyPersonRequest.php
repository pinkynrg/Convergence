<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateCompanyPersonRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
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
