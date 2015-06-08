<?php namespace Convergence\Http\Requests;

use Convergence\Http\Requests\Request;

class CreateCompanyRequest extends Request {

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
			'name' => 'required',
            'city' => 'required|string',
            'country' => 'required|string',
            'address' => 'required',
            'state' => 'required|string',
            'zip_code' => 'required|numeric',
            'group_email' => 'email',
            'account_manager_id' => 'required'
		];
	}

}
