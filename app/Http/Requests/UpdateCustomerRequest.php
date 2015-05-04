<?php namespace Convergence\Http\Requests;

use Convergence\Http\Requests\Request;

class UpdateCustomerRequest extends Request {

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
			'company_name' => 'required',
            'country' => 'required|string',
            'state' => 'required|string',
            'city' => 'required|string',
            'address' => 'required',
            'zip_code' => 'required|numeric',
            'group_email' => 'email',
            'account_manager_id' => 'required'
		];
	}

}
