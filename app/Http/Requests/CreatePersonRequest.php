<?php namespace Convergence\Http\Requests;

use Convergence\Http\Requests\Request;

class CreatePersonRequest extends Request {

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
			'first_name' => 'required|string',
			'last_name' => 'required|string',  
			'department_id' => 'required|numeric', 
			'title_id' => 'required|numeric',
			'phone' => 'numeric',
			'email' => 'email'
		];
	}

}
