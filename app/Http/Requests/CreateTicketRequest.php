<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class CreateTicketRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Auth::user()->can('create-ticket');
	}

	public function forbiddenResponse()
	{
		return redirect()->route('tickets.index')->withErrors(['You are not authorized to create a new ticket']);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'status_id' => 'required|integer',  
			'company_id' => 'required|integer',  
			'contact_id' => 'integer',  
			'equipment_id' => 'integer',  
			'assignee_id' => 'required|integer',  
			'title' => 'required|string',
			'post' => 'required|string',
			'division_id' => 'required|integer',  
			'job_type_id' => 'required|integer',
			'priority_id' => 'required|integer'
		];
	}

}
