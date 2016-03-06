<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class UpdateCompanyRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Auth::user()->can('update-company');
	}

	public function forbiddenResponse()
	{
		return redirect()->route('companies.show',$this->route('id'))->withErrors(['You are not authorized to update companies']);
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
            'account_manager_id' => 'required|numeric',
            'support_type_id' => 'required|numeric',
            'connection_type_id' => 'required|numeric',
            'account_manager_id' => 'required|numeric',
            'main_contact_id' => 'required|numeric',
            'escalation_profile_id' => 'required|numeric'
        ];
	}

}
