<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class CreateEquipmentRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Auth::user()->can('create-equipment');
	}

	public function forbiddenResponse()
	{
		return redirect()->route('companies.show',Request::get('company_id'))->withErrors(['You are not authorized to create a new equipment']);
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
			'cc_number' => 'required|integer',  
			'serial_number' => 'required',  
			'equipment_type_id' => 'integer|required',  
			'company_id' => 'required|integer',  
			'warranty_expiration' => 'date|required'
		];
	}

}
