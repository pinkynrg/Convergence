<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateCompanyRequest extends Request {

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
            'country' => 'required|string',
            'state' => 'required|string',
            'city' => 'required|string',
            'address' => 'required',
            'zip_code' => 'required|numeric',
            'group_email' => 'email',
		];
	}

}
