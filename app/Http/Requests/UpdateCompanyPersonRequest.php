<?php namespace Convergence\Http\Requests;

use Convergence\Http\Requests\Request;

class UpdateCompanyPersonRequest extends Request {

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
			'company_id' => 'numeric',
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
