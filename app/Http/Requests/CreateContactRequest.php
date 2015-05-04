<?php namespace Convergence\Http\Requests;

use Convergence\Http\Requests\Request;

class CreateContactRequest extends Request {

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
			'customer_id' => 'required|numeric', 
			'name' => 'required|string', 
			'phone' => 'numeric', 
			'cellphone' => 'numeric', 
			'email' => 'email'
		];
	}

}
