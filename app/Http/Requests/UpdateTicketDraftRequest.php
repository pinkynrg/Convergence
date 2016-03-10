<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class UpdateTicketDraftRequest extends Request {

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
		return [];
	}

}
