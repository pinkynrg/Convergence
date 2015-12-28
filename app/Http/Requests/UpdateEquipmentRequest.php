<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class UpdateEquipmentRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		// return Auth::user()->can('create-equipment');
		return true;
	}

	public function forbiddenResponse()
	{
		return redirect()->route('companies.show',Request::get('company_id'))->withErrors(['You are not authorized to update equipments']);
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
			'notes' => 'required',  
			'warranty_expiration' => 'date|required'
		];
	}

}
