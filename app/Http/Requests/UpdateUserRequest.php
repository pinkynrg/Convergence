<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\User;
use Auth;

class UpdateUserRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		$user = User::find($this->route('id'));

		return 	(Auth::user()->can('update-user')) || 
				(Auth::user()->can('update-own-user') && Auth::user()->active_contact->person->user->id == $this->route('id')) ||
				(Auth::user()->can('update-customer-user') && !$user->owner->isE80());
	}

	public function forbiddenResponse()
	{
		return redirect()->route('users.index')->withErrors(['You are not authorized to update users']);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
            'password' => 'required|password|string',
            'password2' => 'required|string|same:password',
		];
	}

}
