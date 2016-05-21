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
		return Auth::user()->can('update-equipment');
	}

	public function forbiddenResponse()
	{
		return redirect()->route('equipment.show',$this->get('id'))->withErrors(['You are not authorized to update equipment']);
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
			'warranty_expiration' => 'date'
		];
	}

}
