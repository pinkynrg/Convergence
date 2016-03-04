<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class UpdatePostDraftRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Auth::user()->can('create-post');
	}

	public function forbiddenResponse()
	{
		return redirect()->route('tickets.show',Request::get("ticket_id"))->withErrors(['You are not authorized to create a new post']);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'post' => 'required|string',
			'status_id' => 'required|integer',
			'priority_id' => 'required|integer'
		];
	}

}
