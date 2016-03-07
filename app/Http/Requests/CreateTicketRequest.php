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
		$rules = [
			'company_id' => 'required|numeric',  
			'contact_id' => 'required|numeric',
			'equipment_id' => 'required|numeric',  
			'title' => 'required|string',
			'post' => 'required|string',
		];

		if (Auth::user()->active_contact->isE80()) {
		
			$rules = $rules + [
				'assignee_id' => 'required|numeric',  
				'division_id' => 'required|numeric',  
				'job_type_id' => 'required|numeric',
				'priority_id' => 'required|numeric',
				'level_id' => 'required|numeric'
			];
		}

		return $rules;

	}
}
